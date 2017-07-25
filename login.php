<?php

require_once 'includes/config.php';

/* Initializing variables */

$username = $password = "";
$username_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    // checking if username is empty or not
    if(empty(trim($_POST['username']))) {
        $username_err = "Please enter Username";
    } else {
        $username = $_POST['username'];
    }

    //checking if password id empty or not
    if(empty(trim($_POST['password']))) {
        $password_err = "Please enter Password";
    } else {
        $password = $_POST['password'];
    }

    // Validate Credentials
    if(empty($username_err) && empty($password_err)) {
        //Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username= ?";

        if($stmt = mysqli_prepare($conn,$sql)) {
            // Binding the variables to prepare statements as Parameters
            mysqli_stmt_bind_param($stmt,"s",$username_param);

            //Setting Parameters
            $username_param = $username;

            //Attempt to execute prepared statement
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);

                //Check whether username exists in DB or not if yes verify Password
                if(mysqli_stmt_num_rows($stmt) ==1) {
                    //Bind Result Variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);

                    if(mysqli_stmt_fetch($stmt)) {
                        if(password_verify($password, $hashed_password)) {
                            session_start();
                            $_SESSION['username'] = $username;      
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                        }
                        } else {
                            echo "oops something went wrong. Please try again";
                        }
                    }

                    mysqli_stmt_close($stmt);
            }

            mysqli_close($conn);
        }
    }
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body {
            font: 14px sans-serif;
        }

        .wrapper {
            width: 350px;
            padding: 20px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please login here!</p>
        <!-- Creating a Vertical Form Layout with Bootstrap -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group <?php echo (!empty($username_err))? 'has-error':'';?>">
                <label>UserName: <sup>*</sup></label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($password_err))? 'has-error':'';?>">
                <label>Password: <sup>*</sup></label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Don't have an account? <a href="register.php">Sign Up here</a>.</p>
        </form>
    </div>
</body>
</html>