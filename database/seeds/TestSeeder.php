<?php

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            'tom' => 'alm@edertom.com',
            'doro' => 'alm@edertom.com',
            'susan' => 'alm@edertom.com',
            'florian' => 'weber@conceptsolutions.at',
            'cornelia' => 'mail@florietta.at',
            'martin' => 'office@unger-company.at',
            'christina' => 'christina.krug@schnabulerie.com',
            'octavia&Klaus' => 'info@octaviaplusklaus.com',
            'markus' => 'mail@mamusic.at',
            'verena' => 'verena@weddings-austria.com',
            'christine' => 'christine@haenselundgretel.at',
            'claudia' => 'office@gilbird.at',
        ];

        $cat1 = \App\Cat::where('name', 'Best of 10 Years')->first();
        if ($cat1) {
            $USERS = [$users['tom'], $users['martin'], $users['markus']];
            \App\User::whereIn('email', $USERS)->get()->each(function ($user) use ($cat1) {
                \App\JuryCategoryPermission::firstOrCreate(['cat_id' => $cat1->id, 'user_id' => $user->id,]);
            });
        }

        $cat2 = \App\Cat::where('name', 'Brautstyling')->first();
        if ($cat2) {
            $USERS = [$users['doro'], $users['susan'], $users['claudia']];
            \App\User::whereIn('email', $USERS)->get()->each(function ($user) use ($cat2) {
                \App\JuryCategoryPermission::firstOrCreate(['cat_id' => $cat2->id, 'user_id' => $user->id,]);
            });
        }

        $cat3 = \App\Cat::where('name', 'Entertainment / Live-Musik Trauung')->first();
        if ($cat3) {
            $USERS = [$users['susan'], $users['octavia&Klaus'], $users['christine']];
            \App\User::whereIn('email', $USERS)->get()->each(function ($user) use ($cat3) {
                \App\JuryCategoryPermission::firstOrCreate(['cat_id' => $cat3->id, 'user_id' => $user->id,]);
            });
        }

        $cat4 = \App\Cat::where('name', 'Entertainment / Live-Musik Party')->first();
        if ($cat4) {
            $USERS = [$users['tom'], $users['florian'], $users['verena']];
            \App\User::whereIn('email', $USERS)->get()->each(function ($user) use ($cat4) {
                \App\JuryCategoryPermission::firstOrCreate(['cat_id' => $cat4->id, 'user_id' => $user->id,]);
            });
        }

        $cat5 = \App\Cat::where('name', 'Entertainment / DJ')->first();
        if ($cat5) {
            $USERS = [$users['doro'], $users['cornelia'], $users['markus']];
            \App\User::whereIn('email', $USERS)->get()->each(function ($user) use ($cat5) {
                \App\JuryCategoryPermission::firstOrCreate(['cat_id' => $cat5->id, 'user_id' => $user->id,]);
            });
        }

        $cat6 = \App\Cat::where('name', 'Entertainment / Highlight')->first();
        if ($cat6) {
            $USERS = [$users['susan'], $users['florian'], $users['martin']];
            \App\User::whereIn('email', $USERS)->get()->each(function ($user) use ($cat6) {
                \App\JuryCategoryPermission::firstOrCreate(['cat_id' => $cat6->id, 'user_id' => $user->id,]);
            });
        }

    }
}
