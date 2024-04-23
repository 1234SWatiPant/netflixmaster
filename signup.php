<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "signup.db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Escape user inputs for security
$name = $conn->real_escape_string($_POST['name']);
$age = $conn->real_escape_string($_POST['age']);
$phone = $conn->real_escape_string($_POST['phone']);
$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);
$confirmPassword = $conn->real_escape_string($_POST['confirmPassword']);

// Validate inputs
if (!preg_match("/^[a-zA-Z0-9\s]*$/", $name)) {
    die("Invalid name format.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}

if (strlen($phone) != 10 || !is_numeric($phone)) {
    die("Invalid phone number format.");
}

if ($age < 15) {
    die("Age must be 15 or above.");
}

if (strlen($password) < 8 || !preg_match("/[!@#$%^&*()\-_=+{};:,<.>]/", $password)) {
    die("Password must be at least 8 characters long and contain a special symbol.");
}

if ($password !== $confirmPassword) {
    die("Passwords do not match.");
}

// Check if email or phone number already exists
$sql = "SELECT * FROM users WHERE email='$email' OR phone='$phone'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // User already exists, display error message
    die("User with this email or phone number already exists. <a href='login.html'>Login here</a>");
}

// Insert user data into database
$sql = "INSERT INTO users (name, age, phone, email, password) VALUES ('$name', '$age', '$phone', '$email', '$password')";
if ($conn->query($sql) === TRUE) {
    // Redirect to movie page
    header("Location: movie.html");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
