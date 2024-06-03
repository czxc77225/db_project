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
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT password FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            echo "Login successful<br>";
            
            // 顯示收件夾功能連結
            echo "<h2>Inbox</h2>";
            echo '<a href="inbox.php?username=' . urlencode($username) . '">Go to Inbox</a><br>';

            // 顯示寄件功能
            echo "<h2>Send a Message</h2>";
            echo '<form method="post" action="send_email.php">
                To: <input type="text" name="recipient"><br>
                Subject: <input type="text" name="subject"><br>
                Message: <textarea name="body"></textarea><br>
                <input type="hidden" name="sender" value="' . $username . '">
                <input type="submit" value="Send">
            </form>';

            // 顯示查找已發送郵件功能
            echo "<h2>Sent Messages</h2>";
            echo '<form method="post" action="view_sent_emails.php">
                <input type="hidden" name="sender" value="' . $username . '">
                <input type="submit" value="View Sent Messages">
            </form>';
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No user found with that username";
    }
}

$conn->close();
?>
