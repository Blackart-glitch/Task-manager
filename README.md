# Task Manager Readme

Author: Daniel Ajiboye  
Email: danielajiboye@gmail.com  
LinkedIn: [Daniel Boyé](https://www.linkedin.com/in/daniel-boy%C3%A9-58366a1b4/)  
Date of Creation: June 15, 2023

This is a PHP code for a simple task manager application. The code allows users to perform various operations such as creating new tasks, editing existing tasks, deleting tasks, and viewing task summaries. Below, you will find an overview of the code structure, its functionality, and important variables used.

## Table of Contents
- [Prerequisites](#prerequisites)
- [Usage](#usage)
- [Database Schema](#database-schema)
- [Contributing](#contributing)
- [License](#license)

## Prerequisites
Before running this code, ensure that you have the following:
- PHP installed on your system.
- A database connection set up. The code assumes that the database connection details are stored in a separate file called `dbconnect.php`, which should be included.


## Usage
1. Set up a database connection by creating a file named `dbconnect.php` and including it in the code.
2. Ensure that PHP is installed on your

 system.
3. Run the code by accessing it through a web server.

### Database Schema

#### Table: notifications
| Column       | Type         | Description                           |
|--------------|--------------|---------------------------------------|
| id           | int(11)      | Unique identifier for each notification (Primary Key) |
| message      | text         | Textual message of the notification    |
| created_at   | timestamp    | Timestamp indicating the creation time of the notification (Default: current timestamp) |

#### Table: tasks
| Column         | Type         | Description                           |
|----------------|--------------|---------------------------------------|
| id             | int(11)      | Unique identifier for each task (Primary Key) |
| title          | varchar(255) | Title of the task                      |
| description    | text         | Detailed description of the task       |
| deadline       | date         | Deadline for completing the task       |
| status         | varchar(20)  | Current status of the task             |
| priority       | int(11)      | Priority level of the task (optional)  |
| assigned_user  | varchar(11)  | Email or username of the assigned user |

#### Table: users
| Column       | Type         | Description                           |
|--------------|--------------|---------------------------------------|
| id           | int(11)      | Unique identifier for each user (Primary Key) |
| username     | varchar(255) | Username of the user                   |
| password     | varchar(255) | Password of the user                   |
| email        | varchar(255) | Email address of the user              |

### Database Diagram

```
task_manager
├── notifications
│   ├── id (Primary Key)
│   ├── message
│   └── created_at
├── tasks
│   ├── id (Primary Key)
│   ├── title
│   ├── description
│   ├── deadline
│   ├── status
│   ├── priority
│   └── assigned_user
└── users
    ├── id (Primary Key)
    ├── username
    ├── password
    └── email
```

Let me know if you need any further assistance!

## Contributing
Contributions are welcome! Please fork this repository and submit a pull request.

## License
This project is licensed under the [MIT License](LICENSE).

# Code Explanation - DASHBOARD.PHP

This document provides a detailed explanation of the `dashboard.php` code. It explains the purpose and functionality of each statement in the code.

```php
<?php
include('dbconnect.php');
```
- This line includes the `dbconnect.php` file, which establishes a connection to the database.

```php
if (empty($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
```
- This code block checks if the user is already logged in. If the session variable `$_SESSION["username"]` is empty, it means the user is not logged in. In that case, the code redirects the user to the login page (`login.php`) and exits the script.

```php
$sql = "SELECT * FROM tasks";
$result = mysqli_query($conn, $sql);
$tasks = array();
while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row;
}
```
- These lines execute an SQL query to fetch all the rows from the `tasks` table in the database. The retrieved rows are stored in the `$tasks` array.

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert-submit'])) {
    // Get the form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $status = "New";
    // Prepare the SQL statement for insertion
    $sql = "INSERT INTO tasks (title, description, deadline, status) VALUES ('$title', '$description', '$deadline', '$status')";
    // Execute the SQL statement
    if (mysqli_query($conn, $sql)) {
        // Insertion successful
        echo "New task added successfully!";
    } else {
        // Error occurred
        echo "Error: " . mysqli_error($conn);
    }
}
```
- This code block checks if the form is submitted with the name `insert-submit` using the POST method. If true, it retrieves the form data (title, description, deadline) and prepares an SQL statement for inserting a new task into the `tasks` table. It then executes the SQL statement using `mysqli_query()`. If the insertion is successful, it displays a success message; otherwise, it displays an error message.

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit-submit'])) {
    // Get the form data
    $taskId = $_POST['edit-title'];
    $description = $_POST['edit-description'];
    $deadline = $_POST['edit-deadline'];
    // Prepare the SQL statement for update
    $sql = "UPDATE tasks SET description='$description', deadline='$deadline' WHERE id='$taskId'";
    // Execute the SQL statement
    if (mysqli_query($conn, $sql)) {
        // Update successful
        echo "<p>Task updated successfully!</p>";
    } else {
        // Error occurred
        echo "Error: " . mysqli_error($conn);
    }
}
```
- This code block checks if the form is submitted with the name `edit-submit` using the POST method. If true, it retrieves the form data (task ID, description, deadline) and prepares an SQL statement for updating an existing task in the `tasks` table. It then executes the SQL statement using `mysqli_query()`. If the update is successful, it displays a success message; otherwise, it displays an error message.

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete-submit'])) {
    // Get the task ID to delete
    $taskId = $_POST['task-id'];
   

 // Prepare the SQL statement for deletion
    $sql = "DELETE FROM tasks WHERE id='$taskId'";
    // Execute the SQL statement
    if (mysqli_query($conn, $sql)) {
        // Deletion successful
        echo "<p>Task deleted successfully!</p>";
    } else {
        // Error occurred
        echo "Error: " . mysqli_error($conn);
    }
}
```
- This code block checks if the form is submitted with the name `delete-submit` using the POST method. If true, it retrieves the task ID to be deleted and prepares an SQL statement for deleting the corresponding task from the `tasks` table. It then executes the SQL statement using `mysqli_query()`. If the deletion is successful, it displays a success message; otherwise, it displays an error message.

```php
if (isset($_GET['logout'])) {
    // Destroy the session and redirect to login page
    session_destroy();
    header("Location: login.php");
    exit();
}
```
- This code block checks if the URL parameter `logout` is set. If true, it destroys the session and redirects the user to the login page (`login.php`) before exiting the script.

The remaining code is HTML markup that displays the dashboard interface. It includes navigation elements, modals for adding, editing, and deleting tasks, task summary information, recent activity, and a task list table. The PHP code within the HTML is used to dynamically populate the task list, task summary, and recent activity based on the data fetched from the database.

This completes the explanation of the `dashboard.php` code.

# Code Explanation - LOGIN.PHP

This document provides a detailed explanation of the `login.php` code. It explains the purpose and functionality of each statement in the code.

```php
<?php
include('dbconnect.php');
```
- This line includes the `dbconnect.php` file, which establishes a connection to the database.

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the username and password from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];
```
- This code block checks if the form is submitted using the POST method. If true, it retrieves the values of the `username` and `password` fields from the login form.

```php
$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
$result = mysqli_query($conn, $sql);
```
- These lines execute an SQL query to select a user from the `users` table in the database, matching the provided username and password. The query is constructed using the retrieved `username` and `password` values. The result of the query is stored in the `$result` variable.

```php
if (mysqli_num_rows($result) == 1) {
    // Assuming the validation is successful, store the user data in the session
    $_SESSION["username"] = $username;

    // On successful login, redirect to dashboard.php
    header("Location: dashboard.php");
    exit();
} else {
    // Handle the error case
    $error = "Invalid username or password.";
    exit();
}
```
- This code block checks if the number of rows returned by the SQL query is equal to 1, indicating that a user with the provided username and password exists in the database. If true, it assumes that the validation is successful and proceeds to store the `username` value in the session variable `$_SESSION["username"]`. It then redirects the user to the dashboard page (`dashboard.php`) using the `header()` function and exits the script. If the number of rows is not equal to 1, it sets an error message in the `$error` variable and exits the script.

The remaining code is HTML markup that displays the login form. It includes input fields for the username and password, a login button, and a link to the signup page. The PHP code within the HTML is used to display an error message if the login validation fails.

This completes the explanation of the `login.php` code.

# Code Explanation - SIGNUP.PHP

This document provides a detailed explanation of the `signup.php` code. It explains the purpose and functionality of each statement in the code.

```php
<?php
include('dbconnect.php');
```
- This line includes the `dbconnect.php` file, which establishes a connection to the database.

```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the username, password, and email from the sign-up form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
```
- This code block checks if the form is submitted using the POST method. If true, it retrieves the values of the `username`, `password`, and `email` fields from the sign-up form.

```php
$sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
$result = mysqli_query($conn, $sql);
```
- These lines execute an SQL query to insert a new user into the `users` table in the database. The query inserts the provided `username`, `password`, and `email` values into the respective columns. The result of the query is stored in the `$result` variable.

```php
if ($result) {
    $_SESSION["username"] = $username;

    // On successful sign-up, redirect to dashboard.php
    header("Location: dashboard.php");
    exit();
} else {
    // Handle the error case
    $error = "Error: " . mysqli_error($connection);
    header("Location: signup.php?error=$error");
    exit();
}
```
- This code block checks if the `$result` is true, indicating that the user insertion was successful. If true, it stores the `username` value in the session variable `$_SESSION["username"]`. It then redirects the user to the dashboard page (`dashboard.php`) using the `header()` function and exits the script. If the `$result` is false, it sets an error message in the `$error` variable by retrieving the MySQL error using `mysqli_error($connection)`. It then redirects the user back to the sign-up page (`signup.php`) with the error message as a query parameter in the URL.

The remaining code is HTML markup that displays the sign-up form. It includes input fields for the username, password, and email, a sign-up button, and a link to the login page. The PHP code within the HTML is used to display an error message if an error occurs during the sign-up process.

This completes the explanation of the `signup.php` code.

# Code Explanation - TASKVIEW.PHP

This document provides a detailed explanation of the `taskview.php` code. It explains the purpose and functionality of each statement in the code.

```php
<?php
include('dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signup-submit'])) {
        // Retrieve the form data
        $email = $_POST["email"];
        $taskId = $_POST["task-id"];
```
- This code block checks if the form is submitted using the POST method and if the "signup-submit" button is clicked. If true, it retrieves the values of the `email` and `task-id` fields from the form.

```php
        $checkQuery = "SELECT assigned_user FROM tasks WHERE id = '$taskId'";
        mysqli_real_query($conn, $checkQuery);
        $result = mysqli_store_result($conn);
        $row = mysqli_fetch_assoc($result);

        if ($row["assigned_user"] !== null) {
            // User already exists, display error message
            $error = "A user is already assigned to this task.";
            $message = "Error: $email failed to sign up for task ($taskId). $error";
        } else {
            // Update the assigned_user column in the tasks table
            $updateQuery = "UPDATE tasks SET status = 'In Progress', assigned_user = '$email' WHERE id = '$taskId'";
            mysqli_real_query($conn, $updateQuery);

            // Insert a notification into the notifications table
            $message = "$email has signed up for task ($taskId)";
        }

        $createdAt = date('Y-m-d H:i:s');
        $insertQuery = "INSERT INTO notifications (message, created_at) VALUES ('$message', '$createdAt')";
        mysqli_real_query($conn, $insertQuery);
    }
```
- This code block handles the sign-up functionality when the "Sign Up" button is clicked. It first checks if there is already a user assigned to the task by querying the `tasks` table. If a user is already assigned (`$row["assigned_user"]` is not null), it sets an error message. Otherwise, it updates the `assigned_user` column of the task with the provided email and sets the status to "In Progress". It also inserts a notification into the `notifications` table with the sign-up information.

```php
    if (isset($_POST['update-status-submit'])) {
        $email = $_POST["email"];
        $taskId = $_POST["task-id"];
        $status = $_POST["status"];

        // Update the assigned_user column in the tasks table
        $updateQuery = "UPDATE tasks SET status = '$status', assigned_user = '$email' WHERE id = '$taskId'";
        mysqli_real_query($conn, $updateQuery);

        // Insert a notification into the notifications table
        $message = "$email has updated the status of task ($taskId) to ($status)";
        $createdAt = date('Y-m-d H:i:s');
        $insertQuery = "INSERT INTO notifications (message, created_at) VALUES ('$message', '$createdAt')";
        mysqli_real_query($conn, $insertQuery);
    }
    header('locattion: taskview.php');
}
```
- This code block handles the update status functionality when the "Update" button is clicked. It retrieves the values of the `email`, `task-id`, and `status` fields from the form. It then updates the `status` and `assigned_user` columns of the task with the provided email and status. It also inserts a notification into the `notifications` table with the status update information.

The remaining code is HTML markup that displays the task view page

. It includes a table that shows the list of tasks with their respective ID, title, description, and status. For each task, there are "Sign Up" and "Update Status" buttons that open modals for signing up and updating the status of the task, respectively.

The JavaScript code within the HTML is used to retrieve the task data when the buttons are clicked and populate the respective fields in the modals.

This completes the explanation of the `taskview.php` code.
