
This website is built off the Argon Pro Theme found here: http://argon-dashboard-pro-laravel.creative-tim.com/

# Requirements
Composer: 2
PHP: 8
Node Version: 15.10
Python: 2

# Setting up the project locally

- Clone this project locally using git
- Configure your `.env` file
- run `composer install`
- run `php artisan passport:keys`
- Unfortunately the migrations are broken, you'll need to grab a copy of the dev database from a colleague

If you're on a Mac, you can easily get a local web server going using "Laravel Herd".

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


