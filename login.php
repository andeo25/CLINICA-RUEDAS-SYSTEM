<?php
session_start();

// Unset session variables at the beginning
$_SESSION["user"] = "";
$_SESSION["usertype"] = "";

// Set the timezone and date
date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$_SESSION["date"] = $date;

// Include database connection
include("connection.php");

$error = ''; // Initialize error variable

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['useremail'];
    $password = $_POST['userpassword'];

    // Use prepared statements to prevent SQL injection
    $stmt = $database->prepare("SELECT * FROM webuser WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $utype = $user['usertype'];

        // Check user type and validate password
        if ($utype == 'p') {
            $checker = $database->prepare("SELECT * FROM patient WHERE pemail = ? AND ppassword = ?");
            $checker->bind_param("ss", $email, $password);
            $checker->execute();
            $checkerResult = $checker->get_result();

            if ($checkerResult->num_rows == 1) {
                // Patient dashboard
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'p';
                header('Location: patient/index.php');
                exit;
            } else {
                $error = '<label class="form-label" style="color:rgb(255, 62, 62); text-align:center;">Wrong credentials: Invalid email or password</label>';
            }
        } elseif ($utype == 'a') {
            $checker = $database->prepare("SELECT * FROM admin WHERE aemail = ? AND apassword = ?");
            $checker->bind_param("ss", $email, $password);
            $checker->execute();
            $checkerResult = $checker->get_result();

            if ($checkerResult->num_rows == 1) {
                // Admin dashboard
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'a';
                header('Location: admin/index.php');
                exit;
            } else {
                $error = '<label class="form-label" style="color:rgb(255, 62, 62); text-align:center;">Wrong credentials: Invalid email or password</label>';
            }
        } elseif ($utype == 'd') {
            $checker = $database->prepare("SELECT * FROM doctor WHERE docemail = ? AND docpassword = ?");
            $checker->bind_param("ss", $email, $password);
            $checker->execute();
            $checkerResult = $checker->get_result();

            if ($checkerResult->num_rows == 1) {
                // Doctor dashboard
                $_SESSION['user'] = $email;
                $_SESSION['usertype'] = 'd';
                header('Location: doctor/index.php');
                exit;
            } else {
                $error = '<label class="form-label" style="color:rgb(255, 62, 62); text-align:center;">Wrong credentials: Invalid email or password</label>';
            }
        }
    } else {
        $error = '<label class="form-label" style="color:rgb(255, 62, 62); text-align:center;">We can\'t find any account for this email.</label>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/login.css">
    <title>Login</title>
</head>
<body>
    <center>
        <div class="container">
            <table border="0" style="margin: 0;padding: 0;width: 60%;">
                <tr>
                    <td>
                        <p class="header-text">Welcome Back!</p>
                    </td>
                </tr>
                <div class="form-body">
                    <tr>
                        <td>
                            <p class="sub-text">Login with your details to continue</p>
                        </td>
                    </tr>
                    <form action="" method="POST">
                        <tr>
                            <td class="label-td">
                                <label for="useremail" class="form-label">Email: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <input type="email" name="useremail" class="input-text" placeholder="Email Address" required>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <label for="userpassword" class="form-label">Password: </label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td">
                                <input type="password" name="userpassword" class="input-text" placeholder="Password" required>
                            </td>
                        </tr>
                        <tr>
                            <td><br><?php echo $error; ?></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="submit" value="Login" class="login-btn btn-primary btn">
                            </td>
                        </tr>
                    </form>
                    <tr>
                        <td>
                            <br>
                            <label for="" class="sub-text" style="font-weight: 280;">Don't have an account&#63; </label>
                            <a href="signup.php" class="hover-link1 non-style-link">Sign Up</a>
                            <br><br><br>
                        </td>
                    </tr>
                </div>
            </table>
        </div>
    </center>

    
</body>
</html>
