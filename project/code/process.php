<?php
session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email and password are valid
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Save user information in the session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_email'] = $row['email'];

            // Redirect to a protected page
            header("Location: protected_page.php");
            exit();
        } else {
            // Invalid password
            header("Location: index.php?error=invalid_credentials");
            exit();
        }
    } else {
        // Invalid email
        header("Location: index.php?error=invalid_credentials");
        exit();
    }
}
$conn->close();
?>
