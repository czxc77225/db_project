<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_id = $_POST['email_id'];

    $delete_sql = "DELETE FROM emails WHERE id='$email_id'";

    if ($conn->query($delete_sql) === TRUE) {
        echo "Email deleted successfully.";
    } else {
        echo "Error deleting email: " . $conn->error;
    }
}

$conn->close();
?>
