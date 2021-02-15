<?php

namespace TCG\Voyager\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

class Permission extends Model
{
    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Voyager::modelClass('Role'));
    }

    public static function generateFor($table_name)
    {
        self::firstOrCreate(['key' => 'browse_'.$table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['key' => 'read_'.$table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['key' => 'edit_'.$table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['key' => 'add_'.$table_name, 'table_name' => $table_name]);
        self::firstOrCreate(['key' => 'delete_'.$table_name, 'table_name' => $table_name]);
    }

    public static function generateForSettings()
    {
        $table_name = 'settings';
        self::generateFor($table_name);
        self::generateSettingsGroup('site');
        self::generateSettingsGroup('admin');
    }
    public static function generateSettingsGroup($group_name)
    {
        self::firstOrCreate(['key' => 'browse_group_'.$group_name.'_settings', 'table_name' => 'settings']);
    }
    public static function checkSettingsGroup($group_name)
    {
       return self::check('browse_group_'.$group_name.'_settings');
    }
    public static function check($key)
    {
       return self::where('key',$key)->exists();
    }

    public static function removeFrom($table_name)
    {
        self::where(['table_name' => $table_name])->delete();
    }
}
