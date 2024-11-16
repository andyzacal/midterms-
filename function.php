<?php
session_start(); // Start the session for managing user data

// Initialize session data with default values if not already set
$_SESSION['users'] = $_SESSION['users'] ?? [];
$_SESSION['subjects'] = $_SESSION['subjects'] ?? [];
$_SESSION['students'] = $_SESSION['students'] ?? [];
$_SESSION['registrations'] = $_SESSION['registrations'] ?? [];

// Helper function to validate email format
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Utility to retrieve session data by key
function fetchSessionData($key) {
    return $_SESSION[$key] ?? [];
}

// Utility to update session data by key
function updateSessionData($key, $data) {
    $_SESSION[$key] = $data;
}

// Function to create a new user
function createUser($email, $password) {
    if (!validateEmail($email)) {
        return "Invalid email format.";
    }
    if (empty($password)) {
        return "Password is required.";
    }

    $users = fetchSessionData('users');
    $users[] = ["email" => $email, "password" => $password];
    updateSessionData('users', $users);
    return "User successfully created.";
}

// Function to remove a user by email
function removeUser($email) {
    $users = &$_SESSION['users'];
    foreach ($users as $index => $user) {
        if ($user['email'] === $email) {
            unset($users[$index]);
            return "User removed successfully.";
        }
    }
    return "User not found.";
}

// Function to update a user's password
function changeUserPassword($email, $newPassword) {
    $users = &$_SESSION['users'];
    foreach ($users as &$user) {
        if ($user['email'] === $email) {
            $user['password'] = $newPassword;
            return "Password updated.";
        }
    }
    return "User not found.";
}

// Add a subject to the session
function createSubject($subjectDetails) {
    if (empty($subjectDetails)) {
        return "Subject information cannot be blank.";
    }
    $subjects = fetchSessionData('subjects');
    $subjects[] = $subjectDetails;
    updateSessionData('subjects', $subjects);
    return "Subject successfully added.";
}

// Remove a subject by its code
function removeSubject($subjectCode) {
    $subjects = &$_SESSION['subjects'];
    foreach ($subjects as $index => $subject) {
        if ($subject['subject_code'] === $subjectCode) {
            unset($subjects[$index]);
            return "Subject removed.";
        }
    }
    return "Subject not found.";
}

// Check if a student and subject exist for enrollment
function canEnrollStudent($studentId, $subjectCode) {
    $students = fetchSessionData('students');
    $subjects = fetchSessionData('subjects');

    $studentFound = array_filter($students, fn($s) => $s['student_id'] === $studentId);
    $subjectFound = array_filter($subjects, fn($s) => $s['subject_code'] === $subjectCode);

    return !empty($studentFound) && !empty($subjectFound);
}

// Enroll a student in a subject
function enrollStudent($studentId, $subjectCode) {
    if (canEnrollStudent($studentId, $subjectCode)) {
        $registrations = fetchSessionData('registrations');
        $registrations[] = ['student_id' => $studentId, 'subject_code' => $subjectCode];
        updateSessionData('registrations', $registrations);
        return "Student successfully enrolled.";
    }
    return "Invalid student or subject.";
}

// List all enrollments for a student
function listStudentEnrollments($studentId) {
    $registrations = fetchSessionData('registrations');
    return array_filter($registrations, fn($e) => $e['student_id'] === $studentId);
}

// Remove a student from a subject
function removeEnrollment($studentId, $subjectCode) {
    $registrations = &$_SESSION['registrations'];
    foreach ($registrations as $index => $registration) {
        if ($registration['student_id'] === $studentId && $registration['subject_code'] === $subjectCode) {
            unset($registrations[$index]);
            return "Enrollment removed.";
        }
    }
    return "Enrollment not found.";
}

// Check if user is authenticated
function isAuthenticated() {
    return !empty($_SESSION['email']);
}

// Logout the current user
function userLogout() {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Ensure the user is authenticated to access the page
function authenticateUser() {
    if (!isAuthenticated() && basename($_SERVER['PHP_SELF']) !== 'index.php') {
        header("Location: index.php");
        exit;
    }
}

// Apply authentication guard
authenticateUser();
?>
