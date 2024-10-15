<?php

namespace App\Http\Controllers;

use App\User;;
use Illuminate\Http\Request;
use DB;
use Session;

class ChangeController extends Controller
{

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array  $data
   * @return \App\User
   */
/*  protected function create(array $data)
  {
      return User::update([


      ]);

  }

  */

  public function __construct()
  {
      $this->middleware('auth');

  }

  public function add(Request $data) {

    $validatedData = $data->validate([

      'teilnahmebedingung' => 'required|string|max:1',
    ]);

    $id = \Auth::user()->id;
    $teilnahmebedingung = '1';

    $user = User::find($id);
    $user->teilnahmebedingung = $teilnahmebedingung;
    $user->save();

    //PUT HERE AFTER YOU SAVE
    Session::flash('alert-success','Teilnahmebedingungen wurden akzeptiert.');

    return redirect()->route("home");


  }

  public function change(Request $data) {

    $validatedData = $data->validate([
      'name' => 'required|string|max:255',
      'vorname' => 'required|string|max:255',
      'email' => 'required|string|email|max:255',
      'firma' => 'required|string|max:255',
      'ausgesprochen' => 'nullable|string|max:50',
      'werden' => 'nullable|string|max:50',
      'anr' => 'required|string|max:10',
      'titel' => 'nullable|string|max:10',
      'form' => 'nullable|string|max:40',
      'adresse' => 'required|string|max:255',
      'plz' => 'required|string|max:255',
      'ort' => 'required|string|max:255',
      'bundesland' => 'required|string|max:255',
      'founded' => 'required|string|max:255',
      'url' => 'nullable|string|max:255',
      'companymail' => 'nullable|string|email|max:255',
      'atu' => 'nullable|string|max:255',
      'tel' => 'required|string|max:255',
      'fb' => 'nullable|string|max:255',
      'instagram' => 'nullable|string|max:255',
      'voucher' => 'nullable|string|max:255',
    ]);

    $rechnungsadresse = 0;
    if (isset($data['rechnungsadresse'])) {
      $rechnungsadresse = 1;
    }    

    $id = \Auth::user()->id;
    $first = '1';

    $user = User::find($id);
    $user->name = $data['name'];
    $user->vorname = $data['vorname'];
    $user->email = $data['email'];
    $user->firma = $data['firma'];
    $user->anr = $data['anr'];
    $user->firma = $data['firma'];
    $user->ausgesprochen = $data['ausgesprochen'];
    $user->werden = $data['werden'];
    $user->form = $data['form'];
    $user->adresse = $data['adresse'];
    $user->plz = $data['plz'];
    $user->ort = $data['ort'];
    $user->bundesland = $data['bundesland'];
    $user->founded = $data['founded'];
    $user->url = $data['url'];
    $user->companymail = $data['companymail'];
    $user->atu = $data['atu'];
    $user->titel = $data['titel'];
    $user->fb = $data['fb'];
    $user->instagram = $data['instagram'];
    $user->voucher = $data['voucher'];
    $user->tel = $data['tel'];
    $user->first = $first;

    $user->rechnungsadresse = $rechnungsadresse;
    $user->firma_re = $data['firma_re'];
    $user->adresse_re = $data['adresse_re'];
    $user->plz_re = $data['plz_re'];
    $user->ort_re = $data['ort_re'];

    $user->save();




    //PUT HERE AFTER YOU SAVE
    Session::flash('alert-success','Change has been successfully saved.');

    return redirect()->route("home");
  }






}
