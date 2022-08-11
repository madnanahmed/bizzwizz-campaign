<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use Illuminate\Console\Command;

class ExecuteCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:executecampaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command is used to execute campaign';

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
        $obj = new CronController();
        $obj->executeCampaign();
    }
}
