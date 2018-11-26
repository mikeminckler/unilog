<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ContactsSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unilog:sync-contacts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncs the contacts table from myschool db to unilog';

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
        echo shell_exec('mysqldump -u '.env('DB_USERNAME').' -p'.env('DB_PASSWORD').' -h moodle.brentwood.bc.ca myschool_brentwood contacts | mysql -u '.env('DB_USERNAME').' -p'.env('DB_PASSWORD').' noto_brentwood');
        \DB::statement('ALTER TABLE contacts CHANGE COLUMN `id` `id_old` BINARY(16) NOT NULL , CHANGE COLUMN `user_id` `id` MEDIUMINT(8) UNSIGNED NOT NULL;');
    }
}
