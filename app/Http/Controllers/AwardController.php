<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Project;
use App\User;
use Session;
use App\Badge;

use App\Award;


class AwardController extends Controller
{
    public function createAward()
    {
        $user = Auth::user();
        $badges = Badge::all();
        $projects = Project::all();
        return view('createaward', compact('badges', 'user', 'projects'));
    }
    public function saveAward(Request $request)
    {
        $award = new Award;
        $award->project_id = $request->project_id;
        $award->badge_id = $request->badge_id;
        $award->save();
        Session::flash('alert-success', 'Award Data Has Been inserted');
        return redirect('show-awards');
    }
    public function showAwards()
    {

        $user = Auth::user();
        $users = User::all();
        $badges = Badge::all();
        $awards = Award::all();
        $projects = Project::all();
        return view('showawards', compact('projects', 'badges', 'user', 'users', 'awards'));
    }
    public function showData($id)
    {
        $user = Auth::user();
        $badges = Badge::all();
        $awards = Award::find($id);
        $projects = Project::all();
        return view('editaward', compact('projects', 'badges', 'user', 'awards'));
    }
    public function editAward(Request $request)
    {
        $award = Award::find($request->id);
        $award->project_id = $request->project_id;
        $award->badge_id = $request->badge_id;
        $award->save();
        Session::flash('alert-success', 'Award Data Has Been updated');
        return redirect('show-awards');
    }
    public function deleteAward($id)
    {
        $award = Award::find($id);
        $award->delete();
        Session::flash('alert-success', 'Award Data Has Been deleted');
        return redirect('show-awards');
    }
}
