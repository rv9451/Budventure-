# Taskflow

Taskflow is a Laravel 12 task management application with Admin and Employee roles. Admins manage projects and team tasks. Employees can view and update only their assigned tasks. Self-registration is disabled; demo users are created by the seeder.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js and npm
- SQLite, MySQL, or PostgreSQL

Spatie Laravel Permission is already declared in `composer.json`. `composer install` installs it with the other PHP dependencies.

## Installation

Run these steps from the repository root.

### 1. Install PHP dependencies

```sh
composer install
```

### 2. Create your environment file

PowerShell:


### 3. Configure the database

For SQLite, create the database file and set these values in `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=crmdb
```
For MySQL or PostgreSQL, configure `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` in `.env` instead.


### 5. Create database tables

```sh
php artisan migrate
```

### 7. Add roles, permissions, demo users, and sample records

```sh
php artisan db:seed
```

`DatabaseSeeder` runs `RolePermissionSeeder` and creates the demo Admin, Employees, project, and tasks. Running the seeder again is safe.

### 8. Compile frontend assets

```sh
npm run build
```

### 9. Start the development server

```sh
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