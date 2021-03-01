<?php

namespace TCG\Voyager\Models;

use Str;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';

    protected $guarded = [];

    public $timestamps = false;

    public function getHandler()
    {
       return app($this->handler);
    }
}
