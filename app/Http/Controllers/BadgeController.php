<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cat;
use App\User;
use Session;
use DB;
use App\Badge;


class BadgeController extends Controller
{
    public function showBadges()
    {

        $user = Auth::user();
        $badges = Badge::all();
        $cats = Cat::all();
        return view('showbadges', compact('badges', 'user', 'cats'));
    }
    public function myBadges()
    {
        $user = Auth::user();
        $cats = Cat::all();
        $badges = DB::table('projects')
        ->where('user_id', Auth::id())
        ->join('awards', 'awards.project_id', '=', 'projects.id')
        ->join('badges', 'badges.id', '=', 'awards.badge_id')
        ->select('projects.*','awards.*', 'badges.*')
        ->get();
        // dd($counts);
        return view('mybadges', compact('badges', 'user', 'cats'));
    }
    public function createBadge()
    {
        $user = Auth::user();
        $cats = Cat::all();
        return view('createbadge', compact('cats', 'user'));
    }
    public function saveBadge(Request $request)
    {
        $badge = new Badge;
        $badge->cat_id = $request->cat_id;
        $badge->title = $request->title;
        $badge->year = $request->year;
        if ($request->hasFile('image')) {
            // $original_filename = $request->file('image')->getClientOriginalName();

            $path = $request->file('image')->store('public/img');
            $badge->image = str_replace('public/', '', $path);
        }
        $badge->save();
        Session::flash('alert-success', 'Badge Data Has Been inserted');
        return redirect('show-badges');
    }
    public function showData($id)
    {
        $user = Auth::user();
        $cats = Cat::all();
        $badges = Badge::find($id);
        return view('editbadge', compact('badges', 'user', 'cats'));
    }
    public function editBadge(Request $request)
    {
        $badge = Badge::find($request->id);
        $badge->cat_id = $request->cat_id;
        $badge->title = $request->title;
        $badge->year = $request->year;
        if ($request->hasFile('image')) {
            // $original_filename = $request->file('image')->getClientOriginalName();
            $path = $request->file('image')->store('public/img');
            $badge->image = str_replace('public/', '', $path);
        }
        $badge->save();
        Session::flash('alert-success', 'Badge Data Has Been updated');
        return redirect('show-badges');
    }
    public function deleteBadge($id)
    {
        $badge = Badge::find($id);
        $badge->delete();
        Session::flash('alert-success', 'Badge Data Has Been deleted');
        return redirect('show-badges');
    }
}
