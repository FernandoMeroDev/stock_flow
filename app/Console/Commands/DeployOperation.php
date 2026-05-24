<?php

namespace App\Console\Commands;

use App\Models\Product;
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
        $products = Product::all();
        foreach($products as $product){
            if(Str::contains($product->name, 'jhonnie', true)){
                $product->name = str_replace('JHONNIE', 'JOHNNIE', $product->name);
                $product->save();
            }
        }

        // [Old]
        // // Delete old users
        // $users = User::where('id', '>', 1)->get();
        // foreach($users as $user){
        //     $user->delete();
        // }
        // // Create new Roles
        // $managerRole = Role::create(['name' => 'Manager']);
        // $managerRole->givePermissionTo(['products', 'sales', 'cash-boxes', 'providers', 'purchases']);
        // // Create new users
        // $u1 = User::create([
        //     'name' => 'patricia',
        //     'email' => 'patricia@licenciado.app',
        //     'password' => Hash::make('gato')
        // ]);
        // $u1->assignRole('Manager');
        // $u2 = User::create([
        //     'name' => 'erika',
        //     'email' => 'erika@licenciado.app',
        //     'password' => Hash::make('perro')
        // ]);
        // $u2->assignRole('Manager');
        // $u3 = User::create([
        //     'name' => 'leonardo',
        //     'email' => 'leonardo@licenciado.app',
        //     'password' => Hash::make('pato')
        // ]);
        // $u3->assignRole('Vendedor');
        // // Remove products permission for sellers
        // Role::find(4)->revokePermissionTo('products');
    }
}
