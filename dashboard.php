<?php
include('dbconnect.php');

// Check if the user is already logged in
if (empty($_SESSION["username"])) {
    // User is not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM tasks";
$result = mysqli_query($conn, $sql);
$tasks = array(); // Initialize an empty array to store the rows

while ($row = mysqli_fetch_assoc($result)) {
    $tasks[] = $row; // Append each row to the $data array
}

// Check if the form is submitted from the "New Task" modal
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

// Check if the form is submitted from the "Edit Task" modal
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

// Check if the form is submitted from the "Delete Task" modal
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

// Check if logout request is submitted
if (isset($_POST['logout-submit'])) {
    // Destroy all session data
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
    exit();
}

?>
<html>

<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="bootstrap-5.0.2-dist\css\\bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark p-3">
        <a class="navbar-brand" href="#">Task Manager</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdownMenuLink" data-toggle="dropdown">
                        Tools
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuLink">
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#newTaskModal">New Task</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#editTaskModal">Edit Task</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-toggle="modal" data-target="#deleteTaskModal">Delete Task</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <form method="post">
                        <button type="submit" class="nav-link btn btn-link" name="logout-submit">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
        <!-- New Task Modal -->
        <div class="modal fade" id="newTaskModal" tabindex="-1" role="dialog" aria-labelledby="newTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newTaskModalLabel">New Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="insert" method="post" name="insert" action="">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter task title">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter task description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="deadline">Deadline</label>
                                <input type="date" class="form-control" id="deadline" name="deadline">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="insert-submit">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Task Modal -->
        <div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaskModalLabel">Edit Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="editTitle">Title</label>
                                <select class="form-control" id="editTitle" name="edit-title" required>
                                    <option value="" selected disabled>SELECT A TASK</option>
                                    <?php
                                    foreach ($tasks as $task) {
                                        echo "<option value='" . $task['id'] . "'>" . $task['title'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editDescription">Description</label>
                                <textarea class="form-control" id="editDescription" name="edit-description" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editDeadline">Deadline</label>
                                <input type="date" class="form-control" name="edit-deadline" id="editDeadline" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" name="edit-submit">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <!-- Delete Task Modal -->
        <div class="modal fade" id="deleteTaskModal" tabindex="-1" role="dialog" aria-labelledby="deleteTaskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTaskModalLabel">Delete Task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="">
                            <div class="form-group">
                                <label for="deleteTitle">Title</label>
                                <select class="form-control" id="deleteTitle" name="task-id" required>
                                    <option value="" selected disabled>SELECT A TASK</option>
                                    <?php
                                    foreach ($tasks as $task) {
                                        echo "<option value='" . $task['id'] . "'>" . $task['title'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger" name="delete-submit">Delete</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Task Summary</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $sql = "SELECT COUNT(*) AS total_tasks FROM tasks";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $total_tasks = $row['total_tasks'];

                        echo '<p>Total Tasks: ' . $total_tasks . '</p>';

                        $sql = "SELECT COUNT(*) AS completed_tasks FROM tasks WHERE status = 'Completed'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $completed_tasks = $row['completed_tasks'];

                        echo '<p>Tasks Completed: ' . $completed_tasks . '</p>';

                        $sql = "SELECT COUNT(*) AS in_progress_tasks FROM tasks WHERE status = 'In Progress'";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $in_progress_tasks = $row['in_progress_tasks'];

                        echo '<p>Tasks In Progress: ' . $in_progress_tasks . '</p>';
                        ?>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-20 ">
                    <div class="card-header">
                        <h5>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <ul>
                            <?php
                            $sql = "SELECT * FROM `notifications` WHERE 1";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<li>" . $row['message'] . " at " . $row['created_at'] . "</li>";
                            }
                            ?>
                        </ul>

                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Task List</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-white table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = 'SELECT * FROM `tasks` WHERE 1';
                                $result = mysqli_query($conn, $sql);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<td>' . $row['id'] . '</td>';
                                        echo '<td>' . $row['title'] . '</td>';
                                        echo '<td>' . $row['status'] . '</td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-4">
        <div class="container text-center">
            <span class="text-muted">Task Management App &copy; 2023</span>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>