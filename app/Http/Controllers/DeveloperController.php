<?php

namespace App\Http\Controllers;

use App\JuryCategoryPermission;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function jujuryXCategory()
    {
        $categories = \App\Cat::with(['juryCategoryPermission', 'juryCategoryPermission.user'])->orderBy('name', 'ASC')->get();
        return view('developer.jury-x-category', compact('categories'));
    }

    public function getJuriesByCategory()
    {
        $categories = \App\Cat::with(['juryCategoryPermission', 'juryCategoryPermission.user'])
            ->orderBy('name', 'ASC')->get();
        $juries = \App\User::whereIn('rolle', ['1', 1])->get();

        $juryIds = [];
        if (request()->has('cat_id')) {
            $cat_id = request()->get('cat_id');
            $juryIds = JuryCategoryPermission::where('cat_id', $cat_id)->get()->pluck('user_id')->toArray();
        }

        return view('developer.assign-jury-category', compact('categories', 'juries', 'juryIds'));
    }

    public function assignJuryCategories(Request $request)
    {
        abort_if(auth()->user()->rolle != 9, 403);

        $this->validate($request, [
            'jury_ids' => 'required|array',
            'cat_id' => 'required|exists:cats,id'
        ]);

        $cat_id = $request->cat_id;
        $jury_ids = $request->jury_ids;

        JuryCategoryPermission::where('cat_id', $cat_id)->delete();
        foreach ($jury_ids as $jury_id) {
            JuryCategoryPermission::create([
                'cat_id' => $cat_id,
                'user_id' => $jury_id
            ]);
        }

        return redirect()->back()->with('success', 'Jury categories assigned successfully');
    }
}
