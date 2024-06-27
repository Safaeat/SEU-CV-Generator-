<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'signup') {
        // Signup logic
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if ($password !== $confirm_password) {
            $message = "Passwords do not match!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Generate a unique token for email verification
            $token = bin2hex(random_bytes(50));

            // Save the user with a status of 'pending'
            $sql = "INSERT INTO users (username, email, password, token, status) VALUES (?, ?, ?, ?, 'pending')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $username, $email, $hashed_password, $token);

            if ($stmt->execute()) {
                // Send confirmation email using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'safaeat.11.sma@gmail.com'; // Replace with your Gmail address
                    $mail->Password = 'cxfsyzieqyvqjjdl'; // Replace with your Gmail password or app-specific password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('safaeat.11.sma@gmail.com', 'SEU CV Generator');
                    $mail->addAddress($email);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'Email Confirmation from SEU CV Generator';
                    $mail->Body    = "Please click the link below to verify your email address:<br>";
                    $mail->Body   .= "<a href='http://localhost/cvv5/signup.php?token=$token'>To Verify Email Click Me!</a>";

                    $mail->send();
                    $message = 'Confirmation email has been sent!';
                } catch (Exception $e) {
                    $message = "Failed to send confirmation email. Mailer Error: {$mail->ErrorInfo}";
                    if (strpos($mail->ErrorInfo, 'Could not authenticate') !== false) {
                        $message .= "<br>Suggestions to resolve authentication issues:";
                        $message .= "<ul>";
                        $message .= "<li>Ensure that your username and password are correct.</li>";
                        $message .= "<li>Use an app-specific password if you have 2-Step Verification enabled.</li>";
                        $message .= "<li>Enable less secure app access in your Google account settings, although this is not recommended for long-term security.</li>";
                        $message .= "</ul>";
                    }
                }
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }

            $stmt->close();
        }
    } elseif ($_POST['action'] == 'signin') {
        // Sign-in logic
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if ($user['status'] == 'verified' && password_verify($password, $user['password'])) {
                // Start session and set session variables
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: welcome.php");
                exit();
            } else {
                $message = "The Email is not verified.";
            }
        } else {
            $message = "Invalid email or password.";
        }

        $stmt->close();
    }
}

// Handle email verification
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verify the token
    $sql = "SELECT * FROM users WHERE token = ? AND status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, update the user's status to 'verified'
        $sql = "UPDATE users SET status = 'verified', token = NULL WHERE token = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $token);
        if ($stmt->execute()) {
            $message = "Your email has been verified! You can now <a href='login.php'>login</a>.";
        } else {
            $message = "Error updating record: " . $conn->error;
        }
    } else {
        $message = "Invalid or expired token.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="assets/images/seu_logo.png">
    <link rel="stylesheet" href="assets/css/sign.css">
</head>
<body>
<h2>Registration Form</h2>

<div class="container" id="container">
    <div class="form-container sign-up-container">
        <form method="POST" action="">
            <h1>Create Account</h1>
            <span>Use your email for registration</span>
            <input type="text" name="username" placeholder="Name" required />
            <input type="email" name="email" placeholder="Email" required />
            <input type="password" name="password" placeholder="Password" required />
            <input type="password" name="confirm_password" placeholder="Confirm Password" required />
            <input type="hidden" name="action" value="signup">
            <button type="submit">Sign Up</button>
            <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
            <?php endif; ?>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Hello, There!</h1>
                <p>Enter your personal details and start journey with SEU</p>
                <button><a href="sign.php">Sign In</a></button>
            </div>
        </div>
    </div>
</div>
<footer>
    <p>
        Created with <i style="font-size:12px" class="fa">&#xf08a;</i> by
        <a target="_blank" href="https://seu.edu.bd">Southeast University.</a>
    </p>
</footer>
<script>
    const signUpButton = document.getElementById('signUp');
    const signInButton = document.getElementById('signIn');
    const container = document.getElementById('container');

    signUpButton.addEventListener('click', () => {
       container.classList.add("right-panel-active");
    });

    signInButton.addEventListener('click', () => {
      container.classList.remove("right-panel-active");
    });
</script>
</body>
</html>
