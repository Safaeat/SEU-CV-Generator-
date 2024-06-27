<?php
session_start(); // Start the session

// Redirect to login page if username is not set in session
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "user_auth";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $designation = $_POST['designation'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phoneno = $_POST['phoneno'];
    $summary = $_POST['summary'];
    
    $achievements = json_encode($_POST['group-a']);
    $experiences = json_encode($_POST['group-b']);
    $education = json_encode($_POST['group-c']);
    $projects = json_encode($_POST['group-d']);
    $skills = json_encode($_POST['group-e']);

    $sql = "UPDATE cv_data SET firstname=?, middlename=?, lastname=?, designation=?, address=?, email=?, phoneno=?, summary=?, achievements=?, experiences=?, education=?, projects=?, skills=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssi", $firstname, $middlename, $lastname, $designation, $address, $email, $phoneno, $summary, $achievements, $experiences, $education, $projects, $skills, $id);
    
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: welcome.php");
    exit();
} else {
    $id = $_GET['id'];

    $sql = "SELECT * FROM cv_data WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cv = $result->fetch_assoc();

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Edit CV</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="assets/images/seu_logo.png">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>
    <div class="container">
        <h2>Edit CV</h2>
        <form action="edit_cv.php" method="post">
            <input type="hidden" name="id" value="<?php echo $cv['id']; ?>">
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" name="firstname" class="form-control" value="<?php echo htmlspecialchars($cv['firstname']); ?>">
            </div>
            <div class="form-group">
                <label for="middlename">Middle Name:</label>
                <input type="text" name="middlename" class="form-control" value="<?php echo htmlspecialchars($cv['middlename']); ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" name="lastname" class="form-control" value="<?php echo htmlspecialchars($cv['lastname']); ?>">
            </div>
            <div class="form-group">
                <label for="designation">Designation:</label>
                <input type="text" name="designation" class="form-control" value="<?php echo htmlspecialchars($cv['designation']); ?>">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($cv['address']); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($cv['email']); ?>">
            </div>
            <div class="form-group">
                <label for="phoneno">Phone No:</label>
                <input type="text" name="phoneno" class="form-control" value="<?php echo htmlspecialchars($cv['phoneno']); ?>">
            </div>
            <div class="form-group">
                <label for="summary">Summary:</label>
                <textarea name="summary" class="form-control"><?php echo htmlspecialchars($cv['summary']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="group-a">Achievements:</label>
                <textarea name="group-a" class="form-control"><?php echo htmlspecialchars(implode("\n", json_decode($cv['achievements'], true))); ?></textarea>
            </div>
            <div class="form-group">
                <label for="group-b">Experiences:</label>
                <textarea name="group-b" class="form-control"><?php echo htmlspecialchars(implode("\n", json_decode($cv['experiences'], true))); ?></textarea>
            </div>
            <div class="form-group">
                <label for="group-c">Education:</label>
                <textarea name="group-c" class="form-control"><?php echo htmlspecialchars(implode("\n", json_decode($cv['education'], true))); ?></textarea>
            </div>
            <div class="form-group">
                <label for="group-d">Projects:</label>
                <textarea name="group-d" class="form-control"><?php echo htmlspecialchars(implode("\n", json_decode($cv['projects'], true))); ?></textarea>
            </div>
            <div class="form-group">
                <label for="group-e">Skills:</label>
                <textarea name="group-e" class="form-control"><?php echo htmlspecialchars(implode("\n", json_decode($cv['skills'], true))); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</body>
</html>
