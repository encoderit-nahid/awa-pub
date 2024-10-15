<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Project;
use App\User;
use DB;

use Session;

class EmailCronController extends Controller
{
    //
    public function emailCron()
    {
        $user = Auth::user();
        $projectcount = DB::table('projects')
            ->where('user_id', $user->id)
            ->get();
        $rowcount = $projectcount->count();
        if ($rowcount == 0) {
            Session::flash('alert-success', 'No project Has Been deleted');
            return view('emailcron');
        }
    }
}
