<?php 

namespace TCG\Voyager\Traits;

trait HasMetaFields
{
    
    protected function buildMetaField($name,$type = "varchar", $nullable = 'YES'):array
    {
        return [
            "name" => $name,
            "type" => $type,
            "null" => $nullable,
            "field" => $name,
            "key" => null,
        ];
    }
    public function adminFields():array{
        return [
            // 'meta_1' => $this->buildMetaField('meta_1'),
        ];
    }

    protected function getMetaFieldHolder():string
    {
        return 'meta_fields';
    }

    public function setMetaFieldsAttribute($value)
    {
        $this->attributes[$this->getMetaFieldHolder()] = json_encode($value,JSON_UNESCAPED_UNICODE);
    }
    public function getMetaFieldsAttribute($value)
    {
        return json_decode(!empty($value) ? $value : '{}');
    }

    public function setMetaField($name,$value)
    {
        $this->attributes[$this->getMetaFieldHolder()] = collect($this->{$this->getMetaFieldHolder()})->merge([$name => $value]);
    }
    public function getMetaField($name,$default = null)
    {
        return $this->{$this->getMetaFieldHolder()}->{$name} ?? $default;
    }
    public function deleteMetaField($name)
    {
        unset($this->{$this->getMetaFieldHolder()}->{$name});
    }
    public function hasMetaField($name)
    {
        return property_exists($this->{$this->getMetaFieldHolder()},$name);
    }
    
}