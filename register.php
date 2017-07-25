<?php

/* Including the database config file  */
require_once 'includes/config.php';

/* Initialising variable */
$firstname = $lastname = $username = $password = $confirm_password = $gender_sex = $interest = $bio = "";
$firstname_err = $lastname_err = $username_err = $password_err = $confirm_password_err = $gender_err = $interest_err = $bio_err = "";

/* Processing form data when form is submitted */
if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty(trim($_POST['firstname']))) {
        $firstname_err = "Please enter first name";
    } else {
        $firstname = trim($_POST['firstname']);
    }

    if(empty(trim($_POST['lastname']))) {
        $lastname_err = "Please enter last name";
    } else {
        $lastname = trim($_POST['lastname']);
    }


    if(empty(trim($_POST['username']))) {
        $username_err = "UserName Cannot be empty";
    } else {
        //Preparing Select Statement
        $sql = "SELECT id FROM users WHERE username= ?";

        if($stmt = mysqli_prepare($conn,$sql)) {
            /*Bind variables to Prepare statements as Parameters */
            mysqli_stmt_bind_param($stmt,"s",$param_username);

            /* Setting Parameter */
            $param_username = trim($_POST['username']);

            /* Attempt to execute the prepare statement */
            if(mysqli_stmt_execute($stmt)) {
                /* Store Result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) ==1) {
                    $username_err = "Sorry this username already taken.";
                } else {
                    $username  = trim($_POST['username']);
                }
            } else {
                echo "Oops! Something went wrong. Please try again";
            }

        } //mysqli_prepare

        //Close Statment
        mysqli_stmt_close($stmt);
    } // main else 

    // Validating Password Field
    if(empty(trim($_POST['password']))) {
        $password_err = "Password field cannot be empty";
    } elseif(strlen(trim($_POST['password'])) < 6) {
        $password_err = "Passowd must have atleast 6 characters";
    } else {
        $password = trim($_POST['password']);
    }


     // Validating Confirm Password Field
    if(empty(trim($_POST['confirm_password']))) {
        $confirm_password_err = "Please confirm Password";
    } else {
        $confirm_password = trim($_POST['confirm_password']);
        if($_POST['password'] != $_POST['confirm_password']) {
            $confirm_password_err = "Password didnot match";
        }
    }

    if(isset($_POST['sexgender'])) {
        $gender_sex = trim($_POST['sexgender']);
    }else{
        $gender_err = "Please select gender";
    }

    if(empty(trim($_POST['interest']))) {
        $interest_err = "Please select area of interest";
    } else {
        $interest = trim($_POST['interest']);
    }

    if(empty(trim($_POST['bio']))) {
        $bio_err = "Please write your bio";
    } else {
        $bio = trim($_POST['bio']);
    }



    //Chekcing input errors before inserting into database
    if(empty($firstname_err) && empty($lastname_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($gender_err) && empty($interest_err) && empty($bio_err)) {
        $sql = "INSERT INTO users (firstname,lastname,username,password,sexgender,interest,bio) VALUES (?,?,?,?,?,?,?)";
        $stmt = mysqli_prepare($conn, $sql) or die(mysqli_error($conn));
        if($stmt) {
            mysqli_stmt_bind_param($stmt,"sssssss", $param_firstname, $param_lastname, $param_username, $param_password, $param_sex, $param_interest, $param_bio);

            //Setting Parameters
             $param_firstname = $firstname;
             $param_lastname = $lastname;
             $param_username = $username;
             $param_password = password_hash($password, PASSWORD_DEFAULT);
             $param_sex = $gender_sex;
             $param_interest = $interest;
             $param_bio = $bio;

             //Attempt to execute the prepared statements
             if(mysqli_stmt_execute($stmt)) {
                header("Location: login.php");
             } else {
                echo "Something went Wrong. Please try again and again";
             }
        }

        mysqli_stmt_close($stmt);
    }

        mysqli_close($conn);
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
        <h2>Sign Up</h2>
        <p>Please create an account here!</p>
        <!-- Creating a Vertical Form Layout with Bootstrap -->
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

            <div class="form-group <?php echo (!empty($firstname_err)) ? 'has-error':'';?>">
                <label>First Name<sup>*</sup></label>
                <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>">
                <span class="help-block"><?php echo $firstname_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($lastname_err)) ? 'has-error':'';?>">
                <label>Last Name<sup>*</sup></label>
                <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>">
                <span class="help-block"><?php echo $lastname_err; ?></span>
            </div>

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

            <div class="form-group <?php echo (!empty($confirm_password_err))? 'has-error':'';?>">
                <label>Confirm Password: <sup>*</sup></label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($gender_err))? 'has-error':'';?>">
                <label>Gender:<sup>*</sup></label>
                    <label  class="radio-inline">
                            <input type="radio" id="femaleRadio" name="sexgender" value="Female">Female </label>
                        <label  class="radio-inline">
                            <input type="radio" id="maleRadio" name="sexgender" value="Male">Male</label>
                <span class="help-block"><?php echo $gender_err; ?></span>
            </div>

            <div class="form-group <?php echo (!empty($bio_err))? 'has-error':'';?>">
                <label>Biography:<sup>*</sup></label>
                <textarea class="form-control" rows="3" name="bio" value="<?php echo $bio; ?>"></textarea>
                <span class="help-block"><?php echo $bio_err; ?></span>
            </div> 

            <div class="form-group <?php echo (!empty($interest_err))? 'has-error':'';?>">
            <label>Area of Interest:<sup>*</sup></label>
            <select name="interest" class="form-control">
                <option value=""></option>
                <option value="php">Php</option>
                <option value="perl">Perl</option>
                <option value="mysql">MySQL</option>
                </select>
            <span class="help-block"><?php echo $interest_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>