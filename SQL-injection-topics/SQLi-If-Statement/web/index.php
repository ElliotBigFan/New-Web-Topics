<?php
ini_set('display_errors', 1);
$conn = new mysqli(getenv('MYSQL_HOST'), getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'), getenv('MYSQL_DB'));

if ($conn->connect_error) {
    die("Connection failed.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>DBS401 - Final Exam</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php
            if (isset($_POST['expression'])) {
                $data = $_POST['expression'];
                $filtered = preg_replace(['/or/i', '/and/i', '/union/i', '/select/i', '/from/i', '/where/i', '/substring/i', '/ascii/i'], '', $data);
                var_dump($filtered);
                $result = 0;
                
                $query = "SELECT IF(($filtered), 1, 0) AS result FROM s3cr3t_t4bl3 WHERE id=1";
                var_dump($query);
                try {
                    if ($result = $conn->query($query)) {
                        $row = $result->fetch_assoc();
                        $result = $row['result'] ?? 0;
                        if ($result == 0)
                            echo "<div id=\"result\">You are wrong, try again!</div><audio autoplay hidden><source src=\"admonition.mp3\" type=\"audio/mpeg\"></audio>";
                        else
                            echo "<div id=\"result\">You are right, congratulations!</div>";
                    }
                } catch (\Throwable $th) {
                    echo "<div class=\"error-message\"><i class=\"fas fa-exclamation-triangle\"></i> Error!</div>";
                }
            }
        ?>
        <div class="challenge-header">
            <i class="fas fa-lock"></i>
            <h1>DBS401 - Final Exam</h1>
            <div class="challenge-description">
                <p>Welcome to the Database Security Final Test!</p>
                <p>Your task is to craft a SQL expression that will help you retrieve the hidden flag.</p>
                <p class="hint"><i class="fas fa-lightbulb"></i> Hint: Listening familiar voice...</p>
            </div>
        </div>
        
        <form action="/" id="calcForm" method="POST">
            <div class="input-group">
                <i class="fas fa-terminal"></i>
                <input type="text" id="expression" name="expression" placeholder="Enter your SQL expression..." required>
            </div>
            <button type="submit">
                <i class="fas fa-paper-plane"></i>
                Submit
            </button>
        </form>
    </div>
</body>
</html>
