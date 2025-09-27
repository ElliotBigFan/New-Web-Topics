<?php
// $FLAG, $USER and $PASSWORD_SHA256 in secret file
require("secret.php");

// show my source code
if(isset($_GET['source'])){
    show_source(__FILE__);
    die();
}

$return = null;
if (isset($_POST["auth"])) {
    $auth = @json_decode($_POST['auth'], true);

    if($auth['data']['login'] == $USER && !strcmp($auth['data']['password'], $PASSWORD_SHA256)){
        $return = "✅ Access granted! The validation password is: $FLAG";
    } else {
        $return = "❌ Authentication failed!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auth Lab</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-4">
                <div class="card-body bg-secondary text-light p-4">
                    <h3 class="text-center mb-4">🔐 JSON Auth Lab</h3>
                    <form method="POST" onsubmit="return preparePayload()">
                        <div class="mb-3">
                            <label for="login" class="form-label">Username</label>
                            <input type="text" class="form-control rounded-pill" id="login" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control rounded-pill" id="password" required>
                        </div>
                        <!-- hidden field actually sent -->
                        <input type="hidden" name="auth" id="auth">
                        <button type="submit" class="btn btn-warning w-100 rounded-pill">Login</button>
                    </form>

                    <?php if ($return !== null): ?>
                        <hr class="my-4 text-light">
                        <div class="alert alert-dark text-center rounded-3">
                            <?php echo htmlspecialchars($return); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function preparePayload() {
    let login = document.getElementById("login").value;
    let password = document.getElementById("password").value;

    let json = {
        "data": {
            "login": login,
            "password": password
        }
    };

    document.getElementById("auth").value = JSON.stringify(json);
    return true;
}
</script>
</body>
</html>
