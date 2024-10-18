<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Cat;
use App\User;
use App\Project;
use App\Award;
use App\Badge;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
   */
  public function index()
  {
    $user = Auth::user();
    $projects = Project::where('stat', '=', '3')
      ->whereHas('user', function ($query) use ($user) {
        $query->where('id', '=', $user->id);
      })->get();
    $awards = DB::table('awards')
      ->join('projects', 'projects.id', '=', 'awards.project_id')
      ->join('badges', 'badges.id', '=', 'awards.badge_id')
      ->where('projects.user_id', $user->id)
      ->get();
    return view('home', compact('projects', 'awards', 'user'));
  }

  public function change()

  {
    $user = Auth::user();
    return view('user-change')->with(['user' => $user]);
  }

  public function add()

  {

    $user = Auth::user();
    return view('add-teilnahmebedingung')->with(['user' => $user]);
  }

  public function insert()

  {
    $user = Auth::user();
    return view('project-insert')->with(['user' => $user]);
  }

  public function show()

  {
    $user = Auth::user();
    return view('project-show')->with(['user' => $user]);
  }

  public function beschreibung()

  {
    $user = Auth::user();
    $projects = Project::where('stat', '=', '3')
      ->whereHas('user', function ($query) use ($user) {
        $query->where('id', '=', $user->id);
      })->get();
    // return view('project-show', compact('projects', 'user','cat'));

    // dd($projects);
    return view('beschreibung', compact('projects', 'user'));
  }
}
