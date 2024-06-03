<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 檢查並獲取用戶輸入的字串
    if (isset($_POST['inputString'])) {
        $inputString = htmlspecialchars($_POST['inputString']);
        echo "Received input: " . $inputString . "<br>";
    } else {
        echo "No input string provided.";
        exit(); // 提前退出避免執行後面的代碼
    }

    // 資料庫連接設定
    $servername = "localhost"; // 確保這裡是正確的
    $username = "root";
    $password = "12345678";
    $dbname = "customer_data";

    // 創建連接
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 檢查連接
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        echo "Database connected successfully.<br>";
    }

    // 準備並綁定
    $stmt = $conn->prepare("INSERT INTO submissions (submitted_string) VALUES (?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("s", $inputString);

    // 執行查詢
    if ($stmt->execute()) {
        echo "Your submission has been recorded.<br>";

        // 查詢最新的 10 條記錄
        $result = $conn->query("SELECT * FROM submissions ORDER BY id DESC LIMIT 10");

        if ($result === false) {
            die("Query failed: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            echo "<h3>Last 10 Submissions:</h3>";
            while ($row = $result->fetch_assoc()) {
                echo "ID: " . $row["id"] . " - String: " . $row["submitted_string"] . " - Time: " . $row["submission_time"] . "<br>";
            }
        } else {
            echo "No submissions found.";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    // 關閉聲明和連接
    $stmt->close();
    $conn->close();
} else {
    // 如果不是通過 POST 請求訪問，顯示錯誤訊息
    echo "Invalid request method.";
}
?>
