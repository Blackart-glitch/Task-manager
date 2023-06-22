<?php
include('dbconnect.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the username, password, and email from the sign-up form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Perform your validation and registration logic here
    // ...

    // Insert the user into the database
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
    $result = mysqli_query($conn, $sql);

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
}
?>


<html>

<head>
    <title>Sign Up Page</title>
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container w-70 p-3 shadow-lg">
        <h1>Sign Up</h1>
        <?php
        // Error handling and warnings
        if (isset($error)) {

            echo '<div class="alert alert-danger">' . $error . '</div>';
        }
        ?>
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Sign Up</button>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>

</html>