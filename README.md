# Task Management System

A Laravel 12 application with Admin and Employee roles. Admins manage projects and tasks; employees view and update their assigned tasks. Spatie Laravel Permission handles role-based access.

## MySQL Setup (Windows)

Requirements: PHP 8.2+, Composer, and a running MySQL server. Run the commands from the Taskflow project folder.

### 1. Install Laravel and Spatie dependencies

```powershell
composer install
```

Laravel and Spatie Laravel Permission are already included in this repository's Composer dependencies.

### 2. Create and configure `.env`

```powershell
Copy-Item .env.example .env
notepad .env
```

In `.env`, set the application and database values below. Keep the other settings already in the file, and replace `your-mysql-password` with your MySQL password.

```env
APP_NAME="Task Management System"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=crmdb
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Create the MySQL database

```powershell
mysql -u root -p -e "CREATE DATABASE crmdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Enter your MySQL password when prompted. Alternatively, run this SQL in MySQL Workbench:

```sql
CREATE DATABASE crmdb CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Generate the Laravel application key

```powershell
php artisan key:generate
```

### 5. Run database migrations

```powershell
php artisan migrate
```

### 6. Seed roles, permissions, users, and sample data

```powershell
php artisan db:seed
```

### 7. Start the application

```powershell
php artisan serve
```
Open `http://127.0.0.1:8000` and sign in.

## Demo Login

All demo accounts use `Password123!`.

| Role | Email |
| --- | --- |
| Admin | `admin@task.local` |
| Employee | `jordan@task.local` |
| Employee | `sam@task.local` |

Change these passwords before using the application outside a local evaluation environment.

## Admin Features

- Create, edit, and delete projects.
- Create tasks and assign them to employees.
- Edit task details, project, assignee, priority, status, and due date.
- Delete tasks.
- Search and filter tasks by text, status, and priority.
- View project and task statistics on the dashboard.

Deleting a project also deletes its tasks.

## Employee Features

- View only tasks assigned to the signed-in employee.
- Open task details and update task status without refreshing the page.
- Employees cannot access Admin project/task management or other employees' task records.

## API

The JSON endpoints use the signed-in Laravel session. Send the session cookie and `Accept: application/json`; include the session CSRF token in the `X-CSRF-TOKEN` header for `PATCH` requests.

| Method | Endpoint | Description |
| --- | --- | --- |
| `GET` | `/api/tasks` | List permitted tasks (paginated) |
| `GET` | `/api/tasks/{task}` | Show a permitted task |
| `PATCH` | `/api/tasks/{task}/status` | Update status (`pending`, `in_progress`, or `completed`) |

Admins can access all tasks. Employees are restricted to their own assigned tasks.