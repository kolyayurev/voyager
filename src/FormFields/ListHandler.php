<?php

namespace TCG\Voyager\FormFields;

/**
 * Model must cast field to JsonCast
 */

class ListHandler extends AbstractHandler
{
    protected $viewEdit = 'voyager::formfields.list.edit';
    protected $viewRead = 'voyager::formfields.list.read';
    protected $viewBrowse = 'voyager::formfields.list.browse';
    protected $codename = 'list';

}