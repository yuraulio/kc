
This website is built off the Argon Pro Theme found here: http://argon-dashboard-pro-laravel.creative-tim.com/

# Requirements
Composer: 2 \
PHP: 8 \
Node Version: 15.10 \
Python: 2 \

# Setting up the project locally using native tools

- Clone this project locally using git
- Configure your `.env` file
- run `composer install`
- run `php artisan passport:keys`
- Unfortunately the migrations are broken, you'll need to grab a copy of the dev database from a colleague

If you're on a Mac, you can easily get a local web server going using "Laravel Herd".

# Setting up the project locally using ddev
1. Install the ddev: https://ddev.readthedocs.io/en/stable/
1. Clone project locally using git;
2. Configure the `.env` file (use an .env.example and .env.dev as a base);
3. Run `ddev start`
4. Run `ddev composer install`
5. Run `ddev artisan passport:keys`
6. For importing database use:
   1. `ddev import-db --file=db.sql` command; 
   2. Or `gzip -dc db.sql.gz | ddev import-db` for archived dump;
7. Compile all assets under root and resources directories:
   1. `ddev npm i`
   2. `ddev npm run dev`
   3. `ddev ssh`, and inside of the ssh session `cd resources/admin && npm i && npm run dev`
8. Quick way to get admin user after importing database:
   1. `ddev artisan tinker`
   2. Execute the following php code: ```User::findOrFail(6)->fill(['email'=>'admin@mail.com', 'password' => Hash::make('123123123')])->save()``` (the admin access will be: `admin@mail.com` / `123123123`);
3. To enable the xdebug: 
   1. Run the following command `ddev xdebug`;
   2. Add the server to the Servers (in PHPStorm):
      1. Server name: `knowcrunch.ddev.site`
      2. Host: `knowcrunch.ddev.site`
      3. Port: `80`
      4. Debugger: `Xdebug`
      5. Use path mappings: `path/to/your/project/root` to `/var/www/html` 
   6. For MacUsers maybe useful to add globally the next: `sudo ifconfig en0 alias 10.254.254.254 255.255.255.0 ` 

# Broad architecture

This project powers the front end of the website, as well as the admin pages.

- Front end: https://knowcrunch.com
- Admin: https://admin.knowcrunch.com (this is a subdomain pointing to the same project)
- Vue admin: https://new.admin.knowcrunch.com 
- React admin (WIP, separate project)

Locally (assuming the app is set to be served from knowcrunch.test)
- Front end: knowcrunch.test
- Laravel Admin: knowcrunch.test/admin
- Vue Admin: admin.knowcrunch.test/admin

## Servers

Servers are managed using ScaleForce, we currently have a production and a development server hosting this application.

The project on the production server is found in `/var/www/webroot/ROOT`

## Admin panels

### Vue Admin panel

- The FE assets needs to be compiled separately
- `cd resources/admin && npm i && npm run dev`

# SDLC

- Tasks are written and assigned on Trello
- Developer picks up and works on task
- Ask for feedback on card?
- Create PR (?)
- Merge to master (auto deploys to dev environment)
- Testing/approval (?)
- Deploy to production (currently deployed from master branch)


# Testing Payments

Stripe payments can be tested locally, some configuration is required:

1. Grab a key and secret key from Knowcrunch's Stripe dashboard: https://dashboard.stripe.com/test/apikeys
2. Update the `STRIPE_KEY` and `STRIPE_SECRET` in your `.env` file
3. The keys are also stored in the database, here's something you can paste into `php artisan tinker` to configure them:

```php
PaymentMethod::query()->update([
    'test_processor_options' => encrypt(json_encode([
        'key' => env('STRIPE_KEY'),
        'secret_key' => env('STRIPE_SECRET'),
    ]))
]);
```
# Tiny MCE

Tiny MCE is used as a WYSIWYG editor. We use the self-hosted version as it's free - and host it by simply downloading the
latest version from the Tiny MCE website and placing it in the public directory.

Currently we use Tiny MCE version 6, though 7 has been released. 
