<?php

/**
 * @source https://github.com/orangehill/iseed
 */

namespace TCG\Voyager\Helpers\Iseed;

use Voyager;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Config;

class MakeBreadIseed extends BaseIseed
{

    public function __construct(Filesystem $filesystem = null)
    {
        parent::__construct($filesystem);
    }

    /**
     * Generates a seed file.
     * @param  string   $table
     * @return bool
     */
    public function generateSeed($table)
    {
       
        // Generate class name
        $className = $this->generateClassName($table);

        // Get template for a seed file contents
        $stub = $this->readStubFile($this->getStubPath() . '/bread.stub');

        // Get a app/database/seeds path
        $seedsPath = $this->getPath($className);

        // Get a populated stub file
        $seedContent = $this->populateStub(
            $className,
            $stub,
            $table,
        );

        // Save a populated stub
        $this->files->put($seedsPath, $seedContent);

        return true;
    }

    /**
     * Create the full path name to the seed file.
     * @param  string  $name
     * @return string
     */
    public function getPath($name)
    {
        return base_path() . '/database/seeds/' . $name . '.php';
    }

    /**
     * Get the Data
     * @param  string $table
     * @return Array
     */
    public function getData($table)
    {
        $dataType = Voyager::model('DataType')->where('name',$table)->first();
        $dataRows = $dataType->rows;
        $translations =  $dataType->translations;
        foreach ($dataRows as $dataRow) {
            $translations = $translations->merge($dataRow->translations);
        }

        return [$dataType,$dataRows,$translations];
    }

    /**
     * Generates a seed class name (also used as a filename)
     * @param  string  $table
     * @return string
     */
    public function generateClassName($table)
    {
        $tableString = '';
        $tableName = explode('_', $table);
        foreach ($tableName as $tableNameExploded) {
            $tableString .= ucfirst($tableNameExploded);
        }
        return 'VoyagerBread' . ucfirst($tableString) .  'Seeder';
    }

    /**
     * Populate the place-holders in the seed stub.
     * @param  string   $class
     * @param  string   $stub
     * @param  string   $table
     * @return string
     */
    public function populateStub($class, $stub, $table)
    {
        // Get the data
        [$dataType,$dataRows,$translations] = $this->getData($table);

        dd($dataType->hidden()->toArray());

        $stub = str_replace('{{class}}', $class, $stub);

        $stub = str_replace('{{table}}', $table, $stub);

        $inserts = '';
        
        foreach ($chunks as $chunk) {
            $this->addNewLines($inserts);
            $this->addIndent($inserts, 2);
            $inserts .= var_dump([
                'slug'                  => 'roles',
                'display_name_singular' => __('voyager::seeders.data_types.role.singular'),
                'display_name_plural'   => __('voyager::seeders.data_types.role.plural'),
                'icon'                  => 'voyager-lock',
                'model_name'            => 'TCG\\Voyager\\Models\\Role',
                'controller'            => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'generate_permissions'  => 1,
                'description'           => '',
            ]);
        }

        $stub = str_replace('{{data_type}}', $inserts, $stub);

        // $dataRow = $this->dataRow($dataType, 'id');
        // if (!$dataRow->exists) {
        //     $dataRow->fill([
        //         'type'         => 'number',
        //         'display_name' => __('voyager::seeders.data_rows.id'),
        //         'required'     => 1,
        //         'browse'       => 0,
        //         'read'         => 0,
        //         'edit'         => 0,
        //         'add'          => 0,
        //         'delete'       => 0,
        //         'order'        => 1,
        //     ])->save();
        // }

        // Translation::firstOrNew(array_merge($keys, [
        //     'locale' => $lang,
        // ]));


        

        return $stub;
    }










}
