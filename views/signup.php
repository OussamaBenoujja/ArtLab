<?php
require_once '../control/Database.php';
require_once '../control/Authentication.php';
require_once '../phpmailer/mailer.php'; 

$database = new Database();
$pdo = $database->getConnection();

$profileImagePath = '../assets/img/default.jpg';

// Email functionality
$enableEmail = true; 

error_log("Script accessed.");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    error_log("Form submitted.");

    $username = htmlspecialchars(trim($_POST['username']));
    $email = htmlspecialchars(trim($_POST['email']));
    $bday = htmlspecialchars(trim($_POST['bday']));
    $firstName = htmlspecialchars(trim($_POST['first_name']));
    $lastName = htmlspecialchars(trim($_POST['last_name']));
    $password = htmlspecialchars(trim($_POST['password']));
    $accountType = htmlspecialchars(trim($_POST['account_type']));

    error_log("Username: $username, Email: $email, Account Type: $accountType");

    $profileImagePath = '../assets/img/default.jpg';

    if (isset($_FILES['pfpImage']) && $_FILES['pfpImage']['error'] == 0) {
        error_log("Profile image uploaded.");
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
                error_log("Profile image saved: $profileImagePath");
            } else {
                error_log("Failed to move uploaded file.");
            }
        } else {
            error_log("Invalid file type: $fileType");
        }
    }

    try {
        error_log("Attempting to register user.");
        $auth = new Authentication($pdo);
        $success = $auth->register($username, $firstName, $lastName, $email, $password, $accountType, $bday, "No Bio", $profileImagePath);

        if ($success) {
            error_log("User registration successful.");
            if ($enableEmail) {
                error_log("Email functionality enabled.");
                if ($accountType === 'author') {
                    $subject = "Welcome, Author!";
                    $message = "Dear $firstName $lastName,\n\nThank you for signing up as an author on our art blog! We encourage you to start writing articles and sharing your knowledge with our community.\n\nBest regards,\nThe Art Blog Team";
                } else {
                    $subject = "Welcome, Member!";
                    $message = "Dear $firstName $lastName,\n\nThank you for signing up as a member on our art blog! We encourage you to start reading articles and engaging with our community.\n\nBest regards,\nThe Art Blog Team";
                }

                $result = sendEmail($email, "$firstName $lastName", $subject, $message);

                if ($result === true) {
                    error_log("Email sent successfully.");
                    echo "<script>alert('Registration successful! Please check your email for further instructions.'); window.location.href = 'login.php';</script>";
                } else {
                    error_log("Email sending failed: $result");
                    echo "<script>alert('Registration successful, but there was an error sending the email. $result'); window.location.href = 'login.php';</script>";
                }
            } else {
                error_log("Email functionality disabled.");
                echo "<script>alert('Registration successful!'); window.location.href = 'login.php';</script>";
            }
        } else {
            error_log("User registration failed.");
            echo "<script>alert('Failed to register. Email or username may already be in use.');</script>";
        }
    } catch (Exception $e) {
        error_log("Error during registration: " . $e->getMessage());
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
} else {
    error_log("Form not submitted or signup button not pressed.");
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
<body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-md py-4">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Article Hub</h1>
            <nav>
                <ul class="flex space-x-4">
                    <li><a href="home.php" class="text-gray-600 hover:text-blue-600">Home</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="profile.php" class="text-gray-600 hover:text-blue-600">Profile</a></li>
                        <?php if ($_SESSION['user']['UserType'] === 'Admin'): ?>
                            <li><a href="dashboard.php" class="text-gray-600 hover:text-blue-600">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="logout.php" class="text-gray-600 hover:text-blue-600">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="text-gray-600 hover:text-blue-600">Login</a></li>
                        <li><a href="signup.php" class="text-gray-600 hover:text-blue-600">Signup</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-12">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
            <h1 class="text-2xl font-semibold text-center mb-6">Create Account</h1>
            <form action='signup.php' method='POST' enctype="multipart/form-data" class="space-y-4">
                <div class='width-full flex justify-center'>
                    <img id='pfpPreview' class='rounded-lg w-20' src='../assets/img/default.jpg'>    
                </div>
                <div>
                    <label for='username' class="block text-sm font-medium text-gray-700">Username</label>
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
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white shadow-md py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-600">
            <p>&copy; <?php echo date('Y'); ?> Article Hub. All rights reserved.</p>
        </div>
    </footer>

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