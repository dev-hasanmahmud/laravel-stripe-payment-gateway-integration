## Skill Evaluation Test: Has been implemented assignment full functionality
Task Instruction:
The task is to create a simple login and registration system where users can register and log in.
After logging in, users need to pay USD 10 per month to activate their account. After the
activation period expires, the user will be deactivated automatically. Implement some
restrictions while logging in, such as if a user enters the wrong credentials 3 times, they need to
wait 5 minutes to log in again.

Requirements:
● Use custom authentication.
● An automatic alert email should be sent to the user 2 days before account deactivation.
● Use Stripe for the payment gateway to activate accounts.
● Provide a report of monthly payments in each user's account.
● Restrict wrong login attempts.

## Usage
Tech Stack: Laravel 11, MySQL, Bootstrap

- Clone the repository with `git clone`
- Copy `.env.example` file to `.env` and edit database credentials there
- Need to setup database, stripe payment and mail credentials
- Run `composer install or composer update`
- Run `php artisan key:generate`
- Run `php artisan migrate --seed`
- Run `php artisan serve`

Test instructions:
- Localhost admin login: http://localhost:8000/
  - Email: admin@admin.com
  - Pass: password
  
- Credentials:
  - Payment https://dashboard.stripe.com/test/dashboard/
    - Email: sojohoc207@ndiety.com
  	- Pass: S!@#ojohoc207@

  	- sojohoc207@ndiety.com
    - 4242 4242 4242 4242
	  - 12 / 25
	  - 222
	  - Sojo Hoc
	  - Bangladesh

- Mail https://mailtrap.io/
	- Email: bopeha4627@kwalah.com
	- Pass: bopeha4627@kwalah.com
  - Command for Send Account Expiration Alert Test: php artisan account_expiration:alert
  - Command for Update Expired Accounts Test: php artisan accounts:update-expired