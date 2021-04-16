<?php

namespace TCG\Voyager\FormFields;

class PasswordHandler extends AbstractHandler
{
    protected $viewEdit = 'voyager::formfields.password.edit';
    protected $viewRead = 'voyager::formfields.password.read';
    protected $viewBrowse = 'voyager::formfields.password.browse';
    protected $codename = 'password';

}
