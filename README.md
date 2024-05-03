## DonBosco Projects 


## Description

It is a DonBosco project management system in the Middle East

## Installation

To get started with the project, follow these steps:

1. Clone the project repository:

   ```bash
   git clone https://github.com/nour341/DonBosco.git
   ```

2. Install the project dependencies using Composer:

   ```bash
   composer install
   ```

3. Run the database migrations:

   ```bash
   php artisan migrate
   ```

4. Seed the database with initial data:

   ```bash
   php artisan db:seed --class=CountrySeeder
   php artisan db:seed --class=UserSeeder
   php artisan db:seed --class=CenterSeeder
   php artisan db:seed --class=ProjectSeeder
   ```

5. run server:

   ```bash
    php artisan serv
   ```

## Usage

### Existing users
To register for each account, follow these steps:

**Provincial Account:**
```angular2html
email = provincial@gmail.com
password = 12345678
```

**Local Coordinator Account:**
```angular2html
email = local@gmail.com
password = 12345678
```

**Financial Management Account:**
```angular2html
email = financial@gmail.com
password = 12345678
```

**Employ Account:**
```angular2html
email = employ@gmail.com
password = 12345678
```


