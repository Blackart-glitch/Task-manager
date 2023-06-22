<?php
include('dbconnect.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['signup-submit'])) {
        // Retrieve the form data
        $email = $_POST["email"];
        $taskId = $_POST["task-id"];

        // Check if there's already a user assigned to the task
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

?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task View</title>
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Task List -->
    <div class="container mt-5">
        <h1>Task View</h1>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT `id`, `title`, `description`, `status` FROM `tasks` WHERE status = 'New' OR status = 'In Progress'";
                $result = mysqli_query($conn, $query);

                // Generate table rows dynamically based on fetched data
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $taskId = $row['id'];
                        $taskTitle = $row['title'];
                        $taskDescription = $row['description'];
                        $taskStatus = $row['status'];

                        // Output table row with dynamic data
                        echo "<tr>";
                        echo "<td>$taskId</td>";
                        echo "<td>$taskTitle</td>";
                        echo "<td>$taskDescription</td>";
                        echo "<td>$taskStatus</td>";
                        echo "<td>";
                        echo "<button class='btn btn-primary mx-2 btn-sm' data-toggle='modal' data-target='#signupModal' data-task-id='$taskId' data-task-title='$taskTitle' onclick='getTaskData(this)'>Sign Up</button>";
                        echo "<button class='btn btn-primary btn-sm' data-toggle='modal' data-target='#updateStatusModal' data-task-id='$taskId' data-task-title='$taskTitle' onclick='getTaskData(this)'>Update Status</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    // No tasks found
                    echo "<tr>
                    <td colspan='5'>No tasks found.</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- JavaScript -->
        <script>
            function getTaskData(button) {
                var row = button.closest("tr");
                var taskId = row.cells[0].innerText;
                var taskTitle = row.cells[1].innerText;

                $('#signup-task-id').val(taskId);
                $('#update-task-id').val(taskId);

                console.log("Task ID:", taskId);
                console.log("Task Title:", taskTitle);
            }
        </script>

    </div>

    <!-- Sign Up Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="signupModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signupModalLabel">Sign Up for Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="reg-task-id">Task ID</label>
                            <input readonly type="text" class="form-control" id="signup-task-id" name="task-id">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input required type="email" class="form-control" id="signup-email" name="email" placeholder="Enter your email">
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="signup-submit">Sign Up</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog" aria-labelledby="updateStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateStatusModalLabel">Update Task Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="task-id">Task ID</label>
                            <input readonly type="text" class="form-control" id="update-task-id" name="task-id">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input required type="email" class="form-control" id="update-email" name="email" placeholder="Enter your email">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="In Progress">In Progress</option>
                                <option value="Done">Done</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="update-status-submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>