<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class UpdateExpiredAccounts extends Command
{
    protected $signature = 'accounts:update-expired';
    protected $description = 'Update accounts that have expired.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting the expired accounts update process.');

        // Current date
        $now = Carbon::now();

        // Update expired accounts
        $updatedCount = User::where('account_expires', '<', $now)
            ->update([
                'account_active' => false,
                'account_expires' => null,
            ]);

        $this->info("Expired accounts have been updated. Number of accounts updated: $updatedCount");
    }
}
