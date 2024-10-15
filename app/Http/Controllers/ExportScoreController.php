<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\User;
use App\Count;
use App\Project;
use DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportScoreController extends Controller
{
    public function showScore()
    {
        $users = User::all();
        $counts = DB::table('counts')
        ->join('projects', 'projects.id', '=', 'counts.project_id')
        ->select('projects.*', DB::raw('sum(counts) as score'))
        ->groupBy('project_id', 'user_id')
        ->orderBy('user_id')
        ->get();
        return view('showscore', compact('counts', 'users'));
    }
    public function exportScore()
    {
        $users = User::all();

        $countLeft = DB::table('projects')
            ->join('users as users', 'users.id', '=', 'projects.user_id')
            ->leftJoin('counts as counts', 'counts.project_id', '=', 'projects.id')
                ->select('projects.*','users.*', DB::raw('sum(counts) as score'))
                ->groupBy(['project_id', 'user_id', 'projects.id'])
                ->orderBy('user_id');

        $countRight = DB::table('projects')
            ->join('users', 'users.id', '=', 'projects.user_id')
            ->rightJoin('counts', 'counts.project_id', '=', 'projects.id')
                ->select('projects.*','users.*', DB::raw('sum(counts) as score'))
                ->groupBy(['project_id', 'user_id', 'projects.id'])
                ->orderBy('user_id');

        $counts = $countLeft->union($countRight)->get();;

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=AWA-' . date("Y-m-d-h-i-s") . '.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array(
            'User Name', 'Anr', 'Titel', 'Vorname', 'Name', 'Email', 'Status', 'Rolle', 'Firma', 'Form', 'Address', 'Plz', 'User Ort', 'Address_Re', 'Plz_Re', 'User_Ort_Re',
            'Bundesland', 'Tel', 'Founded', 'Url', 'Company Email', 'UID', 'First', 'Agb', 'Newsletter', 'Datenschutz',
            'Wie wird der Firmenname ausgesprochen', 'Welcher Name soll auf der Urkunde vermerkt werden', 'Facebook', 'Instagram',
            'Rabattcode', 'Project ID', 'Project Name', 'Score', 'Category', 'Group', 'Beschreibung', 'Youtube', 'Copyright',
            'Service', 'Testimonial', 'Check', 'Project Ort', 'Datum', 'Stat', 'Extra', 'Invoice', 'Jury', 'Free',
            'Project',
        ));
        foreach ($counts as $count) {
            $username = '';
            foreach ($users as $user) {
                if ($user->id == $count->user_id) {
                    $username =  $user->name ?? '';
                    break;
                }
            }
            $product_row = [
                'user Name' => $username,
                'Anr' => $count->anr,
                'Titel' => $count->titel,
                'Vorname' => $count->vorname,
                'Name' => $count->name,
                'Email' => $count->email,
                'Status' => $count->status,
                'Rolle' => $count->rolle,
                'Firma' => $count->firma,
                'Form' => $count->form,
                'Address' => $count->adresse,
                'Plz' => $count->plz,
                'User Ort' => $count->ort,
                'Address_Re' => $count->adresse_re,
                'Plz_Re' => $count->plz_re,
                'User_Ort_Re' => $count->ort_re,
                'Bundesland' => $count->bundesland,
                'Tel' => $count->tel,
                'Founded' => $count->founded,
                'Url' => $count->url,
                'Company Email' => $count->companymail,
                'UID' => '',
                'First' => $count->first,
                'Agb' => $count->agb,
                'Newsletter' => $count->newsletter,
                'Datenschutz' => $count->datenschutz,
                'Wie wird der Firmenname ausgesprochen' => $count->ausgesprochen,
                'Welcher Name soll auf der Urkunde vermerkt werden' => $count->werden,
                'Facebook' => $count->fb,
                'Instagram' => $count->instagram,
                'Rabattcode' => $count->voucher,
                'Project ID' => $count->projektname,
                'Project Name' => $count->name,
                'Score' => $count->score ?? 0,
                'Category' => $count->cat_name,
                'Group' => $count->group,
                'Beschreibung' => $count->beschreibung,
                'Youtube' => $count->youtube,
                'Copyright' => $count->copyright,
                'Service' => $count->service,
                'Testimonial' => $count->testimonial,
                'Check' => $count->check,
                'Project Ort' => $count->ort,
                'Datum' => $count->datum,
                'Stat' => $count->stat,
                'Extra' => $count->extra,
                'Invoice' => $count->inv,
                'Jury' => $count->jury,
                'Free' => $count->free,
                'Project' => $count->project,
            ];
            fputcsv($output, $product_row);
        }
    }
}