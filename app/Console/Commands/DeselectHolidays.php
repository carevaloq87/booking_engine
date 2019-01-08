<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use Illuminate\Console\Command;

class DeselectHolidays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deselect:holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deselect days from posible availability selected during holidays';

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
        $holiday_obj = new Holiday();
        $holiday_obj->deselectHolidays();
    }
}
