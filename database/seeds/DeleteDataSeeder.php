<?php

use Illuminate\Database\Seeder;

class DeleteDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cats = [
            'Ausstattung, Dekoartikel & -Verleih',
            'Braut & Herrenmode',
            'Entertainment | Show-Act',
            'Entertainment | Kinderanimation',
            'Fotografie | Ambiente',
            'Innovation', 'Tortendesign | Sweet Table'
        ];

        info(\App\Cat::whereIn('name', $cats)->pluck('id'));
//        \App\Cat::whereIn('name', $cats)->delete();

    }
}
