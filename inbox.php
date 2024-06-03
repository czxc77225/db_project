<?php
$servername = "localhost";
$username = "root";
$password = "12345678";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['username'])) {
    $username = $_GET['username'];

    echo "<h2>Inbox</h2>";

    $search_sender = isset($_GET['sender']) ? $conn->real_escape_string($_GET['sender']) : '';
    $search_subject = isset($_GET['subject']) ? $conn->real_escape_string($_GET['subject']) : '';
    $search_body = isset($_GET['body']) ? $conn->real_escape_string($_GET['body']) : '';
    $search_time = isset($_GET['time']) ? $conn->real_escape_string($_GET['time']) : '';

    $search_query = "AND (1=1)";
    if (!empty($search_sender)) {
        $search_query .= " AND sender LIKE '%$search_sender%'";
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

    $inbox_sql = "SELECT * FROM emails WHERE recipient='$username' $search_query ORDER BY sent_time DESC";
    $inbox_result = $conn->query($inbox_sql);

    echo '<form method="get" action="">
        <input type="hidden" name="username" value="' . $username . '">
        From: <input type="text" name="sender" value="' . htmlspecialchars($search_sender) . '"><br>
        Subject: <input type="text" name="subject" value="' . htmlspecialchars($search_subject) . '"><br>
        Message: <input type="text" name="body" value="' . htmlspecialchars($search_body) . '"><br>
        Time: <input type="text" name="time" value="' . htmlspecialchars($search_time) . '"><br>
        <input type="submit" value="Search">
    </form><br>';

    if ($inbox_result->num_rows > 0) {
        while($email = $inbox_result->fetch_assoc()) {
            echo "From: " . $email['sender'] . "<br>";
            echo "Subject: " . $email['subject'] . "<br>";
            echo "Message: " . $email['body'] . "<br>";
            echo "Time: " . $email['sent_time'] . "<br>";
            echo '<form method="post" action="delete_user_email.php">
                <input type="hidden" name="email_id" value="' . $email['id'] . '">
                <input type="hidden" name="username" value="' . $username . '">
                <input type="submit" value="Delete">
            </form><br>';
        }
    } else {
        echo "No messages in your inbox.<br>";
    }
} else {
    echo "No username provided.";
}

$conn->close();
?>
