<?php

namespace TCG\Voyager\Models;

use Str;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;

class Permission extends Model
{
    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Voyager::modelClass('Role'));
    }

    public static function generate($key,$table_name)
    {
        self::firstOrCreate(['key' => $key, 'table_name' => $table_name]);
    }

    public static function generateFor($table_name)
    {
        self::generate('browse_'.$table_name, $table_name);
        self::generate('read_'.$table_name, $table_name);
        self::generate('edit_'.$table_name, $table_name);
        self::generate('add_'.$table_name, $table_name);
        self::generate('delete_'.$table_name, $table_name);
    }

    public static function generateForSettings()
    {
        $table_name = 'settings';
        self::generateFor($table_name);
        self::generateSettingsGroup('site');
        self::generateSettingsGroup('admin');
    }
    public static function generateForWidgets()
    {
        $table_name = 'widgets';
        self::generateFor($table_name);
        self::generate('moderate_'.$table_name, $table_name);
    }
    public static function generateSettingsGroup($group_name)
    {
        self::generate('browse_group_'.Str::lower($group_name).'_settings','settings');
    }
    public static function checkSettingsGroup($group_name)
    {
       return self::check('browse_group_'.Str::lower($group_name).'_settings');
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
