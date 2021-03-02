### How to use
`composer install`

`cp .env.example .env`

`php artisan key:generate`

`php artisan migrate`

#### Import database
setting path in `app/Console/Commands/ImportAdministrativeDivision.php`

`php artisan import:administrative`

#### Export database
`php artisan export:administrative`
