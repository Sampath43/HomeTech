<?php
// This is the sign in page, previously login.php
session_start();
$users = json_decode(file_get_contents('users.json'), true);
$errorMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $found = false;
    if (is_array($users)) {
        foreach ($users as $user) {
            // Assuming plain text password for now, but you should use password_verify
            if (isset($user['username']) && $user['username'] === $username && isset($user['password']) && $user['password'] === $password) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $user['role'] ?? 'user';
                if (($_SESSION['role']) === 'admin') {
                    header('Location: admin-dashboard.php');
                } else {
                    header('Location: index.php');
                }
                exit();
            }
        }
    }
    $errorMsg = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In | HOMETECH</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      min-height: 100vh;
      background-size: cover;
      background-position: center;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .auth-container {
      max-width: 450px;
      width: 100%;
      padding: 20px;
    }
    .auth-card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      border-radius: 16px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.1);
      padding: 40px;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .auth-card h1 {
      color: #1a2b4d;
      margin-bottom: 10px;
      font-weight: 700;
      font-size: 2.2rem;
    }
    .auth-card p {
        color: #555;
        margin-bottom: 30px;
    }
    .form-control {
      border-radius: 8px;
      font-size: 1rem;
      padding: 14px 20px;
      margin-bottom: 18px;
      border: 1px solid #ddd;
      background: #f8f9fa;
    }
    .form-control:focus {
      border-color: #2874f0;
      box-shadow: 0 0 0 3px rgba(40,116,240,0.15);
    }
    .auth-btn {
      width: 100%;
      background: #2874f0;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 1rem;
      padding: 14px 0;
      margin-top: 10px;
      transition: background-color 0.2s;
    }
    .auth-btn:hover {
      background-color: #0052cc;
    }
    .auth-links {
        margin-top: 25px;
        font-size: 0.95rem;
    }
    .auth-links a {
        color: #2874f0;
        text-decoration: none;
        font-weight: 500;
    }
    .auth-links a:hover {
        text-decoration: underline;
    }
  </style>
</head>
<body>
  <video autoplay muted loop playsinline id="bgVideo" style="position:fixed;top:0;left:0;width:100vw;height:100vh;object-fit:cover;z-index:-1;">
    <source src="Images/8581127-hd_1920_1080_30fps.mp4" type="video/mp4">
  </video>
  <div class="auth-container">
    <div class="auth-card">
      <h1>Welcome Back</h1>
      <p>Sign in to access your account</p>
      <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo htmlspecialchars($errorMsg); ?>
        </div>
      <?php endif; ?>
      <form method="post" action="signin.php" autocomplete="off">
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <button type="submit" class="btn auth-btn">Sign In</button>
      </form>
      <div class="auth-links">
        <a href="register.php">New here? Create an account</a> | 
        <a href="index.php">Back to Home</a>
      </div>
    </div>
  </div>
</body>
</html>
