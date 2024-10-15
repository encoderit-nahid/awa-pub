<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Project;
use App\Mail\ReminderEmail;
use Illuminate\Support\Facades\Mail;
use App\User;
use DB;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $user_id_who_has_projects = Project::pluck('user_id')->toArray();
        $all_users = User::pluck('id')->toArray();
        $user_id_who_has_no_projects = array_diff($all_users, $user_id_who_has_projects);
        $emails = User::whereIn('id', $user_id_who_has_no_projects)->pluck('email')->toArray();
        // dd($emails);
        Mail::to($emails)->send(new ReminderEmail());

    }
}
