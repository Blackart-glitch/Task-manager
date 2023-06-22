<?php
include('dbconnect.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Assuming you have a database connection established
    // Retrieve the username and password from the login form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Perform your validation and authentication checks here
    // ...

    // Select the user from the database
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

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
}
?>


<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container w-70 p-3 shadow-lg">
        <h1>Login</h1>
        <?php
        // Error handling and warnings
        if (isset($error)) {
            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        ?>
        <form method="post" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
        </form>
    </div>
</body>

</html>