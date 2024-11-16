<?php
session_start();

class UserAuthentication {
    private $users;

    public function __construct() {
        $this->users = $this->getUserData();
    }

    private function getUserData() {
        return [
            ["email" => "user1@gmail.com", "password" => "user1"],
            ["email" => "user2@gmail.com", "password" => "user2"],
            ["email" => "user3@example.com", "password" => "user3"]
        ];
    }

    public function validateCredentials($email, $password) {
        $errors = [];
        if (empty($email)) {
            $errors[] = "Please enter your email.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        }

        if (empty($password)) {
            $errors[] = "Password cannot be empty.";
        }

        return $errors;
    }

    public function isUserAuthenticated($email, $password) {
        foreach ($this->users as $user) {
            if ($user['email'] === $email && $user['password'] === $password) {
                return true;
            }
        }
        return false;
    }
}

$auth = new UserAuthentication();
$errorMessages = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    $errorMessages = $auth->validateCredentials($email, $password);

    if (empty($errorMessages)) {
        if ($auth->isUserAuthenticated($email, $password)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_email'] = $email;
            header("Location: dashboard.php");
            exit;
        } else {
            $errorMessages[] = "Incorrect email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php if (!empty($errorMessages)): ?>
        <div class="alert alert-danger">
            <strong>Error:</strong><br>
            <?= implode('<br>', $errorMessages); ?>
        </div>
    <?php endif; ?>

    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h5 class="card-title text-center">Login</h5>
            <form method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
