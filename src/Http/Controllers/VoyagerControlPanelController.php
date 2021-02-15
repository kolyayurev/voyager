<?php

namespace TCG\Voyager\Http\Controllers;

use Auth;
use Artisan;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VoyagerControlPanelController  extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard(app('VoyagerGuard'));
    }

    public function controlPanel()
    {
        $this->authorize('browse_control_panel');

        return view('voyager::tools.control_panel');
    }
    public function clearCache()
    {
        try {
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('view:clear');
                Artisan::call('event:clear');
    
                return response()->json([
                    'status' => 'success'
                ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    

}
