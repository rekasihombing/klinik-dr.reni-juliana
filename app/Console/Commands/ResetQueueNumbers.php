<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetQueueNumbers extends Command
{
    protected $signature = 'queue:reset';
    protected $description = 'Reset queue numbers for past appointments (disabled)';

    public function handle()
    {
        // Tidak melakukan apa-apa agar nomor antrian lama tetap tersimpan
        $this->info('Queue reset is disabled to preserve historical queue numbers.');
    }
}