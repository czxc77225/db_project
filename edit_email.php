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

    $email_sql = "SELECT * FROM emails WHERE id='$email_id'";
    $email_result = $conn->query($email_sql);

    if ($email_result->num_rows > 0) {
        $email = $email_result->fetch_assoc();
        echo '<h2>Edit Email</h2>';
        echo '<form method="post" action="update_email.php">
            To: <input type="text" name="recipient" value="' . $email['recipient'] . '"><br>
            Subject: <input type="text" name="subject" value="' . $email['subject'] . '"><br>
            Message: <textarea name="body">' . $email['body'] . '</textarea><br>
            <input type="hidden" name="email_id" value="' . $email['id'] . '">
            <input type="submit" value="Update">
        </form>';
    } else {
        echo "No email found with that ID.";
    }
}

$conn->close();
?>
