# Uber Pigeon

## Installation

### Install dependencies

```
composer install
php artisan passport:keys
```

If there is error regards missing php extension(s), try to google and install it/them ;)

### Migration & Seed

Run

```
php artisan migrate
php artisan db:seed
```

Alternatively, you can run following command. But remember every thing in the databse will be replaced by seed data

```
php artisan migrate:fresh --seed
```

### Create oauth client

Run:

```
php artisan passport:client --personal
```

Enter name and enter.