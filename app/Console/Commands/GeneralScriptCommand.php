<?php

namespace App\Console\Commands;

use App\Models\BankTypes;
use Illuminate\Console\Command;

class GeneralScriptCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:general-script {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');

        switch($type){
            case "bank_type_migrate":
                $this->bankTypesMigrate();
        }


        
    }

    public function bankTypesMigrate()
    {
        $bank_types = [
            'Poland Company account PLN',
            'Poland Company account Euro',
            'Wise account PLN',
            'Wise account EURO',
            'Wise account GBP',
            'Wise account INR',
            'Wise account USD',
            'India Company account EURO',
            'India Company account INR',
            'London Company account GBP',
        ];

        foreach($bank_types as $bank_type)
        {
            $check = BankTypes::where('name', $bank_type)->first();
            if(!$check)
            {
                BankTypes::create([
                    'name' => $bank_type
                ]);
            }            
        }

        $this->info('General script migrated for bank types.!');
    }
}
