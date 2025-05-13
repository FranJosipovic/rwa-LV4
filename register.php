<?php
include 'includes/db.php';
include 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $username = $_POST['username'];
    $isAdmin = isset($_POST['isAdmin']) ? 1 : 0;  // Set isAdmin based on checkbox

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO user (email, password, username, isAdmin) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $email, $hashedPassword, $username, $isAdmin);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['isAdmin'] = $isAdmin;
        header('Location: index.php');
        exit();
    } else {
        $error = "Error: Could not create account. This email might already be registered.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
        body { font-family: Arial, sans-serif; }
        form { max-width: 300px; margin: 50px auto; }
        label { font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        a { text-decoration: none; color: #007bff; }
    </style>
</head>
<body>
<h2>Register</h2>
<form method="POST" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br>
    <label>Email:</label><br>
    <input type="email" name="email" required><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br>
    <label>
        <input type="checkbox" name="isAdmin" value="1">
        Register as Admin
    </label><br>
    <input type="submit" name="register" value="Register"><br>
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
</form>
<a href="login.php">Prijavi se</a>
</body>
</html>