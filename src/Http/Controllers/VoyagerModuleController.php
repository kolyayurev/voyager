<?php

namespace TCG\Voyager\Http\Controllers;

use Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Models\Permission;


class VoyagerModuleController extends Controller
{
    public function index()
    {
        // Check permission
        $this->authorize('browse_modules');

        return Voyager::view('voyager::modules.index');
    }


}
