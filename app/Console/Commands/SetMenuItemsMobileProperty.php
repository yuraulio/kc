<?php

namespace App\Console\Commands;

use CodexShaper\Menu\Models\MenuItem;
use Illuminate\Console\Command;

class SetMenuItemsMobileProperty extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SetMenuItemMobileToTrue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set all menu items mobile property to true. This will show them on mobile. I am using existing field "middleware".';

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
     * @return int
     */
    public function handle()
    {
        $menuItems = MenuItem::get();
        foreach ($menuItems as $item) {
            $item->middleware = "1";
            $item->save();
        }
    }
}
