<?php
require_once 'init.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
$addressFile = 'address_' . md5($username) . '.json';
$savedAddress = file_exists($addressFile) ? json_decode(file_get_contents($addressFile), true) : null;
$cartFile = 'cart_' . md5($username) . '.json';
$cart = file_exists($cartFile) ? json_decode(file_get_contents($cartFile), true) : [];
$orderHistoryFile = 'orders_' . md5($username) . '.json';
$orderHistory = file_exists($orderHistoryFile) ? json_decode(file_get_contents($orderHistoryFile), true) : [];

// Handle address update
if (isset($_POST['edit_address'])) {
    $newAddress = [
        'fullname' => trim($_POST['fullname']),
        'address' => trim($_POST['address']),
        'city' => trim($_POST['city']),
        'state' => trim($_POST['state']),
        'pincode' => trim($_POST['pincode']),
        'phone' => trim($_POST['phone'])
    ];
    file_put_contents($addressFile, json_encode($newAddress, JSON_PRETTY_PRINT));
    header('Location: profile.php?address_updated=1');
    exit();
}

// Handle password change (dummy, for demo)
$passwordChanged = false;
if (isset($_POST['change_password'])) {
    // In real app, update password in DB
    $passwordChanged = true;
}

$page_title = 'My Profile';
require_once 'header.php';
?>
<style>
    .profile-container {
      max-width: 1200px;
      margin: 40px auto;
      display: flex;
      gap: 32px;
      align-items: flex-start;
      padding: 0 15px;
    }
    .profile-sidebar {
      width: 280px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 12px rgba(40,116,240,0.08);
      padding: 24px;
      text-align: center;
    }
    .profile-main {
      flex: 1;
    }
    .profile-avatar { 
        width: 100px; 
        height: 100px; 
        border-radius: 50%; 
        background: #e9ecef; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        font-size: 3rem; 
        color: #444; 
        margin: 0 auto 16px auto;
    }
    .profile-username { font-size: 1.4rem; font-weight: 600; color: #212121; margin-bottom: 8px; }
    .profile-email { font-size: 1rem; color: #878787; margin-bottom: 20px; }
    .profile-nav a {
        display: block;
        padding: 12px 15px;
        color: #212121;
        text-decoration: none;
        border-radius: 8px;
        margin-bottom: 8px;
        font-weight: 500;
        text-align: left;
        transition: background-color 0.2s, color 0.2s;
    }
    .profile-nav a:hover, .profile-nav a.active {
        background-color: #2874f0;
        color: #fff;
    }
    .profile-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(40,116,240,0.08);
        padding: 24px;
        margin-bottom: 24px;
    }
    .profile-section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #212121;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }
    .address-details p, .order-item p, .cart-item p {
        margin-bottom: 5px;
    }
</style>

<div class="profile-container">
    <aside class="profile-sidebar">
        <div class="profile-avatar">
            <span>&#128100;</span>
        </div>
        <h2 class="profile-username"><?php echo htmlspecialchars($username); ?></h2>
        <p class="profile-email"><?php echo $email ? htmlspecialchars($email) : 'Email not set'; ?></p>
        <nav class="profile-nav">
            <a href="#account-info" class="active">Account Information</a>
            <a href="#order-history">Order History</a>
            <a href="#current-cart">Current Cart</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>
    <main class="profile-main">
        <section id="account-info" class="profile-section">
            <h3 class="profile-section-title">Account Information</h3>
            <?php if(isset($_GET['address_updated'])): ?>
                <div class="alert alert-success">Address updated successfully!</div>
            <?php endif; ?>
            <div class="address-details">
                <h4>Shipping Address</h4>
                <?php if ($savedAddress): ?>
                    <p><strong><?php echo htmlspecialchars($savedAddress['fullname']); ?></strong></p>
                    <p><?php echo htmlspecialchars($savedAddress['address']) . ', ' . htmlspecialchars($savedAddress['city']) . ', ' . htmlspecialchars($savedAddress['state']) . ' - ' . htmlspecialchars($savedAddress['pincode']); ?></p>
                    <p>Phone: <?php echo htmlspecialchars($savedAddress['phone']); ?></p>
                    <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#editAddressModal">Edit Address</button>
                <?php else: ?>
                    <p>No address saved. <button class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#editAddressModal">Add one now</button></p>
                <?php endif; ?>
            </div>
            <hr class="my-4">
            <div>
                <h4>Security</h4>
                <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Change Password</button>
            </div>
        </section>

        <section id="order-history" class="profile-section">
            <h3 class="profile-section-title">Order History</h3>
            <?php if ($orderHistory && count($orderHistory) > 0): ?>
                <?php foreach ($orderHistory as $order): ?>
                    <div class="order-item mb-3 p-3 border rounded">
                        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
                        <p><strong>Date:</strong> <?php echo htmlspecialchars($order['date']); ?></p>
                        <p><strong>Items:</strong> <?php echo htmlspecialchars(implode(', ', array_column($order['items'], 'name'))); ?></p>
                        <p><strong>Total:</strong> ₹<?php echo number_format($order['total'],2); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>You have no past orders.</p>
            <?php endif; ?>
        </section>

        <section id="current-cart" class="profile-section">
            <h3 class="profile-section-title">Current Cart</h3>
            <?php if ($cart && count($cart) > 0): ?>
                 <?php foreach ($cart as $item): ?>
                    <div class="cart-item mb-3 p-3 border rounded">
                        <p><strong><?php echo htmlspecialchars($item['name']); ?></strong> - ₹<?php echo number_format($item['price'],2); ?> x <?php echo $item['quantity'] ?? 1; ?></p>
                    </div>
                <?php endforeach; ?>
                <a href="cart.php" class="btn btn-primary mt-2">View Full Cart</a>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </section>
    </main>
</div>

<!-- Edit Address Modal -->
<div class="modal fade" id="editAddressModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Address</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="mb-3"><input type="text" name="fullname" class="form-control" placeholder="Full Name" required value="<?php echo $savedAddress ? htmlspecialchars($savedAddress['fullname']) : ''; ?>"></div>
          <div class="mb-3"><input type="text" name="address" class="form-control" placeholder="Address Line" required value="<?php echo $savedAddress ? htmlspecialchars($savedAddress['address']) : ''; ?>"></div>
          <div class="mb-3"><input type="text" name="city" class="form-control" placeholder="City" required value="<?php echo $savedAddress ? htmlspecialchars($savedAddress['city']) : ''; ?>"></div>
          <div class="mb-3"><input type="text" name="state" class="form-control" placeholder="State" required value="<?php echo $savedAddress ? htmlspecialchars($savedAddress['state']) : ''; ?>"></div>
          <div class="mb-3"><input type="text" name="pincode" class="form-control" placeholder="Pincode" required value="<?php echo $savedAddress ? htmlspecialchars($savedAddress['pincode']) : ''; ?>"></div>
          <div class="mb-3"><input type="text" name="phone" class="form-control" placeholder="Phone Number" required value="<?php echo $savedAddress ? htmlspecialchars($savedAddress['phone']) : ''; ?>"></div>
          <button type="submit" name="edit_address" class="btn btn-primary">Save Address</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Change Password</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php if ($passwordChanged): ?><div class="alert alert-success">Password changed successfully (demo only).</div><?php endif; ?>
        <form method="post">
          <div class="mb-3"><input type="password" name="new_password" class="form-control" placeholder="New Password" required></div>
          <div class="mb-3"><input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required></div>
          <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
    // Smooth scrolling for profile navigation
    document.querySelectorAll('.profile-nav a').forEach(anchor => {
        if(anchor.getAttribute('href').startsWith('#')) {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
                document.querySelectorAll('.profile-nav a').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            });
        }
    });
</script>

<?php require_once 'footer.php'; ?>
