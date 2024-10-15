<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\award_image;
use Illuminate\Support\Facades\Auth;
use App\User;
use DB;

class AwardImageController extends Controller
{
    //
    public function saveGallery(Request $request)
    {
        $award_image = new award_image;
        $award_image->award_id = $request->award_id;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        do {
            $randomString = 'I2ybaIIhZ9';
            for ($i = 0; $i < 10; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            $randomStringCount = DB::table('award_images')
                ->where('share_url', $randomString)
                ->get();
            $rowcount = $randomStringCount->count();
        } while ($rowcount > 0);
        $award_image->share_url = $randomString;
        if ($request->hasFile('image_path')) {
            $files = $request->file('image_path');
            $paths = [];
            $i = 0;
            foreach ($files as $file) {
                $path = $file->store('public/img');
                $paths[$i] = str_replace('public/', '', $path);
                $i++;
            }
            $award_image->image_path = json_encode($paths);
        }
        $award_image->save();
        return redirect('home');
    }
    public function share(Request $request)
    {
        $share_url = DB::table('award_images')
            ->where('award_id', $request->id)
            ->get();
        return response()->json(['url' => url('') . '/image-gallery/' . $share_url[0]->share_url]);
    }
    public function showGallery($url)
    {
        $user = Auth::user();
        $galleries = DB::table('award_images')
            ->where('share_url', $url)
            ->get();
        return view('awardgallery', compact('user', 'galleries'));
    }
}
