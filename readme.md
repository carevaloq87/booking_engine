## Booking Engine

Booking Engine is a web application that allow LHO users to book appointments for clients, it also allow offices / service providers to manage their service availability through the year.

Services availability can be defined by day of the year and by hours during the day.

# Installation

## Git clone

`git clone https://github.com/carevaloq87/booking_engine`

## Setup Laravel Project

Generate dependencies (Vendor folder, autoload, etc...)
> `composer install`

Create .env variables from the step `Setup Laravel .env variables` in this tutorial [here](https://github.com/carevaloq87/booking_engine#setup-laravel-env-variables)

Generate your own project key
> `artisan key:generate`

Install Laravel Mix
> `npm install laravel-mix --save-dev`

If you want to compile JS files just run, just remenber to compilem according to the environment that you are working at.
> `npm run development`

### Setup Laravel .env variables

In order to keep configuration values centralized all of those values should be stores inside a .env file located on the root folder of your project.

The values that are relevant for us are:

*Basic Laravel settings*

* `APP_NAME=` Name of your application use quotes if you need to put spaces
* `APP_ENV=` Local or production options should be selected
* `APP_KEY=` This key should be generated when running the command `artisan key:generate`
* `APP_DEBUG=` This should be only true when you are debuggin your application ie. your development server.
* `APP_URL=` Set the local, dev or prod URL here


* `DB_CONNECTION=` Type of connection that you will use with your database ie. mysql
* `DB_HOST=` Address that will be used to connect with your database ie. 127.0.0.1
* `DB_PORT=` Port that will be used to connect with your database ie. 3306
* `DB_DATABASE=` Database name
* `DB_USERNAME=` User with privilege to access your database
* `DB_PASSWORD=` User's password to access your database
* `DB_PREFIX=BE_` Prefix for tables in the DB

* `SIMPLESML_SP=` Indicate Service provider variable for the Simple SAML library
* `ORBIT_URL=` indicate Orbit/LHO URL here
* `TYTINYMCE_KEY=` You should get this key from their Tiny MCE website in order to use it on the project

## Status

Currently the application is stable in beta version and is being tested.

## Security Vulnerabilities

If you discover a security vulnerability within the project or its library, please send an e-mail to Christian Arevalo via [christian.arevalo@vla.vic.gov.au](mailto:christian.arevalo@vla.vic.gov.au). All security vulnerabilities will be promptly addressed.

## License

The Booking Engine is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
