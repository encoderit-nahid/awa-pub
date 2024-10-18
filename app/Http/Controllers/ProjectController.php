<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
use PDF;
use File;
use Hash;
use App\Cat;
use Session;
use Storage;
use App\User;
use App\Count;
use App\Image;
use App\Reject;
use App\Invoice;
use App\Project;
use Image as Im;
use App\OperateMenu;
use App\RolleFiveCount;
use Jorenvh\Share\Share;
use App\Mail\SendInvoice;
use App\AwardUploadByUser;
use App\Mail\AddProjectMail;

// use Youtube;
use Illuminate\Http\Request;
use App\FirstRoundEvaluation;
use App\Mail\AcceptingProject;
use App\Mail\RejectingProject;
use Dcblogdev\Dropbox\Dropbox;
use App\JuryCategoryPermission;
use App\Mail\ChangeProjectMail;
use App\Mail\DeleteProjectMail;

use Vimeo\Laravel\Facades\Vimeo;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Filesystem\Filesystem;
use Spatie\Activitylog\Models\Activity;
use Dcblogdev\Dropbox\Models\DropboxToken;


class ProjectController extends Controller
{
    protected $data_per_page = 10;
    private $photos_path;

    public function __construct(Request $request)
    {
        $this->middleware('auth', ['except' => ['registerNewUser']]);

        //  $this->photos_path = '../award/images';
        //$this->photos_path = '../images';
        $this->photos_path = public_path('/images');

        if (isset($request->per_page)) {
            $this->data_per_page = $request->per_page;
        }
    }

    public function first_connect_to_dropbox(Request $request)
    {
        $dropbox = new Dropbox();
        $token = $dropbox->getAccessToken();
        // $dropbox->getAccessToken();
        //dd('Dropbox Connected');
        // dd($token);
    }

    public function activity(Request $request)
    {

        $is_paginate = true;
        $user = Auth::user();
        $users = User::pluck('email', 'id');

        $keyword = $request->get('search');
        if (!empty($keyword)) {
            $is_paginate = false;
            if ($request->get('search_type') == 'search_by_subject_id') {
                $activities = Activity::orderBy('id', "desc")->where('subject_id', $keyword)->get();
            } else {
                $activities = Activity::orderBy('id', "desc")->where('causer_id', $keyword)->get();
            }
        } else {
            $activities = Activity::orderBy('id', "desc")->paginate(10);
        }

        return view('activity', compact('user', 'activities', 'users', 'is_paginate'));
    }

    public function SingleProject($id)
    {
        $user = Auth::user();
        $projects = Project::where('id', $id)
            ->with('images')
            ->paginate(5);

        $project = Project::where('id', $id)->first();
        $current_user = User::where("id", $project->user_id)->first();


        $do_work = 0;
        return view('project-show-single', compact('projects', 'user', 'do_work', 'current_user'));
    }


    public function download_certificate()
    {

        $user = Auth::user();

        $firmas = User::pluck('firma', 'id')->toArray();
        $categoris = Project::where('user_id', $user->id)->pluck('cat_id')->toArray();
        $cat_id_for_current_user = [];
        foreach ($categoris as $key => $val) {
            if (!in_array($val, $cat_id_for_current_user)) {
                array_push($cat_id_for_current_user, $val);
            }
        }

        $position_per_cat = [];

        foreach ($cat_id_for_current_user as $cat_key => $cat_value) {
            $vote_count = [];
            foreach ($firmas as $key => $value) {
                $project_id_array = Project::where('user_id', $key)->where('cat_id', $cat_value)->pluck('id')->toArray();
                $counts = Count::whereIn('project_id', $project_id_array)->sum('counts');
                $vote_count[$key] = $counts;
            }
            arsort($vote_count);
            $position = array_search($user->id, array_keys($vote_count)) + 1;
            $position_per_cat[$cat_value] = $position;
        }
        $all_cats = Project::where('user_id', $user->id)->pluck('name', 'cat_id')->toArray();
        return view('certificate-list')->with(['user' => $user, 'firma' => $user->firma, 'all_cats' => $all_cats, 'position_per_cat' => $position_per_cat]);
        // dd($firmas);
        // $pdf = PDF::loadView('pdf.certificate');
        // return $pdf->download('Certificate.pdf');
        // return view('pdf.certificate');

    }


    public function download_certificate_pdf($rank, $category)
    {
        $user = Auth::user();
        $firma = $user->firma;
        $pdf = PDF::loadView('pdf.certificate', compact('user', 'rank', 'category', 'firma'));
        return $pdf->download('Certificate.pdf');
        // return view('pdf.certificate');

    }

    public function edit($id)
    {
        $user = Auth::user();
        $project = Project::where('id', $id)->first();
        $current_user = User::where("id", $project->user_id)->first();
        return view('project-edit', compact('project', 'user', 'current_user'));
    }

    public function TopFive()
    {
        $user = Auth::user();

        $cats = Cat::pluck('name', 'id');
        $projects = Project::pluck('name', 'id');
        $point = array();
        foreach ($cats as $key => $value) {
            $projects_under_this_cat = Project::where('cat_id', $key)->pluck('id');
            // dd($projects_under_this_cat);
            // $counts = Count::whereIn('project_id', $projects_under_this_cat)->get();
            $counts = DB::table('counts')
                ->whereIn('project_id', $projects_under_this_cat)
                ->join('projects', 'projects.id', '=', 'counts.project_id')
                ->join('users', 'users.id', '=', 'projects.user_id')
                ->select('projects.*', 'users.*', 'counts.*')
                ->get();

            $rolleFiveCounts = DB::table('rolle_five_counts')
                ->whereIn('project_id', $projects_under_this_cat)
                ->join('projects', 'projects.id', '=', 'rolle_five_counts.project_id')
                ->join('users', 'users.id', '=', 'projects.user_id')
                ->select('projects.*', 'users.*', 'rolle_five_counts.*')
                ->get();

            $counts = $counts->merge($rolleFiveCounts);

            $point[$value] = array();
            foreach ($counts as $count) {
                if (array_key_exists($count->project_id, $point[$value])) {
                    $point[$value][$count->project_id] = $point[$value][$count->project_id] + $count->counts;
                } else {
                    $point[$value][$count->project_id] = $count->counts;
                }
                $point['p_name'][$count->project_id] = $count->projektname;
                $point['category'][$count->project_id] = $count->cat_name;
                $point['company'][$count->project_id] = $count->firma;
                $point['first_name'][$count->project_id] = $count->vorname;
                $point['last_name'][$count->project_id] = $count->name;
                $point['bundesland'][$count->project_id] = $count->bundesland;
                $point['email'][$count->project_id] = $count->email;
            }
            arsort($point[$value]);
            $point[$value] = array_slice($point[$value], 0, 10, true);
            // dd($counts);
        }
        return view('project-top-five', get_defined_vars())->with(['user' => $user, 'cats' => $cats, 'point' => $point, 'projects' => $projects]);
    }


    public function registerNewUser(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users',
            'name' => 'required',
            'vorname' => 'required',
            'firma' => 'required',
            'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
            'anr' => 'required',
            // 'agb'                   => 'required',
            'datenschutz' => 'required',
            'teilnahmebedingung' => 'required'
        ]);

        if ($request->input('newsletter') == 1) {
            $news_later = 1;
        } else {
            $news_later = 0;
        }

        $user = User::create([
            'name' => $request->input('name'), 'email' => $request->input('email'), 'password' => bcrypt($request->input('password')), 'firma' => $request->input('firma'), 'vorname' => $request->input('vorname'), 'anr' => $request->input('anr'), 'agb' => $request->input('agb'), 'newsletter' => $news_later,
            'datenschutz' => $request->input('datenschutz'), 'teilnahmebedingung' => $request->input('teilnahmebedingung'), 'voucher' => $request->input('voucher')
        ]);
        if ($user) {
            if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                return redirect(url('/home'));
            }
        }

        return back();
    }


    // Admin: Insert new Project for user


    public function SelectUserView()
    {

        $user = Auth::user();

        $users = User::orderBy('name', 'asc')
            ->where('rolle', '=', '0')
            ->orWhere('rolle', '=', '5')
            ->get();

        return view('selectuser', compact('user', 'users'));
    }

    public function UserAddProject(Request $request)
    {


        $id = $request->input("id");

        $user = $id;

        //dd($user);

        $cats = Cat::where('stat', '!=', '1')
            ->orderBy('name', 'asc')->get();

        return view('project-insert', get_defined_vars());
    }


    public function insertProjectStepOne(Request $data)
    {


        $user = Auth::user();

        $cat_id = $data->input("cat_id");

        $cats = Cat::where('id', '=', $cat_id)->first();

        $random_name = substr(str_shuffle("0123456789ABCDF"), 0, 6);
        $random_name = $cat_id . '_' . $random_name;

        return view('project-insert-two', get_defined_vars())->with(['user' => $user]);
    }

    public function projectCategoryShow(Request $data)
    {

        $user = Auth::user();

        $cat_id = $data->input("cat_id");

        $cats = Cat::where('id', '=', $cat_id)->first();

        //dd($cats);

        $projects = Project::where('stat', '!=', '1')
            ->where('cat_id', '=', $cat_id)
            ->paginate(1000);
        $user_array = [];
        foreach ($projects as $project) {
            array_push($user_array, $project->user_id);
        }
        // Firstname, Lastname, Company, E-Mail
        $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();
        // dd($users);


        return view('project-category-show', compact('projects', 'user', 'users', 'cats'));
    }


    public function CoE()
    {

        $user = Auth::user();

        $users = User::orderBy('firma', 'asc')
            ->where('rolle', '=', '0')
            ->get();

        return view('coe', compact('user', 'users'));
    }

    public function addToCoE(Request $data)
    {

        $user_id = $data['id'];

        DB::table('users')
            ->where('id', $user_id)
            ->update(['rolle' => 5]);

        $user = Auth::user();

        $users = User::orderBy('firma', 'asc')
            ->where('rolle', '=', '0')
            ->get();

        return view('coe', compact('user', 'users'));
    }

    public function CoEShow()
    {


        $user = Auth::user();

        $users = User::orderBy('name', 'asc')
            ->where('rolle', '=', '5')
            ->get();

        return view('coeshow', compact('user', 'users'));
    }


    public function VoteCoe(Request $request)
    {

        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->pluck('name', 'id');

        // $cat_id = "21";
        $cat_id = "21";

        // $pids = Count::where('user_id', $user->id)->pluck('project_id');
        // $uids = Count::pluck('user_id');
        $pids = RolleFiveCount::where('user_id', $user->id)->pluck('project_id');
        $uids = RolleFiveCount::pluck('user_id');


        $projects = Project::with('images')
            ->whereNotIn('id', $pids)
            ->where('cat_id', $cat_id)
            ->where('stat', '=', '2')
            ->where('jury', '=', '1')
            ->paginate(5);


        //->where('user_id', '!=', $user->id)


        if ($request->ajax()) {


            $count = Project::with('images')
                ->whereNotIn('id', $pids)
                ->where('cat_id', $cat_id)
                ->where('user_id', '!=', $user->id)
                ->where('stat', '=', '2')
                ->where('jury', '=', '1')
                ->count();


            if (ceil($count / 5) == $request->input("page")) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }


            return [
                'projects' => view('ajax-load')->with(compact('projects', 'user', 'all_cats', 'cat_id', 'do_work'))->render(),
                'next_page' => $projects->nextPageUrl()
            ];
        } else {


            $count = Project::with('images')
                ->whereNotIn('id', $pids)
                ->where('cat_id', $cat_id)
                ->where('user_id', '!=', $user->id)
                ->where('stat', '=', '2')
                ->where('jury', '=', '1')
                ->count();


            if (ceil($count) <= 5) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
        }

        // dd($count);

        return view('project-show-coe', compact('projects', 'user', 'all_cats', 'cat_id', 'do_work'));
    }


    /*    public function invoice(Request $data) {

          $user = Auth::user();

          $invoices = Invoice::latest()->paginate(20);

          $users_email = User::pluck('email', 'id')->all();
          $users_name = User::pluck('name', 'id')->all();

          return view('invoice', get_defined_vars())->with(['user' => $user, 'invoices' => $invoices, 'users_email' => $users_email, 'users_name' => $users_name ]);

      }*/

    public function invoice(Request $data)
    {
        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->get();

        $user_id_array = Project::SELECT('user_id')->WHERE("stat", '!=', '2')->WHERE("inv", true)->pluck('user_id')->toArray();
        $user_id_array = array_unique($user_id_array);

        if ((isset($data->search)) and ($data->search != "")) {
            $users = User::where('name', 'LIKE', '%' . $data->search . '%')->orWhere('email', 'LIKE', '%' . $data->search . '%')->paginate(15);
        } elseif ((isset($data->search_category)) and ($data->search_category != "")) {
            $user_id_array = Project::SELECT('user_id')->where('cat_id', $data->search_category)->WHERE("stat", '!=', '2')->WHERE("inv", true)->pluck('user_id')->toArray();
            $user_id_array = array_unique($user_id_array);
            $users = User::whereIn('id', $user_id_array)->paginate(15);
        } else {
            $users = User::whereIn('id', $user_id_array)->paginate(15);
        }

        return view('invoice', get_defined_vars())->with(['user' => $user, 'users' => $users, 'all_cats' => $all_cats]);
    }

    public function catInvoice(Request $data)
    {
        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->get();

        if ((isset($data->search_category)) and ($data->search_category != "")) {
            $cats = Cat::where('id', $data->search_category)->get();
        } else {
            $cats = Cat::all();
        }

        return view('invoice-cat', get_defined_vars())->with(['user' => $user, 'cats' => $cats, 'all_cats' => $all_cats]);
    }


    public function change_user_status(Request $request)
    {
        $keyword = $request->get('search');
        if (!empty($keyword)) {
            $users = User::where('name', 'LIKE', '%' . $request->search . '%')->orwhere('email', 'LIKE', '%' . $request->search . '%')->orwhere('firma', 'LIKE', '%' . $request->search . '%')->paginate(15);
        } else {
            $users = User::paginate(15);
        }
        $user = Auth::user();
        return view('change-user-status', get_defined_vars())->with(['user' => $user, 'users' => $users]);
    }

    // public function change_user_status_new(Request $request) {
    //   $keyword = $request->get('search');
    //   if (!empty($keyword)) {
    //     $users = User::where('name', 'LIKE', '%'.$request->search.'%')->orwhere('email', 'LIKE', '%'.$request->search.'%')->orwhere('firma', 'LIKE', '%'.$request->search.'%')->paginate(15);
    //   }else{
    //     $users = User::paginate(15);
    //   }
    //   $user  = Auth::user();
    //   return view('change-user-status-new', get_defined_vars())->with(['user' => $user, 'users' => $users ]);
    // }

    public function status_change_dd(Request $request)
    {

        //return $request->all();
        $user = User::find($request->user_id);
        if ($request->columnName == 'rolle') {
            $user->rolle = $request->columnValue;
        } elseif ($request->columnName == 'voting') {
            $user->voting = $request->columnValue;
        } elseif ($request->columnName == 'insert') {
            $user->insert = $request->columnValue;
        } elseif ($request->columnName == 'is_upload_award') {
            $user->is_upload_award = $request->columnValue;
        } elseif ($request->columnName == 'rating_visible') {
            $user->rating_visible = $request->columnValue;
        }
        $user->save();
        return response()->json(array('msg' => 'Success'), 200);
    }

    public function user_delete($id)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $user = User::find($id);
        $user->firstRoundEvaluation()->delete();
        $user->projects()->delete();
        $user->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return redirect()->route('change-user-status');
    }


    public function change_status($type, $user_id)
    {

        $user = User::find($user_id);

        if ($type == 'excellence') {
            $user->rolle = '5';
        } elseif ($type == 'voting') {
            $user->rolle = '2';
        } elseif ($type == 'insert') {
            $user->rolle = '9';
        } elseif ($type == 'certificate') {
            $user->rolle = '1';
        } elseif ($type == 'is_upload_award') {
            $user->is_upload_award = !$user->is_upload_award;
        } elseif ($type == 'rating_visible') {
            $user->rating_visible = !$user->rating_visible;
        }
        // $user->$type  = !$user->$type;
        $user->save();
        return redirect()->back();
    }


    public function my_pdf_download()
    {

        $user = Auth::user();

        $projects = Project::where('user_id', $user->id)->where('stat', '2')->get();
        $project_ids = "";

        foreach ($projects as $project) {
            $project_ids .= $project->id . ',';
        }
        $project_ids = rtrim($project_ids, ',');
        $date = date("Y-m-d");
        // $year = date("Y");
        //ändern ab 1.1.2020 - untere Zeile auskommentieren
        $year = "2022";

        $count = Invoice::where('project_ids', $project_ids)->where('user_id', $user->id)->count();
        if ($count == 0) {
            $invoice = Invoice::create(['user_id' => $user->id, 'project_ids' => $project_ids, 'date' => $date]);
        } else {
            $invoice = Invoice::where('project_ids', $project_ids)->where('user_id', $user->id)->first();
        }


        $pdf = PDF::loadView('pdf.invoice', compact('projects', 'user', 'date', 'invoice', 'year'));
        return $pdf->download('Invoice.pdf');
    }

    public function pdf_download($id)
    {


        $user = User::where('id', $id)->first();

        $projects = Project::where('user_id', $id)->WHERE("stat", "!=", '1')->get();
        // $projects = Project::where(function ($query) {
        //   $query->where("stat", '0')
        //         ->orWhere("stat", '2');
        //   })->where('user_id', $id)->get();

        $project_ids = "";

        foreach ($projects as $project) {
            $project_ids .= $project->id . ',';
        }
        $project_ids = rtrim($project_ids, ',');
        $date = date("Y-m-d");
        // $year = date("Y");
        $year = "2025";

        $count = Invoice::where('project_ids', $project_ids)->where('user_id', $id)->count();
        if ($count == 0) {
            $invoice = Invoice::create(['user_id' => $id, 'project_ids' => $project_ids, 'date' => $date]);
        } else {
            $invoice = Invoice::where('project_ids', $project_ids)->where('user_id', $id)->first();
        }

        $invoice_id = $invoice->id;
        $print_invoice = "AWA-" . $year . "-" . $invoice_id;


        $pdf = PDF::loadView('pdf.invoice', compact('projects', 'user', 'date', 'invoice', 'year'));
        return $pdf->download($print_invoice . '.pdf');
    }

    public function pdf_mail($id)
    {
        $user = User::where('id', $id)->first();

        $projects = Project::where('user_id', $id)->WHERE("stat", '!=', '1')->get();
        $project_ids = "";

        foreach ($projects as $project) {
            $project_ids .= $project->id . ',';
        }
        $project_ids = rtrim($project_ids, ',');
        $date = date("Y-m-d");
        // $year = date("Y");
        $year = Carbon::now()->addYear()->year;

        $count = Invoice::where('project_ids', $project_ids)->where('user_id', $id)->count();
        if ($count == 0) {
            $invoice = Invoice::create(['user_id' => $id, 'project_ids' => $project_ids, 'date' => $date]);
        } else {
            $invoice = Invoice::where('project_ids', $project_ids)->where('user_id', $id)->first();
        }

        $invoice_id = $invoice->id;
        $print_invoice = "AWA-" . $year . "-" . $invoice_id;

        // $files =   Storage::allFiles('public/bills');
        // Storage::delete($files);

        // Storage::delete('public/bills/AWA-2025-3.pdf');

        // $pdf = PDF::loadView('pdf.invoice', compact('projects', 'user', 'date', 'invoice', 'year'));
        // $content = $pdf->download()->getOriginalContent();
        // Storage::put('public/bills/'.$print_invoice . '.pdf', $content);
        // return $print_invoice . '.pdf';

        $pdf = PDF::loadView('pdf.invoice', compact('projects', 'user', 'date', 'invoice', 'year'));
        return $pdf;
    }

    public function send_invoice(Request $request)
    {
        $to = $request->input('to');
        $id = $request->input('id');
        $name = $request->input('name');
        $subject = $request->input('subject');
        $template = $request->input('template');
        $body = $request->input('body');

        if ($template) {
            $pdf = self::pdf_mail($id);
            // $pdf = $pdf->output();
            Mail::to($to, $name)->send(new SendInvoice($subject, $body, $template, $pdf));
        } else {
            Mail::to($to, $name)->send(new SendInvoice($subject, $body));
        }
        return response()->json(array('msg' => 'Success'), 200);
    }

    public function pdf_download_by_category($id)
    {

        $cat = Cat::WHERE('id', $id)->first();
        $projects = Project::where('cat_id', $id)->WHERE("stat", '0')->WHERE("is_selected_for_first_evaluation", true)->WHERE("inv", true)->get();
        $project_ids = "";

        foreach ($projects as $project) {
            $project_ids .= $project->id . ',';
        }
        $project_ids = rtrim($project_ids, ',');
        $date = date("Y-m-d");
        // $year = date("Y");
        $year = "2020";

        $count = Invoice::where('project_ids', $project_ids)->where('user_id', $id)->count();
        if ($count == 0) {
            $invoice = Invoice::create(['user_id' => $id, 'project_ids' => $project_ids, 'date' => $date]);
        } else {
            $invoice = Invoice::where('project_ids', $project_ids)->where('user_id', $id)->first();
        }


        // $pdf = PDF::loadView('pdf.invoice-cat', compact('projects', 'user', 'date', 'invoice', 'year'));
        $pdf = PDF::loadView('pdf.invoice-cat', compact('projects', 'date', 'invoice', 'year', 'cat'));
        return $pdf->download('Invoice.pdf');
    }

    public function insertProjectStepTwo(Request $data)
    {

        $user = Auth::user();
        $users = User::pluck("email", "id")->toArray();
        $users = User::all()->keyBy('id');


        $cat_id = $data->input("cat_id");
        $cats = Cat::where('id', '=', $cat_id)->first();
        $dropbox = new Dropbox();
        $access_token = $token = $dropbox->getAccessToken();
        return view('project-insert-three', get_defined_vars())->with(['user' => $user, 'users' => $users, 'access_token' => $access_token]);
    }

    public function rejectProject(Request $data)
    {

        $id = $data->id;
        $project = Project::find($id);
        $project->stat = '3';
        $project->reject_text = $data->emailBody;
        $project->save();

        //get user email
        $project = Project::where('id', $id)->first();
        $user_id = $project->user_id;
        $user = User::where('id', $user_id)->first();

        //Add to table RejectingProject

        // DB::table('projects')->insert(
        //    ['user_id'     =>  $user_id, 'project_id'   =>  $id,'projektname' => $project->projektname, 'text' => $data->emailBody ]
        //);
        // $reject->save();        //this will add these values into your DB

        // Send Email
        Mail::to($user->email)->send(new RejectingProject($data->emailBody, $project->projektname, $user->vorname));

        Session::flash('alert-success', 'Das Projekt wurde erfolgreich zurückgewiesen.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function deleteProject(Request $data)
    {

        $id = $data->id;
        $project = Project::find($id);
        $project->stat = '1';
        $project->save();

        /*        //get user email
            $project = Project::where('id', $id)->first();
            $user_id = $project->user_id;
            $user = User::where('id', $user_id)->first();

            // Send Email
            Mail::to($user->email)->send(new RejectingProject($data->emailBody, $project->projektname, $user->vorname.' '.$user->name));*/
        $user = User::where('id', $project->user_id)->first();
        Mail::to($user->email)->send(new DeleteProjectMail($project->projektname, $user->vorname . ' ' . $user->name));

        Session::flash('alert-success', 'Das Projekt wurde erfolgreich gelöscht.');

        return response()->json(array('msg' => 'Success'), 200);
    }


    public function acceptProject(Request $data)
    {
        $id = $data->id;
        $project = Project::find($id);
        // $project->stat = '2';
        $project->inv = '1';
        $project->is_selected_for_first_evaluation = true;
        $project->save();

        //get user email
        $project = Project::where('id', $id)->first();
        $user_id = $project->user_id;
        $user = User::where('id', $user_id)->first();

        // Send Email
        Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AcceptingProject($project->projektname, $user->vorname));

        // Session::flash('alert-success', 'Das Projekt wurde angenommen.');
        Session::flash('alert-success', 'Zur Bewertung freigegeben.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function juryProject(Request $data)
    {

        $id = $data->id;
        $project = Project::find($id);
        $project->stat = '2';
        $project->jury = '1';
        $project->save();

        //get user email
        $project = Project::where('id', $id)->first();
        $user_id = $project->user_id;
        $user = User::where('id', $user_id)->first();

        // Send Email
        // Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AcceptingProject($project->projektname, $user->vorname));

        Session::flash('alert-success', 'Das Projekt wurde angenommen und für die Jury freigegeben.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function invProject(Request $data)
    {
        $id = $data->id;
        $project = Project::find($id);
        // $project->stat = '2';
        $project->inv = '1';
        $project->is_selected_for_first_evaluation = true;
        $project->save();

        //get user email
        $project = Project::where('id', $id)->first();
        $user_id = $project->user_id;
        $user = User::where('id', $user_id)->first();

        // Send Email
        Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AcceptingProject($project->projektname, $user->vorname));

        // Session::flash('alert-success', 'Das Projekt wurde angenommen und für die Jury freigegeben.');
        Session::flash('alert-success', 'Abgespeichert.');

        return response()->json(array('msg' => 'Success'), 200);
    }


    public function special(Request $data)
    {
        $id = $data->id;
        $project = Project::find($id);
        $project->special = $data->dataValue;
        $project->save();
        return response()->json(array('msg' => 'Success'), 200);
    }

    public function service(Request $data)
    {
        $id = $data->id;
        $project = Project::find($id);
        $project->service = $data->dataValue;
        $project->save();
        return response()->json(array('msg' => 'Success'), 200);
    }

    public function free(Request $data)
    {
        $id = $data->id;
        $project = Project::find($id);
        $project->free = $data->dataValue;
        $project->save();
        return response()->json(array('msg' => 'Success'), 200);
    }

    public function showRating(Request $data)
    {
        $id = $data->id;
        $project = Project::find($id);
        $project->rating_visible = $data->dataValue;
        $project->save();
        return response()->json(array('msg' => 'Success'), 200);
    }

    public function juryAddProject(Request $data)
    {
        $id = $data->id;
        $project = Project::find($id);
        $project->jury = '1';
        $project->save();

        /*//get user email
            $project = Project::where('id', $id)->first();
            $user_id = $project->user_id;
            $user = User::where('id', $user_id)->first();

            // Send Email
            Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AcceptingProject($project->projektname, $user->vorname));*/

        Session::flash('alert-success', 'Das Projekt wurde für die Jury freigegeben.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function invAddProject(Request $data)
    {

        $id = $data->id;
        $project = Project::find($id);
        $project->inv = '1';
        $project->save();

        /*//get user email
          $project = Project::where('id', $id)->first();
          $user_id = $project->user_id;
          $user = User::where('id', $user_id)->first();

          // Send Email
          Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AcceptingProject($project->projektname, $user->vorname));*/

        Session::flash('alert-success', 'Das Projekt wurde für die Inv freigegeben.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function juryRemoveProject(Request $data)
    {

        $id = $data->id;
        $project = Project::find($id);
        $project->jury = '0';
        $project->save();

        /*//get user email
            $project = Project::where('id', $id)->first();
            $user_id = $project->user_id;
            $user = User::where('id', $user_id)->first();

            // Send Email
            Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AcceptingProject($project->projektname, $user->vorname));*/

        Session::flash('alert-success', 'Das Projekt wurde aus der Jury entfernt.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function invRemoveProject(Request $data)
    {

        $id = $data->id;
        $project = Project::find($id);
        $project->inv = '0';
        $project->save();

        /*//get user email
          $project = Project::where('id', $id)->first();
          $user_id = $project->user_id;
          $user = User::where('id', $user_id)->first();

          // Send Email
          Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AcceptingProject($project->projektname, $user->vorname));*/

        Session::flash('alert-success', 'Das Projekt wurde aus der Inv entfernt.');

        return response()->json(array('msg' => 'Success'), 200);
    }


    public function changeProject(Request $data)
    {
        $users = User::all()->keyBy('id');
        // print_r($data->autoUpdateStatus);
        //return $data->all();
        //die;
        if ($data->submit == 'change') {
            $user = Auth::user();
            $projectID = $data->projectID;
            $catID = $data->catID;
            $cats = Cat::where('id', '=', $catID)->firstOrFail();
            $project = Project::where('id', '=', $projectID)->firstOrFail();
            $changeBlade = $cats->code . '-change';

            if (isset($data->autoUpdateStatus)) {
                $project_auto = Project::find($projectID);
                $project_auto->stat = '0';
                $project_auto->reject_text = null;
                $project_auto->save();
            }

            return view($changeBlade, compact('project', 'user', 'cats', 'users'))->with(['user' => $user]);
        } elseif ($data->submit == 'delete') {

            $projectID = $data->projectID;

            $project_del = Project::find($projectID);
            $project_del->stat = '1';
            $project_del->save();


            return redirect()->route("project-show")->with('alert-success', 'Das Projekt mit der Projekt ID: ' . $projectID . ' wurde erfolgreich gelöscht!');
        }
    }

    public function ProjectChange(Request $data)
    {
        $user = Auth::user();

        $project_id = $data['project_id'];


        /**
         * video upload
         */
        //get old link
        $youtube = Project::where('id', $project_id)->first()->youtube;


        if ($data->has('uploaded_youtube_file_name')) {
            if (!empty($data->input('uploaded_youtube_file_name'))) {
                /**
                 * NEW DROPBOX CODE
                 */
                // upload
                // upload
                $dropbox = new Dropbox();
                $d_path = $data->input("uploaded_youtube_file_name");
                // share link
                $token = $dropbox->getAccessToken();
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
            "path": "' . $d_path . '",
                                        "settings": {
                                            "access": "viewer",
                                            "allow_download": true,
                                            "audience": "public",
                                            "requested_visibility": "public"
                                          }
                                        }',
                    CURLOPT_HTTPHEADER => [
                        'Content: application/json',
                        'Authorization: Bearer ' . $token,
                        'Content-Type: application/json'
                    ]
                ]);

                $response = curl_exec($curl);
                $dropbox_return_data = json_decode($response, true);
                $youtube = $dropbox_return_data['url'];
                $youtube = str_replace('?dl=0', '?raw=1', $youtube);


                // $dropbox = new Dropbox();

                // $video = $data->file('youtube');
                // $path = $video->getRealPath();
                // $size = filesize($path);
                // $tmp = explode('.', $video->getClientOriginalName());
                // $ext = end($tmp);
                // $d_path = '/' . uniqid() . '.' . $ext;
                // $fp = fopen($path, 'rb');
                // $dropbox->files()->upload($d_path, $path);
                // // share link
                // $token = $dropbox->getAccessToken();
                // $curl = curl_init();
                // curl_setopt_array($curl, [
                //   CURLOPT_URL => 'https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings',
                //   CURLOPT_RETURNTRANSFER => true,
                //   CURLOPT_CUSTOMREQUEST => 'POST',
                //   CURLOPT_POSTFIELDS => '{
                //                                 "path": "' . $d_path . '",
                //                                 "settings": {
                //                                     "access": "viewer",
                //                                     "allow_download": true,
                //                                     "audience": "public",
                //                                     "requested_visibility": "public"
                //                                 }
                //                             }',
                //   CURLOPT_HTTPHEADER => [
                //     'Content: application/json',
                //     'Authorization: Bearer ' . $token,
                //     'Content-Type: application/json'
                //   ]
                // ]);

                // $response = curl_exec($curl);
                // $dropbox_return_data = json_decode($response, true);
                // $youtube = $dropbox_return_data['url'];
                // $youtube = str_replace('?dl=0', '?raw=1', $youtube);

                /**
                 * OLD CODE
                 */
                // $video = $data->file('youtube');
                // $realpath = $video->getRealPath();
                // $video = Vimeo::upload($realpath, [
                //   'name'          => $data->input("name"),
                //   'description'   => $data->input("beschreibung"),
                //   'privacy'       => 'anybody',
                // ]);

                // if ($video) {
                //   $video = str_replace('videos/', '', $video);
                //   $youtube = "https://player.vimeo.com/video" . $video;
                // }
            }
        }


        $project = Project::find($project_id);
        $project->projektname = $data['projektname'];
        if ($data['rating_visible'])
            $project->rating_visible = $data['rating_visible'];
        else
            $project->rating_visible = 0;
        $project->beschreibung = str_replace(['"', "'"], "", $data['beschreibung']);
        $project->testimonial = str_replace(['"', "'"], "", $data['testimonial']);
        $project->youtube = $youtube;
        // $project->projektname         = $data['name'];
        $project->cat_name = $data['cat_name'];
        if (Auth::user()->rolle == 0 || Auth::user()->id == $project->user_id) {
            $project->stat = 0;
        } else {
            if (isset($data['stat'])) {
                $project->stat = $data['stat'];
            }
        }

        if ($data->has('check')) {
            $project->check = 1;
        }

        $project->copyright = str_replace(['"', "'"], "", $data['copyright']);
        $project->datum = str_replace(['"', "'"], "", $data['datum']);
        $project->ort = str_replace(['"', "'"], "", $data['ort']);
        $project->extra = str_replace(['"', "'"], "", $data['extra']);
        if ($project->isDirty()) {
            $project->save();

            Mail::to($user->email)->send(new ChangeProjectMail($project->projektname, $user->vorname . ' ' . $user->name));

            return redirect()->route("project-show")->with('alert-success', 'Das Projekt ' . $data['name'] . ' wurde erfolgreich geändert!');
        }

        return redirect()->route("home");
    }

    public function insertProject(Request $data)
    {

        $cat_id = $data->input("cat_id");
        $juries = JuryCategoryPermission::where('cat_id', $cat_id)
            ->pluck('user_id')
            ->toArray();

        $group = $data['group'];

        if ($data->has('check')) {
            $check = 1;
        }

        $cats = Cat::where('id', '=', $cat_id)->first();
        /*if ($data->input("selected_user") == 0) {
          $userId = Auth::id();
        }else{
          $userId = $data->input("selected_user");
        }*/
        $userId = $data->input("user_id");

        $user = Auth::user();

        $projectname = time();

        // video upload
        $youtube = "";

        if ($data->has('uploaded_youtube_file_name')) {
            if (!empty($data->input('uploaded_youtube_file_name'))) {
                /**
                 * NEW DROPBOX CODE
                 */
                // upload
                $dropbox = new Dropbox();
                $d_path = $data->input("uploaded_youtube_file_name");
                // share link
                $token = $dropbox->getAccessToken();
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => 'https://api.dropboxapi.com/2/sharing/create_shared_link_with_settings',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => '{
            "path": "' . $d_path . '",
                                        "settings": {
                                            "access": "viewer",
                                            "allow_download": true,
                                            "audience": "public",
                                            "requested_visibility": "public"
                                          }
                                        }',
                    CURLOPT_HTTPHEADER => [
                        'Content: application/json',
                        'Authorization: Bearer ' . $token,
                        'Content-Type: application/json'
                    ]
                ]);

                $response = curl_exec($curl);
                $dropbox_return_data = json_decode($response, true);
                $youtube = $dropbox_return_data['url'];
                $youtube = str_replace('?dl=0', '?raw=1', $youtube);


                /**
                 * OLD CODE
                 */
                // $video = $data->file('youtube');
                // $realpath = $video->getRealPath();
                // $video = Vimeo::upload($realpath, [
                //   'name'          => $data->input("name"),
                //   'description'   => $data->input("beschreibung"),
                //   'privacy'       => [
                //     "view" => "anybody"
                //   ],
                // ]);

                // if ($video) {
                //   $video = str_replace('videos/', '', $video);
                //   $youtube = "https://player.vimeo.com/video" . $video;
                // }
            }
        }

        $project = new Project;
        $project->projektname = $data->input("name");
        $project->projektname = $projectname;
        $project->cat_id = $data->input("cat_id");
        $project->cat_name = $data->input("cat_name");
        $project->group = $group;
        $project->user_id = $userId;
        $project->beschreibung = str_replace(['"', "'"], "", $data->input("beschreibung"));
        $project->youtube = $youtube;
        $project->copyright = str_replace(['"', "'"], "", $data->input("copyright"));
        $project->testimonial = str_replace(['"', "'"], "", $data->input("testimonial"));
        $project->extra = str_replace(['"', "'"], "", $data->input("extra"));
        $project->datum = str_replace(['"', "'"], "", $data->input("datum"));
        $project->ort = str_replace(['"', "'"], "", $data->input("ort"));
        $project->check = $check;
        $project->save();

        $project_id = $project->id;

        if ($project_id) {
            foreach ($juries as $jury) {
                $USER = User::withTrashed()->find($jury);
                if ($USER) {
                    $USER->firstRoundEvaluation()->create(['project_id' => $project_id]);
                }
            }
        }

        Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AddProjectMail($project->projektname, $user->vorname));

        Session::flash('alert-success', 'Das Projekt wurde gespeichert!');

        if ($data->input("cat_id") == 29) {
            return redirect('project-show');
        }

        return view('project-insert-picture', get_defined_vars())->with(['user' => $user]);
    }


    public function AddImage($project_id, $cat_id, $is_auto = null)
    {

        if ($is_auto != null) {
            $project_auto = Project::find($project_id);
            $project_auto->stat = '0';
            $project_auto->save();
        }

        $cats = Cat::where('id', '=', $cat_id)->first();

        $userId = Auth::id();
        $user = Auth::user();

        //max image = 5 - current image
        $current_image_number = Image::where('project_id', $project_id)->count();
        $max_img = $cats->count - $current_image_number;

        if ($max_img < 0) {
            $max_img = 0;
        }
        return view('project-add-new-picture', get_defined_vars())->with(['user' => $user, 'max_img' => $max_img]);
    }

    public function EditImage($project_id, $cat_id, $is_auto = null)
    {

        $projectUserId = null;
        $project_auto = Project::find($project_id);
        if ($is_auto != null) {
            $project_auto->stat = '0';
            $project_auto->save();
        }
        $projectUserId = !empty($project_auto) ? $project_auto->user_id : null;

        $cats = Cat::where('id', '=', $cat_id)->first();

        $userId = Auth::id();
        $user = Auth::user();

        //max image = 5 - current image
        $current_image_number = Image::where('project_id', $project_id)->count();
        $current_images = Image::where('project_id', $project_id)->get();
        $max_img = $cats->count - $current_image_number;

        if ($max_img < 0) {
            $max_img = 0;
        }
        return view('project-edit-new-picture', get_defined_vars())->with(['user' => $user, 'projectUserId' => $projectUserId, 'max_img' => $max_img, 'current_images' => $current_images]);
    }


    public function upload(Request $request)
    {


        \Log::debug('An informational message.');
        $cat_id = $request->input('cat_id');
        $project_id = $request->input('project_id');
        $userId = Auth::id();

        $project = Project::where('id', $project_id)->first();

        $photo = $request->file('file');


        $name = sha1(date('YmdHis') . str_random(30));
        $save_name = $name . '.' . $photo->getClientOriginalExtension();
        // $resize_name = $name . str_random(2) . '.' . $photo->getClientOriginalExtension();


        if (!File::exists($this->photos_path . '/' . $userId)) {
            File::makeDirectory($this->photos_path . '/' . $userId, 0777, true);
        }

        if (!File::exists($this->photos_path . '/' . $userId . '/' . $cat_id)) {
            File::makeDirectory($this->photos_path . '/' . $userId . '/' . $cat_id, 0777, true);
        }
        if (!File::exists($this->photos_path . '/' . $userId . '/' . $cat_id . '/' . $project->projektname)) {
            File::makeDirectory($this->photos_path . '/' . $userId . '/' . $cat_id . '/' . $project->projektname, 0777, true);
        }

        if (!File::exists($this->photos_path . '/' . $userId . '/' . $cat_id . '/' . $project->projektname . '/thumbnail')) {
            File::makeDirectory($this->photos_path . '/' . $userId . '/' . $cat_id . '/' . $project->projektname . '/thumbnail', 0777, true);
        }


        Im::make($photo)
            ->resize(200, null, function ($constraints) {
                $constraints->aspectRatio();
            })
            ->save($this->photos_path . '/' . $userId . '/' . $cat_id . '/' . $project->projektname . '/thumbnail/' . $save_name);

        $photo->move($this->photos_path . '/' . $userId . '/' . $cat_id . '/' . $project->projektname . '/', $save_name);


        if (!File::exists($this->photos_path . '/' . $userId . '/' . $cat_id . '/' . $project->projektname . '/thumbnail/' . $save_name)) {
            $upload_success = false;
        } else {
            $upload_success = true;
        }


        $Image = new Image;
        $Image->project_id = $request['project_id'];
        $Image->filename = $save_name;
        $Image->url = 'images/' . $userId . '/' . $cat_id . '/' . $project->projektname . '/' . $save_name;
        $Image->thumb_url = 'images/' . $userId . '/' . $cat_id . '/' . $project->projektname . '/thumbnail/' . $save_name;
        if ($Image->isDirty()) {
            Session::put('project_stat_' . $request['project_id'], $project->stat);
            $project->stat = 0;
            $project->save();
        }
        $Image->save();
        Session::flash('alert-success', 'Images added to project.');


        if ($upload_success) {
            return json_encode(array('fileName' => $save_name, 'status' => 200));
        } else {
            return json_encode(array('fileName' => "", 'status' => 400));
        }
    }

    public function DeleteImage(Request $request)
    {
        $id = $request->input('id');
        //  File::delete('../award/'.$request->input('thumb_url'));
        File::delete(public_path($request->input('thumb_url')));
        //  File::delete('../award/'.$request->input('url'));
        File::delete(public_path($request->input('url')));

        $image = Image::find($id);
        $project = Project::where('id', $image->project_id)->first();
        $project->stat = 0;
        $project->save();
        $image->delete();

        Session::flash('alert-success', 'Image Deleted.');

        return json_encode(array('status' => 200));
    }

    public function DeleteImageInstant(Request $request)
    {
        $image = Image::Where('project_id', $request->input('id'))->first();
        File::delete(public_path($image->url));
        File::delete(public_path($image->thumb_url));
        $image->delete();

        $project = Project::where('id', $request->input('id'))->first();
        $project->stat = Session::get('project_stat_' . $request->input('id'));
        $project->save();

        session()->forget('project_stat_' . $request->input('id'));

        Session::flash('alert-success', 'Image Deleted.');

        return json_encode(array('status' => 200));
    }


    public function delete(Request $request)
    {
        $fileName = $request->input('fileName');

        $dynamic_ids = Image::join('projects', 'images.project_id', '=', 'projects.id')
            ->select('projects.user_id', 'projects.cat_id')
            ->where('images.filename', $fileName)
            ->get();
        $user = $dynamic_ids[0]->user_id;
        $cat = $dynamic_ids[0]->cat_id;

        //  $thumb_filePath = '../award/images/'.$user.'/'.$cat.'/'.$project->projektname.'/thumbnail/';
        $thumb_filePath = '../images/' . $user . '/' . $cat . '/' . $project->projektname . '/thumbnail/';
        File::delete($thumb_filePath . $fileName);
        //  $wide_filePath = '../award/images/'.$user.'/'.$cat.'/'.$project->projektname.'/';
        $wide_filePath = '../images/' . $user . '/' . $cat . '/' . $project->projektname . '/';
        File::delete($wide_filePath . $fileName);

        $id_to_delete = Image::where('filename', $fileName)->first()->id;
        Image::find($id_to_delete)->delete();


        return $fileName;
    }

    public function show_delete(Request $request)
    {
        $fileName = $request->input('fileName');
        $project_id = $request->input('project_id');
        $project = Project::where('id', $project_id)->first();
        $userId = Auth::id();
        $user = User::where('id', $userId)->first();
        $role = $user->rolle;
        if ($role == 0 || $role == 9) {
            $dynamic_ids = Image::join('projects', 'images.project_id', '=', 'projects.id')
                ->select('projects.user_id', 'projects.cat_id')
                ->where('images.filename', $fileName)
                ->get();
            $user = $dynamic_ids[0]->user_id;
            $cat = $dynamic_ids[0]->cat_id;
            $thumb_filePath = 'images/' . $user . '/' . $cat . '/' . $project->projektname . '/thumbnail/';
            File::delete($thumb_filePath . $fileName);
            $wide_filePath = 'images/' . $user . '/' . $cat . '/' . $project->projektname . '/';
            File::delete($wide_filePath . $fileName);

            $id_to_delete = Image::where('filename', $fileName)->first()->id;
            Image::find($id_to_delete)->delete();
        }

        $id_to_delete = Image::where('filename', $fileName)->first()->id;
        $image = Image::find($id_to_delete);
        $image->state = 1;
        $image->save();

        return $fileName;
    }

    public function myform()
    {

        $user = Auth::user();
        $cats = Cat::where('stat', '!=', '1')
            ->orderBy('name', 'asc')->get();

        return view('project-insert', get_defined_vars());
    }

    public function categorySelect()
    {

        $user = Auth::user();
        $cats = Cat::where('stat', '!=', '1')
            ->orderBy('name', 'asc')->get();

        return view('project-category-select', compact('user', 'cats'));
    }


    public function filter(Request $request)
    {
        $cat_id = $request->input("cat_id");
        //  return $cat_id;


        //  $code = Cat::where('id', $cat_id)->first();
        $query = Cat::where('id', '=', $cat_id);

        var_dump($code->code);

        //  return view('project-insert', get_defined_vars());
    }

    public function ProjectShow()
    {

        $user = Auth::user();
        $projects = Project::with(['images' => function ($query) {
            $query->where('state', 0);
        }, 'count'])
            ->where('stat', '!=', '1')
            ->whereHas('user', function ($query) use ($user) {
                $query->where('id', '=', $user->id);
            })->get();
        // return view('project-show', compact('projects', 'user','cat'));
        // new
        $cats_ids = Cat::pluck('id');
        $cat_ids = [];
        foreach ($cats_ids as $cats_id) {
            array_push($cat_ids, $cats_id);
        }
        $project_cat = Project::pluck('cat_id', 'id');
        $all_votes = Count::get();
        foreach ($all_votes as $vote) {
            $project_id = $vote->project_id;
            $cat_id = 0;
            if (isset($project_cat[$project_id])) {
                $cat_id = $project_cat[$project_id];
            }

            if (!isset($cat_ids[$cat_id])) {
                $cat_ids[$cat_id] = [];
            }

            if (!is_array($cat_ids[$cat_id])) {
                $cat_ids[$cat_id] = [];
            }
            if (!isset($cat_ids[$cat_id][$project_id])) {
                $cat_ids[$cat_id][$project_id] = $vote->counts;
                // array_push($cat_ids[$cat_id], [$project_id => $vote->counts]);
            } else {
                $cat_ids[$cat_id][$project_id] += $vote->counts;
            }
        }
        // new ends
        return view('project-show', compact('projects', 'user', 'cat_ids'));
    }

    public function projectRejected()
    {

        $user = Auth::user();
        $projects = Project::with(['images' => function ($query) {
            $query->where('state', 0);
        }])
            ->where('stat', 3)
            ->whereHas('user', function ($query) use ($user) {
                $query->where('id', '=', $user->id);
            })->get();
        // dd($projects);
        // return view('project-show', compact('projects', 'user','cat'));

        // dd($projects);
        return view('project-rejected', compact('projects', 'user'));
    }


    public function adminProjectShowAll(Request $request)
    {
        $is_paginate = true;
        $keyword = $request->get('search');
        if (!empty($keyword)) {
            /*search in user model*/
            $searched_user = User::where('email', $request->get('search'))
                ->orWhere('vorname', $request->get('search'))
                ->orWhere('name', $request->get('search'))
                ->pluck('id')
                ->toArray();

            $get_project_by_searched_user = Project::where('stat', '!=', '1')
                ->whereIn('user_id', $searched_user)
                ->pluck('id')
                ->toArray();

            /*search in project*/
            $project_with_search = Project::Where('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('projektname', 'LIKE', '%' . $keyword . '%')
                ->orWhere('cat_id', 'LIKE', '%' . $keyword . '%')
                ->orWhere('cat_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('group', 'LIKE', '%' . $keyword . '%')
                ->orWhere('beschreibung', 'LIKE', '%' . $keyword . '%')
                ->orWhere('youtube', 'LIKE', '%' . $keyword . '%')
                ->orWhere('copyright', 'LIKE', '%' . $keyword . '%')
                ->orWhere('testimonial', 'LIKE', '%' . $keyword . '%')
                ->orWhere('datum', 'LIKE', '%' . $keyword . '%')
                ->orWhere('ort', 'LIKE', '%' . $keyword . '%')
                ->orWhere('extra', 'LIKE', '%' . $keyword . '%')
                ->where('stat', '!=', '1')
                ->pluck('id')
                ->toArray();

            /*combine 2 array*/
            $project_ids = array_unique(array_merge($get_project_by_searched_user, $project_with_search));
            $projects = Project::whereIn('id', $project_ids)->get();

            /*turn of pagination*/
            $is_paginate = false;
        } else {
            $projects = Project::where('stat', '!=', '1')->paginate($this->data_per_page);
        }

        $user = Auth::user();
        $user_array = [];
        foreach ($projects as $project) {
            array_push($user_array, $project->user_id);
        }
        $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();

        return view('admin-project-show-all', compact('projects', 'user', 'users', 'is_paginate'));
    }

    public function ProjectBewerten(Request $request, $cat_id = null)
    {

        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->pluck('name', 'id');
        $pids = Count::WHERE('user_id', $user->id)->pluck('project_id');

        $projects = Project::where('stat', '=', '2')
            ->where('jury', '=', '1')
            ->with('images');
        if ($cat_id != null) {
            $projects = $projects->where('cat_id', $cat_id);
        }
        if (count($pids) != 0) {
            $projects = $projects->whereNotIn('id', $pids);
        }
        if ($user->rolle == 2) {
            $projects = $projects->where('special', '1');
        }
        $projects = $projects->paginate(5);


        if ($request->ajax()) {


            $count = Project::where('stat', '=', '2')
                ->where('jury', '=', '1')
                ->with('images');
            if ($cat_id != null) {
                $count = $count->where('cat_id', $cat_id);
            }
            if (count($pids) != 0) {
                $count = $count->whereNotIn('id', $pids);
            }
            if ($user->rolle == 2) {
                $count = $count->where('special', '1');
            }
            $count = $count->count();


            if (ceil($count / 5) == $request->input("page")) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }


            return [
                'projects' => view('ajax-load')->with(compact('projects', 'user', 'all_cats', 'cat_id', 'do_work'))->render(),
                'next_page' => $projects->nextPageUrl()
            ];
        } else {

            $count = Project::where('stat', '=', '2')
                ->where('jury', '=', '1')
                ->with('images');
            if ($cat_id != null) {
                $count = $count->where('cat_id', $cat_id);
            }
            if (count($pids) != 0) {
                $count = $count->whereNotIn('id', $pids);
            }
            if ($user->rolle == 2) {
                $count = $count->where('special', '1');
            }
            $count = $count->count();


            if (ceil($count) <= 5) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
        }

        // dd($count);

        return view('project-show-rater', compact('projects', 'user', 'all_cats', 'cat_id', 'do_work'));
    }

    public function ProjectFirstRound(Request $request, $cat_id = null)
    {
        $user = Auth::user();
        $jury_cats = JuryCategoryPermission::where('user_id', $user->id)
            ->pluck('cat_id')
            ->toArray();

        $all_cats = Cat::orderBy('name')->wherein('id', $jury_cats)->pluck('name', 'id');

        $keyword = $request->get('search');

        if (!empty($keyword)) {
            /*search in project*/
            $project_with_search = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                ->select('projects.*')
                ->where('first_round_evaluation.jury_id', '=', $user->id)
                ->whereNull('first_round_evaluation.status')
                ->where(function ($query) use ($keyword) {
                    $query->where('name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('projektname', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('cat_name', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('group', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('beschreibung', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('youtube', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('copyright', 'LIKE', '%' . $keyword . '%')
                        ->orWhere('testimonial', 'LIKE', '%' . $keyword . '%');
                })
                ->wherein('cat_id', $jury_cats)
                ->where('stat', '0')
                ->where('is_selected_for_first_evaluation', '=', true)
                ->where('is_failed_first_evolution', '=', false)
                ->pluck('projects.id')
                ->distinct()
                ->toArray();

            if ($cat_id == null) {
                $projects = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                    ->select('projects.*')
                    ->where('first_round_evaluation.jury_id', '=', $user->id)
                    ->whereNull('first_round_evaluation.status')
                    ->whereIn('projects.id', $project_with_search)
                    ->where('stat', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('is_failed_first_evolution', '=', false)
                    ->wherein('cat_id', $jury_cats)
                    ->with('images')
                    ->distinct()
                    ->paginate(5);
            } else {
                $projects = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                    ->select('projects.*')
                    ->where('first_round_evaluation.jury_id', '=', $user->id)
                    ->whereNull('first_round_evaluation.status')
                    ->whereIn('projects.id', $project_with_search)
                    ->where('stat', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('is_failed_first_evolution', '=', false)
                    ->wherein('cat_id', $jury_cats)
                    ->with('images')
                    ->distinct()
                    ->paginate(5);
            }
        } else {
            if ($cat_id == null) {
                $projects = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                    ->select('projects.*')
                    ->where('first_round_evaluation.jury_id', '=', $user->id)
                    ->whereNull('first_round_evaluation.status')
                    ->where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('is_failed_first_evolution', '=', false)
                    ->whereIn('cat_id', $jury_cats)
                    ->with('images')
                    ->distinct()
                    ->paginate(5);
            } else {
                $projects = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                    ->select('projects.*')
                    ->where('first_round_evaluation.jury_id', '=', $user->id)
                    ->whereNull('first_round_evaluation.status')
                    ->where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('is_failed_first_evolution', '=', false)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->distinct()
                    ->paginate(5);
            }
        }

        $user_array = [];
        foreach ($projects as $project) {
            array_push($user_array, $project->user_id);
        }
        $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();


        if ($request->ajax()) {
            if ($cat_id == null) {
                $count = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                    ->where('first_round_evaluation.jury_id', '=', $user->id)
                    ->whereNull('first_round_evaluation.status')
                    ->where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('is_failed_first_evolution', '=', false)
                    ->wherein('cat_id', $jury_cats)
                    ->with('images')
                    ->distinct()
                    ->count();
            } else {
                $count = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                    ->where('first_round_evaluation.jury_id', '=', $user->id)
                    ->whereNull('first_round_evaluation.status')
                    ->where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('is_failed_first_evolution', '=', false)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->distinct()
                    ->count();
            }


            $user_array = [];
            foreach ($projects as $project) {
                array_push($user_array, $project->user_id);
            }
            $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();

            if (ceil($count / 5) == $request->input("page")) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
            info($projects);
            return [
                'projects' => view('ajax-load-first-evaluation-admin')->with(compact('projects', 'user', 'all_cats', 'cat_id', 'do_work', 'users'))->render(),
                'next_page' => $projects->nextPageUrl()
            ];
        } else {
            if ($cat_id == null) {
                $count = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                    ->where('first_round_evaluation.jury_id', '=', $user->id)
                    ->whereNull('first_round_evaluation.status')
                    ->where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('is_failed_first_evolution', '=', false)
                    ->wherein('cat_id', $jury_cats)
                    ->with('images')
                    ->distinct()
                    ->count();
            } else {
                $count = Project::join('first_round_evaluation', 'first_round_evaluation.project_id', '=', 'projects.id')
                    ->where('first_round_evaluation.jury_id', '=', $user->id)
                    ->whereNull('first_round_evaluation.status')
                    ->where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('is_failed_first_evolution', '=', false)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->distinct()
                    ->count();
            }


            if (ceil($count) <= 5) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
        }
        // dd($projects);
        return view('project-first-round', compact('projects', 'user', 'all_cats', 'cat_id', 'do_work', 'users'));
    }

    public function acceptProjectFirstRound(Request $data)
    {
        $id = $data->id;

        $evaluation = FirstRoundEvaluation::where('jury_id', Auth()->user()->id)->where('project_id', $id)->first();
        $evaluation->status = 1;
        $evaluation->save();

        $juries = FirstRoundEvaluation::where('project_id', $id)->count();
        $juriesVote = FirstRoundEvaluation::where('project_id', $id)->where('status', 1)->count();

        $passVote = 0;

        if ($juries % 2 == 0) {
            $passVote = ($juries / 2) + 1;
        } else {
            $passVote = ceil(($juries / 2));
        }

        $project = Project::find($id);
        if ($juriesVote >= $passVote || $juries == 1) {
            $project->stat = '2';
            $project->is_selected_for_first_evaluation = true;
            $project->inv = '1';
            $project->jury = '1';
            $project->save();
        }

        //get user email
        $project = Project::where('id', $id)->first();
        $user_id = $project->user_id;
        $user = User::where('id', $user_id)->first();

        // Send Email
        // Mail::to($user->email)->bcc('office@austrianweddingaward.at')->send(new AcceptingProject($project->projektname, $user->vorname));

        // Session::flash('alert-success', 'Das Projekt wurde angenommen.');
        Session::flash('alert-success', 'Zur Bewertung freigegeben.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function rejectProjectFirstRound(Request $data)
    {
        $id = $data->id;
        $evaluation = FirstRoundEvaluation::where('jury_id', Auth()->user()->id)->where('project_id', $id)->first();
        $evaluation->status = 0;
        $evaluation->save();

        $juries = FirstRoundEvaluation::where('project_id', $id)->count();
        $juriesVote = FirstRoundEvaluation::where('project_id', $id)->where('status', 0)->count();

        $failedVote = 0;

        if ($juries % 2 == 0) {
            $failedVote = ($juries / 2) + 1;
        } else {
            $failedVote = ceil(($juries / 2));
        }

        $project = Project::find($id);
        if ($juriesVote >= $failedVote) {
            $project->is_failed_first_evolution = false;
            $project->save();
        }

        //get user email
        $project = Project::where('id', $id)->first();
        $user_id = $project->user_id;
        $user = User::where('id', $user_id)->first();

        // Send Email
        // Mail::to($user->email)->send(new RejectingProject($data->emailBody, $project->projektname, $user->vorname . ' ' . $user->name));

        // Session::flash('alert-success', 'Das Projekt wurde erfolgreich zurückgewiesen.');
        Session::flash('alert-success', 'Zur Bewertung freigegeben.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function disableProjectRound(Request $data)
    {
        $permission = OperateMenu::where('type', 'round')->first();
        return view('round-visibility')->with(['permission' => $permission]);
    }

    public function updateProjectRound(Request $data)
    {
        $permission = OperateMenu::where('type', 'round')->first();
        $permission->is_disable = $data->input('permission');
        $permission->save();
        return redirect('/round-visibility');
    }

    public function disableProjectVotable(Request $data)
    {
        $vote = OperateMenu::where('type', 'vote')->first();
        return view('project_votable')->with(['vote' => $vote]);
    }

    public function updateProjectVotable(Request $data)
    {
        $permission = OperateMenu::where('type', 'vote')->first();
        $permission->is_votable = $data->input('vote');
        $permission->save();
        return redirect('/project-votable');
    }

    public function getNonPublicVotableProject(Request $request, $cat_id = null)
    {
        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->pluck('name', 'id');
        $pids = Count::WHERE('user_id', $user->id)->pluck('project_id');
        $countPublicVotableProject = Project::where('is_public_votable', 1)->count();

        $projects = Project::where('projects.cat_id', '=', '26')
            ->where('projects.stat', '=', '2')
            ->where('projects.jury', '=', '1')
            ->where('projects.is_public_votable', '=', '0')
            ->withImages();
        if ($cat_id != null) {
            $projects = $projects->where('cat_id', $cat_id);
        }
        if (count($pids) != 0) {
            $projects = $projects->whereNotIn('id', $pids);
        }
        if ($user->rolle == 2) {
            $projects = $projects->where('special', '1');
        }
        $projects = $projects->paginate(5);

        if ($request->ajax()) {
            $count = Project::where('projects.cat_id', '=', '26')
                ->where('projects.stat', '=', '2')
                ->where('projects.jury', '=', '1')
                ->where('projects.is_public_votable', '=', '0')
                ->withImages();
            if ($cat_id != null) {
                $count = $count->where('cat_id', $cat_id);
            }
            if (count($pids) != 0) {
                $count = $count->whereNotIn('id', $pids);
            }
            if ($user->rolle == 2) {
                $count = $count->where('special', '1');
            }
            $count = $count->count();


            if (ceil($count / 5) == $request->input("page")) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }


            return [
                'projects' => view('ajax-load-make-public-votable')->with(compact('projects', 'user', 'all_cats', 'cat_id', 'do_work'))->render(),
                'next_page' => $projects->nextPageUrl()
            ];
        } else {

            $count = Project::where('projects.cat_id', '=', '26')
                ->where('projects.stat', '=', '2')
                ->where('projects.jury', '=', '1')
                ->where('projects.is_public_votable', '=', '0')
                ->withImages();
            if ($cat_id != null) {
                $count = $count->where('cat_id', $cat_id);
            }
            if (count($pids) != 0) {
                $count = $count->whereNotIn('id', $pids);
            }
            if ($user->rolle == 2) {
                $count = $count->where('special', '1');
            }
            $count = $count->count();


            if (ceil($count) <= 5) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
        }

        // dd($count);

        return view('project-make-public-votable', compact('countPublicVotableProject', 'projects', 'user', 'all_cats', 'cat_id', 'do_work'));
    }

    public function getPublicVotableProject(Request $request, $cat_id = null)
    {
        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->pluck('name', 'id');
        $pids = Count::WHERE('user_id', $user->id)->pluck('project_id');
        $countPublicVotableProject = Project::where('is_public_votable', 1)->count();

        $projects = Project::where('projects.cat_id', '=', '26')
            ->where('projects.stat', '=', '2')
            ->where('projects.jury', '=', '1')
            ->where('projects.is_public_votable', '=', '1')
            ->withImages();
        if ($cat_id != null) {
            $projects = $projects->where('cat_id', $cat_id);
        }
        if (count($pids) != 0) {
            $projects = $projects->whereNotIn('id', $pids);
        }
        if ($user->rolle == 2) {
            $projects = $projects->where('special', '1');
        }
        $projects = $projects->paginate(5);

        if ($request->ajax()) {
            $count = Project::where('projects.cat_id', '=', '26')
                ->where('projects.stat', '=', '2')
                ->where('projects.jury', '=', '1')
                ->where('projects.is_public_votable', '=', '1')
                ->withImages();
            if ($cat_id != null) {
                $count = $count->where('cat_id', $cat_id);
            }
            if (count($pids) != 0) {
                $count = $count->whereNotIn('id', $pids);
            }
            if ($user->rolle == 2) {
                $count = $count->where('special', '1');
            }
            $count = $count->count();


            if (ceil($count / 5) == $request->input("page")) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }


            return [
                'projects' => view('ajax-load-public-votable')->with(compact('projects', 'user', 'all_cats', 'cat_id', 'do_work'))->render(),
                'next_page' => $projects->nextPageUrl()
            ];
        } else {

            $count = Project::where('projects.cat_id', '=', '26')
                ->where('projects.stat', '=', '2')
                ->where('projects.jury', '=', '1')
                ->where('projects.is_public_votable', '=', '1')
                ->withImages();
            if ($cat_id != null) {
                $count = $count->where('cat_id', $cat_id);
            }
            if (count($pids) != 0) {
                $count = $count->whereNotIn('id', $pids);
            }
            if ($user->rolle == 2) {
                $count = $count->where('special', '1');
            }
            $count = $count->count();


            if (ceil($count) <= 5) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
        }

        // dd($count);

        return view('project-public-votable', compact('countPublicVotableProject', 'projects', 'user', 'all_cats', 'cat_id', 'do_work'));
    }

    public function updatePublicVotableProject(Request $data)
    {
        $id = $data->id;
        $project = Project::find($id);
        $project->is_public_votable = $data->status;
        $project->save();

        $count = Project::where('is_public_votable', 1)->count();

        Session::flash('alert-success', $count . ' Projekte sind Zur öffentlichen Abstimmung freigegeben.');

        return response()->json(array('msg' => 'Success'), 200);
    }

    public function ProjectRated(Request $request)
    {
        $user = Auth::user();
        if ($user->rolle == 5) {
            $count = new RolleFiveCount();
            $count->project_id = $request->project_id;
            $count->user_id = $user->id;
            $count->counts = $request->counts;
            $count->created_at = \Carbon\Carbon::now()->toDateTimeString();
            $count->updated_at = \Carbon\Carbon::now()->toDateTimeString();
            $count->save();

            return redirect('/votecoe');
        } else {
            $count = new Count;
            $count->project_id = $request->project_id;
            $count->user_id = $user->id;
            $count->counts = $request->counts;
            $count->created_at = \Carbon\Carbon::now()->toDateTimeString();
            $count->updated_at = \Carbon\Carbon::now()->toDateTimeString();
            $count->save();

            $cat_id = $request->cat_id;
            if ($cat_id != null) {
                return redirect('/project-show-rater/' . $cat_id);
            } else {
                return redirect('/project-show-rater');
            }
        }
    }

    public function singgleAdminProjectShow($id)
    {
        $user = Auth::user();
        $project = Project::where('id', $id)->with('images')->first();
        $projects_under_cat = Project::where('cat_id', $project->cat_id)->pluck('id');
        foreach ($projects_under_cat as $val) {
            $projects[$val] = 0;
        }
        $counts = Count::whereIn('project_id', $projects_under_cat)->get();
        foreach ($counts as $cnt) {
            $projects[$cnt->project_id] += $cnt->counts;
        }
        $max = max($projects);
        $current_user = User::where('id', $project->user_id)->first();
        return view('single-project-show', compact('project', 'user', 'current_user', 'max'));
    }

    public function adminProjectShow(Request $request, $stat)
    {
        $is_paginate = true;
        $keyword = $request->get('search');
        if (!empty($keyword)) {
            /*search in user model*/
            $searched_user = User::where('email', $keyword)
                ->orWhere('vorname', $keyword)
                ->orWhere('name', $keyword)
                ->pluck('id')
                ->toArray();

            $get_project_by_searched_user = Project::where('stat', $stat)
                ->whereIn('user_id', $searched_user)
                ->pluck('id')
                ->toArray();

            /*search in project*/
            $project_with_search = Project::Where('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('projektname', 'LIKE', '%' . $keyword . '%')
                ->orWhere('cat_id', 'LIKE', '%' . $keyword . '%')
                ->orWhere('cat_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('group', 'LIKE', '%' . $keyword . '%')
                ->orWhere('beschreibung', 'LIKE', '%' . $keyword . '%')
                ->orWhere('youtube', 'LIKE', '%' . $keyword . '%')
                ->orWhere('copyright', 'LIKE', '%' . $keyword . '%')
                ->orWhere('testimonial', 'LIKE', '%' . $keyword . '%')
                ->where('stat', $stat)
                ->pluck('id')
                ->toArray();

            /*combine 2 array*/
            $project_ids = array_unique(array_merge($get_project_by_searched_user, $project_with_search));
            $projects = Project::whereIn('id', $project_ids)->get();

            /*turn of pagination*/
            $is_paginate = false;
        } else {
            $projects = Project::where('stat', $stat)->paginate($this->data_per_page);
            // $projects = Project::where('stat', $stat)->get();
        }

        // dd($projects);
        $user = Auth::user();
        $user_array = [];
        foreach ($projects as $project) {
            array_push($user_array, $project->user_id);
        }

        $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();

        return view('admin-project-show', compact('projects', 'user', 'users', 'stat', 'is_paginate'));
    }

    /*  public function adminProjectShowAll() {

      $user = Auth::user();
      $stat = 1;
      $projects = Project::where('user_id', $user->id)->where('stat', '<>', '2')->get();

      $results = User::with('projects')->get();

      return view('admin-project-show-all', compact('results','projects', 'user'));

    }*/


    public function ProjectFreigeben(Request $request, $cat_id = null)
    {
        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->pluck('name', 'id');

        $keyword = $request->get('search', '');
        if ($keyword) {
            /*search in user model*/
            $searched_user = User::where('email', $request->get('search'))
                ->orWhere('vorname', $request->get('search'))
                ->orWhere('name', $request->get('search'))
                ->pluck('id')
                ->toArray();

            $get_project_by_searched_user = Project::where('stat', '0')
                ->whereIn('user_id', $searched_user)
                ->pluck('id')
                ->toArray();

            /*search in project*/
            $project_with_search = Project::where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('projektname', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('cat_id', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('cat_name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('group', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('beschreibung', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('youtube', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('copyright', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('testimonial', 'LIKE', '%' . $keyword . '%');
            })->where('stat', '0')
                ->where('is_selected_for_first_evaluation', '=', false)
                ->pluck('id')
                ->toArray();
            /*combine 2 array*/
            $project_ids = array_unique(array_merge($get_project_by_searched_user, $project_with_search));

            $projects = Project::whereIn('id', $project_ids)
                ->with('images');
            if ($cat_id) {
                $projects->where('cat_id', $cat_id);
            }
            $projects = $projects->paginate(5);

            info('keyword');
            info($projects);
        } else {
            $projects = Project::where('stat', '=', '0')
                ->where('is_selected_for_first_evaluation', '=', false)
                ->with('images');

            if ($cat_id) {
                $projects->where('cat_id', $cat_id);
            }
            $projects = $projects->paginate(5);

            info('noKeyword');
            info($projects);
        }


        $user_array = [];
        foreach ($projects as $project) {
            array_push($user_array, $project->user_id);
        }
        $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();


        if ($request->ajax()) {
            $count = Project::where('stat', '=', '0')
                ->where('is_selected_for_first_evaluation', '=', false)
                ->with('images');
            if ($cat_id) {
                $count->where('cat_id', $cat_id);
            }
            $count = $count->count();


            $user_array = [];
            foreach ($projects as $project) {
                array_push($user_array, $project->user_id);
            }
            $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();

            if (ceil($count / 5) == $request->input("page")) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
            info('Ajax');
            return [
                'projects' => view('ajax-load-admin')->with(compact('projects', 'user', 'all_cats', 'cat_id', 'do_work', 'users'))->render(),
                'next_page' => $projects->nextPageUrl()
            ];
        } else {
            $count = Project::where('stat', '=', '0')
                ->where('is_selected_for_first_evaluation', '=', false)
                ->with('images');
            if ($cat_id) {
                $count->where('cat_id', $cat_id);
            }
            $count = $count->count();

            if (ceil($count) <= 5) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
        }
        $projects = Project::where('stat', '=', '0')
            ->where('is_selected_for_first_evaluation', '=', false)
            ->with('images')
            ->paginate(100);
        return view('project-show-admin', compact('projects', 'user', 'all_cats', 'cat_id', 'do_work', 'users', 'keyword'));
    }

    public function ProjectRechnung(Request $request, $cat_id = null)
    {
        $user = Auth::user();
        $all_cats = Cat::orderBy('name')->pluck('name', 'id');

        $keyword = $request->get('search');
        if (!empty($keyword)) {
            /*search in user model*/
            $searched_user = User::where('email', $request->get('search'))
                ->orWhere('vorname', $request->get('search'))
                ->orWhere('name', $request->get('search'))
                ->pluck('id')
                ->toArray();

            $get_project_by_searched_user = Project::where('stat', '2')
                ->where('inv', 1)
                ->where('jury', 0)
                ->whereIn('user_id', $searched_user)
                ->pluck('id')
                ->toArray();

            /*search in project*/
            $project_with_search = Project::Where('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('projektname', 'LIKE', '%' . $keyword . '%')
                ->orWhere('cat_id', 'LIKE', '%' . $keyword . '%')
                ->orWhere('cat_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('group', 'LIKE', '%' . $keyword . '%')
                ->orWhere('beschreibung', 'LIKE', '%' . $keyword . '%')
                ->orWhere('youtube', 'LIKE', '%' . $keyword . '%')
                ->orWhere('copyright', 'LIKE', '%' . $keyword . '%')
                ->orWhere('testimonial', 'LIKE', '%' . $keyword . '%')
                ->where('stat', '2')
                ->where('inv', 1)
                ->where('jury', 0)
                ->pluck('id')
                ->toArray();
            /*combine 2 array*/
            $project_ids = array_unique(array_merge($get_project_by_searched_user, $project_with_search));

            if ($cat_id == null) {
                $projects = Project::whereIn('id', $project_ids)
                    ->with('images')
                    ->paginate(5);
            } else {
                $projects = Project::whereIn('id', $project_ids)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->paginate(5);
            }
        } else {
            if ($cat_id == null) {
                $projects = Project::where('stat', '=', '2')
                    ->where('inv', 1)
                    ->where('jury', 0)
                    ->with('images')
                    ->paginate(5);
            } else {
                $projects = Project::where('stat', '=', '2')
                    ->where('inv', 1)
                    ->where('jury', 0)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->paginate(5);
            }
        }


        $user_array = [];
        foreach ($projects as $project) {
            array_push($user_array, $project->user_id);
        }
        $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();


        if ($request->ajax()) {

            if ($cat_id == null) {
                $count = Project::where('stat', '=', '2')
                    ->where('inv', 1)
                    ->where('jury', 0)
                    ->with('images')
                    ->count();
            } else {
                $count = Project::where('stat', '=', '2')
                    ->where('inv', 1)
                    ->where('jury', 0)
                    ->where('cat_id', '=', $cat_id)
                    ->with('images')
                    ->count();
            }


            $user_array = [];
            foreach ($projects as $project) {
                array_push($user_array, $project->user_id);
            }
            $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();

            if (ceil($count / 5) == $request->input("page")) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }

            return [
                'projects' => view('ajax-load-admin')->with(compact('projects', 'user', 'all_cats', 'cat_id', 'do_work', 'users'))->render(),
                'next_page' => $projects->nextPageUrl()
            ];
        } else {

            if ($cat_id == null) {
                $count = Project::where('stat', '=', '2')
                    ->where('inv', 1)
                    ->where('jury', 0)
                    ->with('images')
                    ->count();
            } else {
                $count = Project::where('stat', '=', '2')
                    ->where('inv', 1)
                    ->where('jury', 0)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->count();
            }


            if (ceil($count) <= 5) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
        }

        return view('project-show-rechung-admin', compact('projects', 'user', 'all_cats', 'cat_id', 'do_work', 'users'));
    }


    public function ProjectFreigegebene(Request $request, $cat_id = null)
    {

        $user = Auth::user();
        $jury_cats = JuryCategoryPermission::where('user_id', $user->id)
            ->pluck('cat_id')
            ->toArray();
        $all_cats = Cat::orderBy('name')->wherein('id', $jury_cats)->pluck('name', 'id');
        // $all_cats = Cat::orderBy('name')->pluck('name', 'id');
        $keyword = $request->get('search');
        if (!empty($keyword)) {
            /*search in user model*/
            $searched_user = User::where('email', $request->get('search'))
                ->orWhere('vorname', $request->get('search'))
                ->orWhere('name', $request->get('search'))
                ->pluck('id')
                ->toArray();

            $get_project_by_searched_user = Project::where('stat', '2')
                ->whereIn('user_id', $searched_user)
                ->pluck('id')
                ->toArray();

            /*search in project*/
            $project_with_search = Project::where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('projektname', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('cat_id', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('cat_name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('group', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('beschreibung', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('youtube', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('copyright', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('testimonial', 'LIKE', '%' . $keyword . '%');
            })->where('stat', '2')
                ->where('is_selected_for_first_evaluation', '=', true)
                ->pluck('id')
                ->toArray();
            /*combine 2 array*/
            $project_ids = array_unique(array_merge($get_project_by_searched_user, $project_with_search));

            if ($cat_id == null) {
                $projects = Project::whereIn('id', $project_ids)
                    ->with('images')
                    ->paginate(5);
            } else {
                $projects = Project::whereIn('id', $project_ids)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->paginate(5);
            }
        } else {
            if ($cat_id == null) {
                $projects = Project::where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->with('images')
                    ->paginate(5);
            } else {
                $projects = Project::where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->paginate(5);
            }
        }


        $user_array = [];
        foreach ($projects as $project) {
            array_push($user_array, $project->user_id);
        }
        $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();


        if ($request->ajax()) {

            if ($cat_id == null) {
                $count = Project::where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->with('images')
                    ->count();
            } else {
                $count = Project::where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('cat_id', '=', $cat_id)
                    ->with('images')
                    ->count();
            }


            $user_array = [];
            foreach ($projects as $project) {
                array_push($user_array, $project->user_id);
            }
            $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();

            if (ceil($count / 5) == $request->input("page")) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }

            return [
                'projects' => view('ajax-load-admin-freigegebene')->with(compact('projects', 'user', 'all_cats', 'cat_id', 'do_work', 'users'))->render(),
                'next_page' => $projects->nextPageUrl()
            ];
        } else {

            if ($cat_id == null) {
                $count = Project::where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->with('images')
                    ->count();
            } else {
                $count = Project::where('stat', '=', '0')
                    ->where('is_selected_for_first_evaluation', '=', true)
                    ->where('cat_id', $cat_id)
                    ->with('images')
                    ->count();
            }


            if (ceil($count) <= 5) {
                $do_work = 0;
            } else {
                $do_work = 1;
            }
        }

        return view('project-show-admin-freigegebene', compact('projects', 'user', 'all_cats', 'cat_id', 'do_work', 'users'));
    }


    // public function ProjectFreigegebene(Request $request, $cat_id = null) {

    //     $user = Auth::user();
    //     $all_cats = Cat::pluck('name', 'id');

    //     $keyword = $request->get('search');
    //     if (!empty($keyword)) {
    //         /*search in user model*/
    //         $searched_user = User::where('email',$request->get('search'))
    //                                 ->orWhere('vorname', $request->get('search'))
    //                                 ->orWhere('name', $request->get('search'))
    //                                 ->pluck('id')
    //                                 ->toArray();

    //         $get_project_by_searched_user = Project::where('stat', '0')
    //                                         ->whereIn('user_id', $searched_user)
    //                                         ->pluck('id')
    //                                         ->toArray();

    //         /*search in project*/
    //         $project_with_search = Project::Where('name', 'LIKE', '%'.$keyword.'%')
    //                             ->orWhere('projektname', 'LIKE', '%'.$keyword.'%')
    //                             ->orWhere('cat_id', 'LIKE', '%'.$keyword.'%')
    //                             ->orWhere('cat_name', 'LIKE', '%'.$keyword.'%')
    //                             ->orWhere('group', 'LIKE', '%'.$keyword.'%')
    //                             ->orWhere('beschreibung', 'LIKE', '%'.$keyword.'%')
    //                             ->orWhere('youtube', 'LIKE', '%'.$keyword.'%')
    //                             ->orWhere('copyright', 'LIKE', '%'.$keyword.'%')
    //                             ->orWhere('testimonial', 'LIKE', '%'.$keyword.'%')
    //                             ->where('stat', '0')
    //                             ->pluck('id')
    //                             ->toArray();
    //         /*combine 2 array*/
    //         $project_ids = array_unique(array_merge($get_project_by_searched_user,$project_with_search));

    //         if ($cat_id == null) {
    //           $projects = Project::whereIn('id', $project_ids)
    //                             ->with('images')
    //                             ->paginate(5);
    //         }else{
    //           $projects = Project::whereIn('id', $project_ids)
    //                             ->where('cat_id', $cat_id)
    //                             ->with('images')
    //                             ->paginate(5);
    //         }

    //     }else{
    //       if ($cat_id == null) {
    //         $projects = Project::where('stat', '=', '2')
    //                   ->with('images')
    //                   ->paginate(5);
    //       }else{
    //         $projects = Project::where('stat', '=', '2')
    //                   ->where('cat_id', $cat_id)
    //                   ->with('images')
    //                   ->paginate(5);
    //       }
    //     }


    //     $user_array = [];
    //     foreach ($projects as $project) {
    //       array_push($user_array, $project->user_id);
    //     }
    //     $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();


    //     if($request->ajax()) {

    //       if ($cat_id == null) {
    //         $count = Project::where('stat', '=', '0')
    //                         ->with('images')
    //                         ->count();
    //       }else{
    //         $count = Project::where('stat', '=', '0')
    //                         ->where('cat_id', '=', $cat_id)
    //                         ->with('images')
    //                         ->count();
    //       }


    //       $user_array = [];
    //       foreach ($projects as $project) {
    //         array_push($user_array, $project->user_id);
    //       }
    //       $users = User::whereIn('id', $user_array)->get()->keyBy('id')->toArray();

    //       if ( ceil($count/5) == $request->input("page")) {
    //         $do_work = 0;
    //       }else{
    //         $do_work = 1;
    //       }

    //       return [
    //         'projects' => view('ajax-load-admin')->with(compact('projects', 'user','cat', 'all_cats', 'cat_id', 'do_work', 'users'))->render(),
    //         'next_page' => $projects->nextPageUrl()
    //       ];
    //       }else{

    //       if ($cat_id == null) {
    //         $count = Project::where('stat', '=', '0')
    //                         ->with('images')
    //                         ->count();
    //       }else{
    //         $count = Project::where('stat', '=', '0')
    //                         ->where('cat_id', $cat_id)
    //                         ->with('images')
    //                         ->count();
    //       }


    //         if ( ceil($count) <= 5) {
    //           $do_work = 0;
    //         }else{
    //           $do_work = 1;
    //         }
    //       }

    //     return view('project-show-admin-freigegebene', compact('projects', 'user', 'all_cats', 'cat_id', 'do_work','users'));

    //   }

    public function ProjectFreigegeben(Request $request)
    {

        $user = Auth::user();


        $projects = Project::where('stat', '=', '0')
            ->with('images')
            ->paginate(5);


        return view('project-show-admin', compact('projects', 'user', 'cat'));
    }


    public function awardUploadByUser(Request $request)
    {
        $user = Auth::user();
        $old_project = AwardUploadByUser::where('user_id', $user->id)->pluck('projektname');
        // $projects = Project::where('user_id', $user->id)->where('jury', 1)->whereNotIn('projektname', $old_project)->get();
        $projects = Project::where('user_id', $user->id)->where('jury', 1)->get();
        $dropbox = new Dropbox();
        $access_token = $dropbox->getAccessToken();
        return view('award-upload-by-user', compact('projects', 'user', 'access_token'));
    }


    public function awardUploadByUserList(Request $request)
    {
        $user = Auth::user();
        if ($user->rolle == 9) {
            $uploadedAwards = AwardUploadByUser::get();
            $projects = Project::pluck('name', 'projektname');
        } else {
            $uploadedAwards = AwardUploadByUser::where('user_id', $user->id)->get();
            $projects = Project::where('user_id', $user->id)->pluck('projektname', 'name');
        }

        return view('award-upload-by-user-list', compact('projects', 'user', 'uploadedAwards'));
    }

    public function awardUploadByUserSubmit(Request $request)
    {
        $user = Auth::user();
        $n = new AwardUploadByUser;
        $n->user_id = $user->id;
        $n->projektname = $request->projektname;
        $n->title = $request->title;
        $n->description = $request->description;
        $n->save();
        return view('award-upload-by-user-success', compact('user'));
    }


    public function EmailSenden(Request $request)
    {

        $userId = $request->input("user_id");
        $projectId = $request->input("project_id");

        $user = User::where('id', $userId)->first();
        $project = Project::where('id', $projectId)->first();

        return view('email-senden', compact('project', 'user'));
    }

    public function checkUserInfo(Request $request)
    {
        $content = $request->content;
        $users = User::all();
        foreach ($users as $user) {
            if (strpos($content, $user->name) !== false) {
                return response()->json(array('msg' => $user->name), 200);
            }
        }
    }
}