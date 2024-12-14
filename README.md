# WattoDo

**WattoDo** is a powerful to-do list application designed to help you efficiently manage tasks and plans. Developed as the final project for EDMA, this system demonstrates the implementation of several key requirements:

## Requirements Fulfilled

- **Medium-Large Scale System**: WattoDo is designed to handle extensive tasks and plans effectively.
- **MVC Concept**: The application is structured using the Model-View-Controller architecture for better organization and scalability.
- **Database Backup**: Users can back up their tasks and plans, ensuring data safety and reliability.
- **AJAX Requests**: Smooth and asynchronous interactions provide a seamless user experience.
- **Password Hashing with Bcrypt**: User passwords are securely stored using the bcrypt hashing algorithm, ensuring data security and protection against unauthorized access.

## Project Members

- Lance Javate
- Laimarie Bantuas
- Bernie Dungog
- Harvey Hector Degamo
- Joey Duhina
- Rjay Fabrigar

## Features

- **Task Creation**: Quickly add tasks to your list.
- **Task Management**: Mark tasks as complete, edit, or delete them effortlessly.
- **Plan Organization**: Organize tasks into categories or plans for better clarity.
- **User-Friendly Interface**: Designed for ease of use and efficiency.
- **Database Backup**: Ensure data safety by creating backups of your tasks and plans.

## Setup and Usage

To run WattoDo locally, follow these steps:

### Prerequisites
- Ensure you have PHP installed on your system.
- A terminal or command-line tool to run commands.

### Steps

1. **Download the Project Files**
   Obtain the project files from your provided source or shared directory and navigate to the project folder:
   ```bash
   cd wattodo
   ```

2. **Run the Local Server**
   Use PHP's built-in server to host the application locally:
   ```bash
   php -S localhost:8000
   ```

   This command starts the server, making WattoDo accessible at [http://localhost:8000](http://localhost:8000).

3. **Backup Your Data**
   For backing up your tasks and plans, use the `backup.php` script:
   ```bash
   php backup.php
   ```
   The script creates a backup file containing your current tasks and plans, ensuring no data is lost.

4. **Restore Your Data**
   To restore a backup, use the `restore.php` script. This will prompt you to choose between restoring to a new database or an existing database:
   ```bash
   php restore.php
   ```
   Follow the on-screen instructions to choose a backup file, select the type of restoration, and provide the name of the database.

   #### Example Demo:
   ```bash
   php restore.php
   ```
   **Output:**
   ```
   Available backup files:
   [3] backup_2024-12-14_14-52-14.sql
   [4] backup_2024-12-14_15-31-55.sql
   Enter the number of the backup file to restore (or enter / to cancel): 3
   Would you like to:
   [1] Restore to a new database
   [2] Restore to an existing database
   [/] Cancel
   Enter your choice: 2
   Enter the name of the existing database to restore to: restore_db
   Restoring to the existing database: restore_db
   Database `restore_db` created successfully.
   Database restored successfully from: C:\Users\HomePC\Desktop\wattodo/storage/backup_2024-12-14_14-52-14.sql
   Database restoration to `restore_db` completed successfully.
   ```

### Accessing the Application
Open your preferred web browser and go to:

[http://localhost:8000](http://localhost:8000)

Here, you can interact with the WattoDo interface to create, manage, and organize your tasks.

---

Enjoy managing your tasks efficiently with **WattoDo**!
