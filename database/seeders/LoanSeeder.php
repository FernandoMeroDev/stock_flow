<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Loans\Loan;
use App\Models\Loans\LoanPresentation;
use App\Models\Presentation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $users_max_id = $users->count() - 1;
        $clients = Client::all();
        $clients_max_id = $clients->count() - 1;
        $presentations = Presentation::all();
        $presentations_max_id = $presentations->count() - 1;
        for($i = 0; $i < 10; $i++){
            $lender = $users->get($this->ran_id($users_max_id));
            $client = $clients->get($this->ran_id($clients_max_id));
            $guarantor = $users->get($this->ran_id($users_max_id));
            $loan = Loan::create([
                'lender_id' => $lender->id,
                'client_id' => $client->id,
                'guarantor_id' => $guarantor->id,
                'warehouse_id'=> 1,
                'dead_line' => now()->subDays(2)->addDays($i + 1),
                'finished' => false,
                'created_by_id' => 1,
            ]);
            for($j = 0; $j < 10; $j++){
                $presentation = $presentations->get($this->ran_id($presentations_max_id));
                LoanPresentation::create([
                    'loan_id' => $loan->id,
                    'presentation_id' => $presentation->id,
                    'count' => 10,
                    'unitary_price' => $presentation->price,
                ]);
            }
        }
    }

    protected function ran_id(int $max): int
    {
        return random_int(1, $max);
    }
}
