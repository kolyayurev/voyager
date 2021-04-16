<?php

namespace TCG\Voyager\FormFields;

class CoordinatesHandler extends AbstractHandler
{
    protected $supports = [
        'mysql',
        'pgsql',
    ];

    protected $viewEdit = 'voyager::formfields.coordinates.edit';
    protected $viewRead = 'voyager::formfields.coordinates.read';
    protected $viewBrowse = 'voyager::formfields.coordinates.browse';
    protected $codename = 'coordinates';


}
