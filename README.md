## DonBosco Projects 
![img.png](public%2Fimages%2Fimg.png)

---

## System Overview

- **Users**: Users can log in and interact with projects and tasks.
- **Projects**: Each project can contain multiple tasks and has a dedicated budget.
- **Tasks**: Each task is associated with a project and has various statuses that are tracked.
- **Invoices**: Invoices are managed for each task or project and include details such as pricing and duration.
- **Items**: Used in budgets and include details like prices and quantities.
- **Status**: A status is assigned to each task to track its progress.

---

## Task Statuses

- **pending**: "قيد الانتظار" - Task is defined but work has not yet started.
- **in_progress**: "قيد التنفيذ" - Work on the task has started.
- **on_hold**: "معلقة" - Work on the task is temporarily paused.
- **under_review**: "مراجعة" - Task is completed and under review.
- **completed**: "مكتملة" - Task is completed and approved.
- **closed**: "مغلقة" - Task is completed and no further action is needed.
- **cancelled**: "ملغاة" - Task is cancelled and will not be completed.

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
   php artisan db:seed --class=ItemSeeder 
   php artisan db:seed --class=TaskSeeder 
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


