<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductWarehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DeployOperation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:deploy-operation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Versatile command. Execute Deploy operations needed by current deploy context.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
