<?php

namespace TCG\Voyager\FormFields;

class CoordinatesHandler extends AbstractHandler
{
    protected $supports = [
        'mysql',
        'pgsql',
    ];

    protected $viewEdit = 'voyager::formfields.coordinates';
    protected $codename = 'coordinates';


}
