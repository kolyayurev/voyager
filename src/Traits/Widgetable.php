<?php

namespace TCG\Voyager\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Translation;
use TCG\Voyager\Translator;

trait Widgetable
{
    /**
     * Check if this model can translate.
     *
     * @return bool
     */
    public function widgetable()
    {
        return true;
    }

    /**
     * Load widgets relation.
     *
     * @return mixed
     */
    public function widgets()
    {
        return $this->hasMany(Voyager::model('Widget'), 'foreign_key', $this->getKeyName())
            ->where('table_name', $this->getTable());
    }

    /**
     * This scope eager loads the widgets.
     * We can use this as a shortcut to improve performance in our application.
     *
     * @param Builder     $query
     */
    public function scopeWithWidgets(Builder $query)
    {
        $query->with(['widgets']);
    }

  
}
