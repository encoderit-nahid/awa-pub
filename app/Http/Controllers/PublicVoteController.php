<?php

namespace App\Http\Controllers;

use App\Cat;
use App\User;
use App\Count;
use App\Project;
use App\PublicVoteCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PublicVoteController extends Controller
{
    public function index() {
        return view('vote');
    }
    
    public function contact() {
        return view('contact');
    }

    public function voteScore() {
        $users = User::all();
        $counts = DB::table('public_vote_counts')
        ->join('projects', 'projects.id', '=', 'public_vote_counts.project_id')
        ->select('projects.*', 'public_vote_counts.*', DB::raw('sum(counts) as score'), DB::raw('count(public_vote_counts.user_id) as total_user'))
        ->groupBy(['project_id', 'projects.user_id'])
        ->orderBy('score', 'desc')
        ->get();
        // dd($counts);
        return view('showvotescore', compact('counts', 'users'));
    }

    public function vote(Request $request, $cat_id = null) {
        // Styled Shoot Team = 26

        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->pluck('name', 'id');
        $pids = PublicVoteCount::WHERE('user_id', $user->id)->pluck('project_id');

        $projects = Project::where('projects.cat_id', '=', '26')
            ->whereDoesntHave('publicVoteCount', function($queries) use ($user) {
                $queries->where('user_id', '=', $user->id);
            })
            ->where('projects.stat', '=', '2')
            ->where('projects.jury', '=', '1')
            ->where('projects.is_public_votable', '=', '1')
            ->select('projects.*')
            ->withImages()
            ->inRandomOrder()
            ->get();

        $do_work = 0;

        return view('votes', compact('projects', 'user', 'all_cats', 'cat_id', 'do_work'));
    }

    public function PublicProjectRated(Request $request) {

        $user = Auth::user();

        PublicVoteCount::updateOrCreate([
          'project_id' => $request->project_id,
          'user_id' => $user->id
        ],
        [
          'counts' => $request->counts,
          'created_at' => \Carbon\Carbon::now()->toDateTimeString(),
          'updated_at' => \Carbon\Carbon::now()->toDateTimeString(),
        ]);

        return redirect('/votes');
    }
}