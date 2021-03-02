<?php

namespace TCG\Voyager\Models;

use Str;

use TCG\Voyager\Casts\JsonCast;

use Illuminate\Database\Eloquent\Model;

class Widget extends Model
{
    protected $table = 'widgets';

    protected $guarded = [];

    public $timestamps = false;

    public $casts = ['value' => JsonCast::class];

    public function getHandler()
    {
       return app($this->handler);
    }
}
