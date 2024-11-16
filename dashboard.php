<?php
session_start(); // Start the session

// Redirect to login page if user is not logged in
function checkUserLogin() {
    if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
        header('Location: index.php');
        exit;
    }
}

// Get sanitized email of the logged-in user
function getUserEmail() {
    return htmlentities($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8');
}

// Check if the user is logged in
checkUserLogin();
$email = getUserEmail();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styling adjustments for cards and layout */
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            margin-top: 50px;
            max-width: 1000px;
        }
        .action-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }
        .action-title {
            font-weight: 600;
            margin-bottom: 10px;
        }
        .action-text {
            color: #6c757d;
        }
        .btn-custom {
            background-color: #0d6efd;
            border: none;
        }
        .btn-custom:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="container dashboard-container">
        <!-- Dashboard Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-primary">Hello, <?= $email; ?>! Welcome to your Dashboard</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Dashboard Actions -->
        <div class="row">
            <!-- Card: Add Subject -->
            <div class="col-md-6 mb-4">
                <div class="card action-card">
                    <div class="card-body">
                        <h5 class="action-title">Add a New Subject</h5>
                        <p class="action-text">Manage your system by adding new subjects. Use this option to proceed to the subject creation form.</p>
                        <a href="#" class="btn btn-custom w-100">Add Subject</a>
                    </div>
                </div>
            </div>

            <!-- Card: Register Student -->
            <div class="col-md-6 mb-4">
                <div class="card action-card">
                    <div class="card-body">
                        <h5 class="action-title">Register a Student</h5>
                        <p class="action-text">Easily enroll students into the system. Click below to start the registration process.</p>
                        <a href="student/register.php" class="btn btn-custom w-100">Register Student</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
