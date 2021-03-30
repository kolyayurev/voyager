<?php

namespace TCG\Voyager\Models;

use Str;
use Voyager;

use TCG\Voyager\Casts\JsonCast;
use TCG\Voyager\Traits\Translatable;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    use Translatable;

    protected $table = 'widgets';

    protected $guarded = [];

    public $timestamps = false;

    protected $fillable = ['name','slug','handler','description','details','table_name', 'foreign_key', 'value'];

    protected $translatable = ['name','value'];

    public function getHandler()
    {
       return Voyager::widgetHandler($this->handler);
    }

    public function setDetailsAttribute($value)
    {
        $this->attributes['details'] = !is_string($value)?json_encode($value,JSON_UNESCAPED_UNICODE):$value;
    }

    public function getDetails()
    {
        return json_decode(!empty($this->details) ? $this->details : '{}');
    }
}
