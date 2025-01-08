<?php
require_once '../control/Database.php';
require_once '../control/Authentication.php';
require_once '../phpmailer/mailer.php'; // Include mailer.php

$database = new Database();
$pdo = $database->getConnection();

$profileImagePath = '../assets/img/default.jpg';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $bday = htmlspecialchars(trim($_POST['bday']));
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $password = htmlspecialchars(trim($_POST['password']));
    $accountType = htmlspecialchars(trim($_POST['account_type']));

    $profileImagePath = '../assets/img/default.jpg';

    if (isset($_FILES['pfpImage']) && $_FILES['pfpImage']['error'] == 0) {
        $targetDir = '../assets/img/';
        $fileType = pathinfo($_FILES['pfpImage']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $fileType;
        $targetFilePath = $targetDir . $fileName;
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');

        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            if (move_uploaded_file($_FILES['pfpImage']['tmp_name'], $targetFilePath)) {
                $profileImagePath = '../assets/img/' . $fileName;
            }
        }
    }

    try {
        $auth = new Authentication($pdo);
        $success = $auth->register($username, $firstName, $lastName, $email, $password, $accountType, $bday, "No Bio", $profileImagePath);

        if ($success) {
            if ($accountType === 'author') {
                $subject = "Welcome, Author!";
                $message = "Dear $firstName $lastName,\n\nThank you for signing up as an author on our art blog! We encourage you to start writing articles and sharing your knowledge with our community.\n\nBest regards,\nThe Art Blog Team";
            } else {
                $subject = "Welcome, Member!";
                $message = "Dear $firstName $lastName,\n\nThank you for signing up as a member on our art blog! We encourage you to start reading articles and engaging with our community.\n\nBest regards,\nThe Art Blog Team";
            }

            $result = sendEmail($email, "$firstName $lastName", $subject, $message);

            if ($result === true) {
                echo "<script>alert('Registration successful! Please check your email for further instructions.'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Registration successful, but there was an error sending the email. $result'); window.location.href = 'login.php';</script>";
            }
        } else {
            echo "<script>alert('Failed to register. Email or username may already be in use.');</script>";
        }
    } catch (Exception $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../src/output.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<main class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
    <h1 class="text-2xl font-semibold text-center mb-6">Create Account</h1>
    <form action='signup.php' method='POST' enctype="multipart/form-data" class="space-y-4">
        <div class='width-full flex justify-center'>
            <img id='pfpPreview' class='rounded-lg w-20' src='../assets/img/default.jpg'>    
        </div>
        <div>
            <label for='username' class="block text-sm font-medium text-gray-700">UserName</label>
            <input type='text' name='username' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='pfpImage' class="block text-sm font-medium text-gray-700">Profile Image</label>
            <input id='imageUpload' type='file' accept="image/*" name='pfpImage' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='email' class="block text-sm font-medium text-gray-700">Email</label>
            <input type='email' name='email' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='bday' class="block text-sm font-medium text-gray-700">Birth Date</label>
            <input type='date' name='bday' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='first_name' class="block text-sm font-medium text-gray-700">First Name</label>
            <input type='text' name='first_name' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='last_name' class="block text-sm font-medium text-gray-700">Last Name</label>
            <input type='text' name='last_name' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='password' class="block text-sm font-medium text-gray-700">Password</label>
            <input type='password' name='password' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
        </div>
        <div>
            <label for='account_type' class="block text-sm font-medium text-gray-700">Account Type</label>
            <select name='account_type' class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value='member'>Member</option>
                <option value='author'>Author</option>
            </select>
        </div>
        <button type='submit' name='signup' class="w-full bg-blue-600 text-white font-semibold py-2 rounded-md hover:bg-blue-700 transition duration-150">Sign Up</button>
    </form>
</main>
<script>
const imageInput = document.getElementById('imageUpload');
const previewImage = document.getElementById('pfpPreview');

imageInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewImage.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewImage.src = "../assets/img/default.jpg";
    }
});
</script>
</body>
</html>