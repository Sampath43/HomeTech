<?php
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $mobile = trim($_POST['mobile'] ?? '');

    if (empty($username) || empty($password) || empty($confirm_password) || empty($mobile)) {
        $error_message = 'All fields are required.';
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $error_message = 'Invalid mobile number. Please enter 10 digits.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        $users = file_exists('users.json') ? json_decode(file_get_contents('users.json'), true) : [];
        if (!is_array($users)) $users = [];
        
        $exists = false;
        foreach ($users as $user) {
            if (isset($user['username']) && strtolower($user['username']) === strtolower($username)) {
                $exists = true;
                break;
            }
        }

        if ($exists) {
            $error_message = 'Username already exists. Please choose another.';
        } else {
            $users[] = [
                "username" => $username,
                "password" => $password, // Storing plain text, consider hashing
                "mobile" => $mobile,
                "role" => "user"
            ];
            file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
            header('Location: signin.php?register=success');
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | HOMETECH</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      min-height: 100vh;
      background-image: url('Images/andrea-davis-Wj6oZNCAzs4-unsplash.jpg');
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
    <source src="Images/3735225-hd_1920_1080_25fps.mp4" type="video/mp4">
  </video>
  <div class="auth-container">
    <div class="auth-card">
      <h1>Create an Account</h1>
      <p>Join us for exclusive deals and offers</p>
      <?php if(!empty($error_message)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($error_message); ?>
        </div>
      <?php endif; ?>
      <form method="post" action="register.php" autocomplete="off">
        <input type="text" name="username" class="form-control" placeholder="Username" required autofocus value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
        <input type="tel" name="mobile" class="form-control" placeholder="Mobile Number" required pattern="[0-9]{10}" value="<?php echo htmlspecialchars($_POST['mobile'] ?? ''); ?>">
        <button type="submit" class="btn auth-btn">Register</button>
      </form>
      <div class="auth-links">
        <a href="signin.php">Already have an account? Sign In</a> | 
        <a href="index.php">Back to Home</a>
      </div>
    </div>
  </div>
</body>
</html>
