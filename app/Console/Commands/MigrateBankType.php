<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateBankType extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-bank-type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate Bank Type insert/update';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $bank_names = [
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

        foreach($bank_names as $bank)
        {

        }

        
    }
}
