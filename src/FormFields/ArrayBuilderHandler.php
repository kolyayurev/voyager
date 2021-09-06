<?php

namespace TCG\Voyager\FormFields;

/**
 * Model must cast field to JsonCast
 */

class ArrayBuilderHandler extends AbstractHandler
{
    protected $viewEdit = 'voyager::formfields.array_builder.edit';
    protected $viewRead = 'voyager::formfields.array_builder.read';
    protected $viewBrowse = 'voyager::formfields.array_builder.browse';
    protected $codename = 'array_builder';
}