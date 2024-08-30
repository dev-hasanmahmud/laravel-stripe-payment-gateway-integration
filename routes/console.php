<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('account_expiration:alert')->dailyAt('02:00'); // daily at 2 AM
Schedule::command('accounts:update-expired')->dailyAt('02:00'); // daily at 2 AM
