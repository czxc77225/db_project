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
    $sender = $_POST['sender'];
    $recipient = $_POST['recipient'];
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    $stmt = $conn->prepare("INSERT INTO emails (sender, recipient, subject, body) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $sender, $recipient, $subject, $body);

    if ($stmt->execute()) {
        echo "Message sent successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
