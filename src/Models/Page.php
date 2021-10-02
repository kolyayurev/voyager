<?php

namespace TCG\Voyager\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use TCG\Voyager\Traits\Translatable;
use TCG\Voyager\Traits\Widgetable;
use TCG\Voyager\Traits\HasSeo;
use TCG\Voyager\Traits\HasMedia;
use TCG\Voyager\Traits\HasMetaFields;

class Page extends Model
{
    use HasSeo,HasMedia,HasMetaFields,Translatable,Widgetable;

    protected $translatable = ['title','excerpt', 'body'];

    /**
     * Statuses.
     */
    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_INACTIVE = 'INACTIVE';

    /**
     * List of statuses.
     *
     * @var array
     */
    public static $statuses = [self::STATUS_ACTIVE, self::STATUS_INACTIVE];

    protected $guarded = [];

    public function save(array $options = [])
    {
        // If no author has been assigned, assign the current user's id as the author of the post
        if (!$this->author_id && Auth::user()) {
            $this->author_id = Auth::user()->getKey();
        }

        return parent::save();
    }
    /**
     * Override
     */
    public function getH1()
    {
        if(Voyager::translatable($this))
            return $this->getTranslatedAttribute('h1')??$this->getTranslatedAttribute('title')??'';
        else
            return $this->h1??$this->title??'';
    }
    /**
     * Checks
     */
    public function isHome()
    {
        return $this->slug == 'home' ? true : false;
    }
    /**
     * scopes
     */
    public function scopeVisible($query)
    {
        return $query->where('visible',true);
    }
    /**
     * Scope a query to only include active pages.
     *
     * @param  $query  \Illuminate\Database\Eloquent\Builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', static::STATUS_ACTIVE);
    }
}
