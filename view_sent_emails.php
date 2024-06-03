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

    $search_recipient = isset($_POST['recipient']) ? $conn->real_escape_string($_POST['recipient']) : '';
    $search_subject = isset($_POST['subject']) ? $conn->real_escape_string($_POST['subject']) : '';
    $search_body = isset($_POST['body']) ? $conn->real_escape_string($_POST['body']) : '';
    $search_time = isset($_POST['time']) ? $conn->real_escape_string($_POST['time']) : '';

    $search_query = "AND (1=1)";
    if (!empty($search_recipient)) {
        $search_query .= " AND recipient LIKE '%$search_recipient%'";
    }
    if (!empty($search_subject)) {
        $search_query .= " AND subject LIKE '%$search_subject%'";
    }
    if (!empty($search_body)) {
        $search_query .= " AND body LIKE '%$search_body%'";
    }
    if (!empty($search_time)) {
        $search_query .= " AND sent_time LIKE '%$search_time%'";
    }

    echo "<h2>Sent Messages</h2>";

    $sent_sql = "SELECT * FROM emails WHERE sender='$sender' $search_query ORDER BY sent_time DESC";
    $sent_result = $conn->query($sent_sql);

    echo '<form method="post" action="">
        To: <input type="text" name="recipient" value="' . htmlspecialchars($search_recipient) . '"><br>
        Subject: <input type="text" name="subject" value="' . htmlspecialchars($search_subject) . '"><br>
        Message: <input type="text" name="body" value="' . htmlspecialchars($search_body) . '"><br>
        Time: <input type="text" name="time" value="' . htmlspecialchars($search_time) . '"><br>
        <input type="hidden" name="sender" value="' . $sender . '">
        <input type="submit" value="Search">
    </form><br>';

    if ($sent_result->num_rows > 0) {
        while($email = $sent_result->fetch_assoc()) {
            echo "To: " . $email['recipient'] . "<br>";
            echo "Subject: " . $email['subject'] . "<br>";
            echo "Message: " . $email['body'] . "<br>";
            echo "Time: " . $email['sent_time'] . "<br>";
            echo '<form method="post" action="edit_email.php">
                <input type="hidden" name="email_id" value="' . $email['id'] . '">
                <input type="submit" value="Edit">
            </form>';
            echo '<form method="post" action="delete_email.php">
                <input type="hidden" name="email_id" value="' . $email['id'] . '">
                <input type="submit" value="Delete">
            </form><br>';
        }
    } else {
        echo "No sent messages found.";
    }
}

$conn->close();
?>
