<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        // Old
        // $this->call('permission:create-permission', [
        //     'name' => 'providers'
        // ]);
    }
}
