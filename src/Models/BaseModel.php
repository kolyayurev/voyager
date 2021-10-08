<?php

namespace TCG\Voyager\Models;

use Log;
use Date;
use Storage;
use Exception;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * scopes
     */
    public function scopeVisible($query)
    {
        return $query->where('visible',true);
    }

}
