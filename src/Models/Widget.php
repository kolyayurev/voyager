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

    public $casts = ['value' => JsonCast::class];

    protected $translatable = ['name'];

    public function getHandler()
    {
       return Voyager::widget($this->handler);
    }
}
