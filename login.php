<?php
session_start();
// Load users from users.json
$users = json_decode(file_get_contents('users.json'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    foreach ($users as $user) {
        if ($user['username'] === $username && $user['password'] === $password) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];
            if ($user['role'] === 'admin') {
                header('Location: admin-dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit();
        }
    }
    $errorMsg = 'Invalid username or password.';
}

if (isset($_GET['error']) && $_GET['error'] == 1) {
    $errorMsg = 'Invalid username or password.';
}

// Show login form below
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | HOMETECH</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <style>
    body {
      min-height: 100vh;
      background: #f1f3f6;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-container {
      max-width: 420px;
      width: 100%;
      padding: 20px;
    }
    .login-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.05);
      padding: 40px;
      text-align: center;
    }
    .login-card h1 {
      color: #2874f0;
      margin-bottom: 10px;
      font-weight: 700;
      font-size: 2rem;
    }
    .login-card p {
        color: #6c757d;
        margin-bottom: 30px;
    }
    .form-control {
      border-radius: 8px;
      font-size: 1rem;
      padding: 12px 15px;
      margin-bottom: 15px;
    }
    .login-btn {
      width: 100%;
      background: #2874f0;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      padding: 12px 0;
      margin-top: 10px;
    }
    .login-links {
        margin-top: 20px;
        font-size: 0.9rem;
    }
    .login-links a {
        color: #2874f0;
        text-decoration: none;
    }
    .login-links a:hover {
        text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-logo">
        <span class="bi bi-person-circle"></span>
      </div>
      <h1>Sign In</h1>
      <p>Access your HOMETECH account</p>
      <form method="post" action="login.php" autocomplete="on">
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus aria-label="Username">
        <input type="password" name="password" class="form-control" placeholder="Password" required aria-label="Password">
        <button type="submit" class="btn login-btn">Login</button>
      </form>
      <div class="login-links">
        <a href="register.php">New user? Register</a>
        <a href="index.php">| Home</a>
      </div>
    </div>
  </div>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</body>
</html>
