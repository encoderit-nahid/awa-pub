<?php

use App\FirstRoundEvaluation;
use App\JuryCategoryPermission;
use App\Project;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//      \DB::table('users')->delete();
//      \DB::table('users')->insert(array(
//
//        0 => array (
//
//        'anr' => "Herr",
//        'titel' => "MA",
//        'vorname' => "Stefan",
//        'name' => "Lehrner",
//        'email' => "stefan@lehrner.org",
//        'password' => Hash::make('x93rbinc!'),
//        'rolle' => "0",
//        'fb' => "stefan.lehrner1",
//        'tel' => "+436505700550"
//      ),
//
//        1 => array (
//
//        'anr' => "Frau",
//        'titel' => "Mag.",
//        'vorname' => "Bianca",
//        'name' => "Lehrner",
//        'email' => "office@hochzeitsplaner.co.at",
//        'password' => Hash::make('x93rbinc!'),
//        'rolle' => "9",
//        'fb' => "stefan.lehrner1",
//        'tel' => "+436505700550"
//      ),
//
//        2 => array (
//
//        'anr' => "Frau",
//        'titel' => "",
//        'vorname' => "Bea",
//        'name' => "Bewerterin",
//        'email' => "bea@bewerterin.at",
//        'password' => Hash::make('x93rbinc!'),
//        'rolle' => "1",
//        'fb' => "stefan.lehrner1",
//        'tel' => "+436505700550"
//      ),
//
//        3 => array (
//
//        'anr' => "Frau",
//        'titel' => "",
//        'vorname' => "User",
//        'name' => "Userin",
//        'email' => "user@userin.at",
//        'password' => Hash::make('x93rbinc!'),
//        'rolle' => "0",
//        'fb' => "stefan.lehrner1",
//        'tel' => "+436505700550"
//        )
//
//      ));

        $users = [
            'tom' => 'alm@edertom.com',
            'doro' => 'alm@edertom.com',
            'susan' => 'alm@edertom.com',
            'florian' => 'weber@conceptsolutions.at',
            'cornelia' => 'mail@florietta.at',
            'martin' => 'office@unger-company.at',
            'christina' => 'christina.krug@schnabulerie.com',
            'octavia_Klaus' => 'info@octaviaplusklaus.com',
            'markus' => 'mail@mamusic.at',
            'verena' => 'verena@weddings-austria.com',
            'christine' => 'christine@haenselundgretel.at',
            'claudia' => 'office@gilbird.at',
        ];

        $userGroups = [
            'Best of 10 Years' => [$users['tom'], $users['martin'], $users['markus']],
            'Brautstyling' => [$users['doro'], $users['susan'], $users['claudia']],
            'Entertainment | Live-Musik Trauung' => [$users['susan'], $users['octavia_Klaus'], $users['christine']],
            'Entertainment | Live-Musik Party' => [$users['tom'], $users['florian'], $users['verena']],
            'Entertainment | DJ' => [$users['doro'], $users['cornelia'], $users['markus']],
            'Entertainment | Highlight' => [$users['susan'], $users['florian'], $users['martin']],
            'Floristik | Gesamtkonzept' => [$users['martin'], $users['christine'], $users['verena']],
            'Floristik | Brautstrauß' => [$users['cornelia'], $users['octavia_Klaus'], $users['claudia']],
            'Food&Beverage | Highlight' => [$users['florian'], $users['christina'], $users['verena']],
            'Fotografie | Soloportrait' => [$users['susan'], $users['octavia_Klaus'], $users['claudia']],
            'Fotografie | Brautpaarportrait' => [$users['doro'], $users['octavia_Klaus'], $users['claudia']],
            'Fotografie | Brautpaarportrait Landschaft' => [$users['tom'], $users['octavia_Klaus'], $users['markus']],
            'Fotografie | Momentaufnahme' => [$users['doro'], $users['cornelia'], $users['martin']],
            'Fotografie | Destination Wedding' => [$users['tom'], $users['susan'], $users['christine']],
            'Fotografie | Hochzeitsreportage' => [$users['florian'], $users['christina'], $users['verena']],
            'Fotografie | Engagement Shooting' => [$users['cornelia'], $users['octavia_Klaus'], $users['claudia']],
            'Freie Trauung' => [$users['martin'], $users['christina'], $users['christine']],
            'Green Wedding' => [$users['markus'], $users['christina'], $users['verena']],
            'Hochzeitsplanung' => [$users['doro'], $users['susan'], $users['octavia_Klaus']],
            'Location | Historisch' => [$users['florian'], $users['martin'], $users['verena']],
            'Location | Outdoor' => [$users['tom'], $users['florian'], $users['christina']],
            'Location | Special' => [$users['doro'], $users['cornelia'], $users['claudia']],
            'Mitarbeiter:in des Jahres' => [$users['tom'], $users['markus'], $users['claudia']],
            'Newcomer' => [$users['claudia'], $users['martin'], $users['cornelia']],
            'Outfit | Braut' => [$users['susan'], $users['christina'], $users['christine']],
            'Outfit | Bräutigam' => [$users['florian'], $users['christine'], $users['verena']],
            'Papeterie | Highlight' => [$users['tom'], $users['cornelia'], $users['octavia_Klaus']],
            'Papeterie | Gesamtkonzept' => [$users['doro'], $users['martin'], $users['christina']],
            'Ringdesign' => [$users['florian'], $users['martin'], $users['christina']],
            'Styled Shoot Team' => [$users['cornelia'], $users['christina'], $users['octavia_Klaus']],
            'Patisserie | Tortendesign' => [$users['susan'], $users['florian'], $users['verena']],
            'Patisserie | Highlight' => [$users['susan'], $users['markus'], $users['christine']],
            'Video | Highlight Video' => [$users['doro'], $users['markus'], $users['claudia']],
            'Wedding Design' => [$users['tom'], $users['cornelia'], $users['markus']]
        ];


        foreach ($userGroups as $categoryName => $userEmails) {
            $category = \App\Cat::where('name', $categoryName)->first();
            if ($category) {
                \App\User::whereIn('email', $userEmails)->withTrashed()->get()->each(function ($user) use ($category) {
                    \App\JuryCategoryPermission::firstOrCreate([
                        'cat_id' => $category->id,
                        'user_id' => $user->id,
                    ]);
                });
            }
        }

//        $projects = Project::where('user_id', $id)->WHERE("stat", "!=", '1')->get();

        // add to evaluation process
        $newUserGroup = [
            $users['tom'], $users['doro'], $users['octavia_Klaus'], $users['christine'], $users['claudia'], $users['verena']
        ];

        $users = \App\User::whereIn('email', $newUserGroup)->get();
        foreach ($users as $user) {
            $projects = Project::where('user_id', $user->id)->where('stat', '!=', '1')->get();
            foreach ($projects as $project) {
                FirstRoundEvaluation::updateOrCreate([
                    'jury_id' => $user->id,
                    'project_id' => $project->id,
                ]);
            }
        }
    }
}
