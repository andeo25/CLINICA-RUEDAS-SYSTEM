<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/animations.css">  
    <link rel="stylesheet" href="css/main.css">  
    <link rel="stylesheet" href="css/signup.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>i
    <title>Create Account</title>
    <style>
        .container{
            animation: transitionIn-X 0.5s;
        }
    </style>
</head>
<body>
    <?php
    session_start();

    $_SESSION["user"] = "";
    $_SESSION["usertype"] = "";

    // Set the new timezone
    date_default_timezone_set('Asia/Kolkata');
    $date = date('Y-m-d');

    $_SESSION["date"] = $date;

    // Include database connection
    include("connection.php");

    $error = ''; // Initialize error variable
    $accountCreated = false; // Flag for successful account creation

    if ($_POST) {
        $result = $database->query("SELECT * FROM webuser");

        $fname = $_SESSION['personal']['fname'];
        $lname = $_SESSION['personal']['lname'];
        $name = $fname . " " . $lname;
        $address = $_SESSION['personal']['address'];
        $dob = $_SESSION['personal']['dob'];
        $email = $_POST['newemail'];
        $tele = $_POST['tele'];
        $newpassword = $_POST['newpassword'];
        $cpassword = $_POST['cpassword'];

        if ($newpassword == $cpassword) {
            $sqlmain = "SELECT * FROM webuser WHERE email = ?;";
            $stmt = $database->prepare($sqlmain);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Already have an account for this Email address.</label>';
            } else {
                // Insert the new user into the patient and webuser tables
                $database->query("INSERT INTO patient (pemail, pname, ppassword, paddress, pdob, ptel) VALUES ('$email', '$name', '$newpassword', '$address', '$dob', '$tele');");
                $database->query("INSERT INTO webuser (email, usertype) VALUES ('$email', 'p');");

                $_SESSION["user"] = $email;
                $_SESSION["usertype"] = "p";
                $_SESSION["username"] = $fname;

                // Set flag to indicate successful account creation
                $accountCreated = true;
            }
        } else {
            $error = '<label for="promter" class="form-label" style="color:rgb(255, 62, 62);text-align:center;">Password Confirmation Error! Please reconfirm your password.</label>';
        }
    }
    ?>

    <center>
        <div class="container">
            <table border="0" style="width: 69%;">
                <tr>
                    <td colspan="2">
                        <p class="header-text">Let's Get Started</p>
                        <p class="sub-text">It's Okay, Now Create User Account.</p>
                    </td>
                </tr>
                <form action="" method="POST">
                    <tr>
                        <td class="label-td" colspan="2">
                            <label for="newemail" class="form-label">Email: </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="email" name="newemail" class="input-text" placeholder="Email Address" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <label for="tele" class="form-label">Mobile Number: </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="tel" name="tele" class="input-text" placeholder="ex: 09876543211" pattern="0[0-9]{10}" >
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <label for="newpassword" class="form-label">Create New Password: </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="password" name="newpassword" class="input-text" placeholder="New Password" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <label for="cpassword" class="form-label">Confirm Password: </label>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-td" colspan="2">
                            <input type="password" name="cpassword" class="input-text" placeholder="Confirm Password" required>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <?php echo $error; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="reset" value="Reset" class="login-btn btn-primary-soft btn" >
                        </td>
                        <td>
                            <input type="submit" value="Sign Up" class="login-btn btn-primary btn">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <br>
                            <label for="" class="sub-text" style="font-weight: 280;">Already have an account? </label>
                            <a href="login.php" class="hover-link1 non-style-link">Login</a>
                            <br><br><br>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </center>

    <!-- Display SweetAlert after Sign Up -->
    <?php if ($accountCreated): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'You have successfully created an account!',
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "login.php"; // Redirect to login after SweetAlert confirmation
                }
            });
        </script>
    <?php endif; ?>

</body>
</html>
