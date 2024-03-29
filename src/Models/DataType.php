<?php

namespace TCG\Voyager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\{Translatable,HasMetaFields};

class DataType extends Model
{
    use Translatable,HasMetaFields;

    protected $translatable = ['display_name_singular', 'display_name_plural'];

    protected $table = 'data_types';

    protected $fillable = [
        'name',
        'slug',
        'display_name_singular',
        'display_name_plural',
        'icon',
        'model_name',
        'policy_name',
        'controller',
        'description',
        'generate_permissions',
        'server_side',
        'per_page',
        'widgetable',
        'order_column',
        'order_display_column',
        'order_direction',
        'default_search_key',
        'default_widget_search_key',
        'scope',
        'details',
    ];
    protected $appends = ['table_name'];

    public function rows()
    {
        return $this->hasMany(Voyager::modelClass('DataRow'))->orderBy('order');
    }

    public function browseRows()
    {
        return $this->rows()->where('browse', 1);
    }

    public function readRows()
    {
        return $this->rows()->where('read', 1);
    }

    public function editRows()
    {
        return $this->rows()->where('edit', 1);
    }

    public function addRows()
    {
        return $this->rows()->where('add', 1);
    }

    public function deleteRows()
    {
        return $this->rows()->where('delete', 1);
    }

    public function lastRow()
    {
        return $this->hasMany(Voyager::modelClass('DataRow'))->orderBy('order', 'DESC')->first();
    }

    public function getTableNameAttribute()
    {
        return  \Str::title(str_replace('_',' ',$this->name));  
    }

    public function setGeneratePermissionsAttribute($value)
    {
        $this->attributes['generate_permissions'] = $value ? 1 : 0;
    }

    public function setServerSideAttribute($value)
    {
        $this->attributes['server_side'] = $value ? 1 : 0;
    }

    public function setWidgetableAttribute($value)
    {
        $this->attributes['widgetable'] = $value ? 1 : 0;
    }

    public function updateDataType($requestData, $throw = false)
    {
        try {
            DB::beginTransaction();

            // Prepare data
            foreach (['generate_permissions', 'server_side','widgetable'] as $field) {
                if (!isset($requestData[$field])) {
                    $requestData[$field] = 0;
                }
            }

            if ($this->fill($requestData)->save()) {
                $fields = $this->fields(
                    (strlen($this->model_name) != 0)
                    ? DB::getTablePrefix().app($this->model_name)->getTable()
                    : DB::getTablePrefix().Arr::get($requestData, 'name')
                );

                $requestData = $this->getRelationships($requestData, $fields);

                foreach ($fields as $field) {
                    $dataRow = $this->rows()->firstOrNew(['field' => $field]);

                    foreach (['browse', 'read', 'edit', 'add', 'delete'] as $check) {
                        $dataRow->{$check} = isset($requestData["field_{$check}_{$field}"]);
                    }

                    $dataRow->required = boolval($requestData['field_required_'.$field]);
                    $dataRow->field = $requestData['field_'.$field];
                    $dataRow->type = $requestData['field_input_type_'.$field];
                    $dataRow->details = json_decode($requestData['field_details_'.$field]);
                    $dataRow->display_name = $requestData['field_display_name_'.$field];
                    $dataRow->order = intval($requestData['field_order_'.$field]);

                    // Prepare Translations and Transform data
                    $translations = (is_bread_translatable($dataRow) && !empty($requestData['field_display_name_'.$field.'_i18n']))
                        ? $dataRow->prepareTranslationsFromArray($field, $requestData)
                        : [];

                    if (!$dataRow->save()) {
                        throw new \Exception(__('voyager::database.field_safe_failed', ['field' => $field]));
                    }

                    // Save translations if applied
                    $dataRow->saveTranslations($translations);
                }

                // Clean data_rows that don't have an associated field
                // TODO: need a way to identify deleted and renamed fields.
                //   maybe warn the user and let him decide to either rename or delete?
                $this->rows()->whereNotIn('field', $fields)->delete();

                // It seems everything was fine. Let's check if we need to generate permissions
                if ($this->generate_permissions) {
                    Voyager::model('Permission')->generateFor($this->name);
                }

                DB::commit();

                return true;
            }
        } catch (\Exception $e) {
            DB::rollBack();

            if ($throw) {
                throw $e;
            }
        }

        return false;
    }

    public function fields($name = null)
    {
        if (is_null($name)) {
            $name = $this->name;
        }

        $fields = SchemaManager::listTableColumnNames($name);

        if ($extraFields = $this->extraFields()) {
            foreach ($extraFields as $field) {
                $fields[] = $field['field'];
            }
        }

        return $fields;
    }

    public function getRelationships($requestData, &$fields)
    {
        if (isset($requestData['relationships'])) {
            $relationships = $requestData['relationships'];
            if (count($relationships) > 0) {
                foreach ($relationships as $index => $relationship) {
                    // Push the relationship on the allowed fields
                    array_push($fields, $relationship);

                    $relationship_column = $requestData['relationship_column_belongs_to_'.$relationship];
                    if ($requestData['relationship_type_'.$relationship] == 'hasOne' || $requestData['relationship_type_'.$relationship] == 'hasMany') {
                        $relationship_column = $requestData['relationship_column_'.$relationship];
                    }

                    // Build the relationship details
                    $relationshipDetails = [
                        'model'       => $requestData['relationship_model_'.$relationship],
                        'table'       => $requestData['relationship_table_'.$relationship],
                        'type'        => $requestData['relationship_type_'.$relationship],
                        'column'      => $relationship_column,
                        'key'         => $requestData['relationship_key_'.$relationship],
                        'label'       => $requestData['relationship_label_'.$relationship],
                        'pivot_table' => $requestData['relationship_pivot_table_'.$relationship],
                        'pivot'       => ($requestData['relationship_type_'.$relationship] == 'belongsToMany') ? '1' : '0',
                        'taggable'    => $requestData['relationship_taggable_'.$relationship] ?? '0',
                    ];

                    $details = json_decode($requestData['field_details_'.$relationship], true);
                    $merge = array_merge($details, $relationshipDetails);
                    $requestData['field_details_'.$relationship] = json_encode($merge);
                }
            }
        }

        return $requestData;
    }

    public function fieldOptions()
    {
        // Get ordered BREAD fields
        // $orderedFields = $this->rows()->pluck('field')->toArray();

        $fieldOptions = SchemaManager::describeTable(
            (strlen($this->model_name) != 0)
            ? DB::getTablePrefix().app($this->model_name)->getTable()
            : DB::getTablePrefix().$this->name
        );
        // dump($_fieldOptions,$orderedFields,'===');
        
        // $fieldOptions = [];
        // $f_size = count($orderedFields);
        // for ($i = 0; $i < $f_size; $i++) {
        //     if(array_key_exists($orderedFields[$i],$_fieldOptions))
        //         $fieldOptions[$orderedFields[$i]] = $_fieldOptions[$orderedFields[$i]];
        // }
        // $fieldOptions = collect($fieldOptions);

        if ($extraFields = $this->extraFields()) {
            foreach ($extraFields as $key => $field) {
                $fieldOptions[$key] =  $field;
            }
        }
        return $fieldOptions;
    }




    public function extraFields()
    {
        if (empty(trim($this->model_name))) {
            return [];
        }

        $model = app($this->model_name);
        if (method_exists($model, 'adminFields')) {
            return $model->adminFields();
        }
    }
    protected function getMetaFieldHolder():string
    {
        return 'details';
    }

    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = json_encode($value);
    }

    public function getDetailsAttribute($value)
    {
        return json_decode(!empty($value) ? $value : '{}');
    }

    public function getOrderColumnAttribute()
    {
        return $this->getMetaField('order_column');
    }

    public function setOrderColumnAttribute($value)
    {
        $this->setMetaField('order_column',$value);
    }

    public function getOrderDisplayColumnAttribute()
    {
        return $this->getMetaField('order_display_column');
    }

    public function setOrderDisplayColumnAttribute($value)
    {
        $this->setMetaField('order_display_column',$value);
    }

    public function getDefaultSearchKeyAttribute()
    {
        return $this->getMetaField('default_search_key');
    }

    public function setDefaultSearchKeyAttribute($value)
    {
        $this->setMetaField('default_search_key',$value);
    }

    public function getDefaultWidgetSearchKeyAttribute()
    {
        return $this->getMetaField('default_widget_search_key');
    }

    public function setDefaultWidgetSearchKeyAttribute($value)
    {
        $this->setMetaField('default_widget_search_key',$value);
    }

    public function getOrderDirectionAttribute()
    {
        return $this->getMetaField('order_direction','desc');
    }

    public function setOrderDirectionAttribute($value)
    {
        $this->setMetaField('order_direction',$value);
    }

    public function getPerPageAttribute()
    {
        return $this->getMetaField('per_page',15);
    }

    public function setPerPageAttribute($value)
    {
        $this->setMetaField('per_page',$value);
    }

    public function getScopeAttribute()
    {
        return $this->getMetaField('scope');
    }

    public function setScopeAttribute($value)
    {
        $this->setMetaField('scope',$value);
    }
    /**
     * checks
     */
    public function isWidgetable()
    {
        return $this->widgetable == true ? true :false;
    }
    /**
     * Scopes
     */
    public function scopeWidgetable($query)
    {
        return $query->where('widgetable',true);
    }
}
