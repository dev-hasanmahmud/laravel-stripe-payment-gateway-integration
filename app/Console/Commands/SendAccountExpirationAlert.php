<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\AccountExpirationAlert;
use Carbon\Carbon;

class SendAccountExpirationAlert extends Command
{
    protected $signature = 'account_expiration:alert';
    protected $description = 'Send alert emails to users before account Expiration.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting the alert process.');

        $alertDate = Carbon::now()->addDays(2)->toDateString();
        $this->info("Alert Date: $alertDate");

        $users = User::whereDate('account_expires', $alertDate)->get();
        $this->info("Number of users found: " . $users->count());

        foreach ($users as $user) {
            $this->info("Sending alert to user ID: {$user->id}");
            $user->notify(new AccountExpirationAlert($user));
        }

        $this->info('Alert emails sent successfully.');
    }

}

