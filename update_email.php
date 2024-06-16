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
    $recipient = $_POST['recipient'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    $update_sql = "UPDATE emails SET recipient='$recipient', subject='$subject', body='$body' WHERE id='$email_id'";

    if ($conn->query($update_sql) === TRUE) {
        echo "Email updated successfully.";
    } else {
        echo "Error updating email: " . $conn->error;
    }
}

$conn->close();
?>
