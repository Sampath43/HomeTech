<?php
require_once 'init.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$username = $_SESSION['username'];
$orderHistoryFile = 'orders_' . md5($username) . '.json';
$orders = file_exists($orderHistoryFile) ? json_decode(file_get_contents($orderHistoryFile), true) : [];
if (!is_array($orders)) { $orders = []; }

// Sort orders by date, newest first
usort($orders, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

$page_title = 'My Orders';
require_once 'header.php';
?>
<style>
    .orders-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 15px;
    }
    .page-header {
        text-align: center;
        margin-bottom: 40px;
    }
    .order-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        margin-bottom: 25px;
    }
    .order-card-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
        border-radius: 10px 10px 0 0;
    }
    .order-card-body {
        padding: 20px;
    }
    .order-item {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .order-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    .order-item-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
    }
    .order-item-details {
        flex: 1;
    }
</style>

<div class="orders-container">
    <div class="page-header">
        <h1>My Orders</h1>
        <p class="lead">View your order history and track your current orders.</p>
    </div>

    <?php if (isset($_GET['order_success'])): ?>
        <div class="alert alert-success text-center">Your order has been placed successfully!</div>
    <?php endif; ?>

    <?php if (empty($orders)): ?>
        <div class="text-center">
            <h3>You have no orders yet.</h3>
            <p>Start shopping to see your orders here.</p>
            <a href="products.php" class="btn btn-primary">Go Shopping</a>
        </div>
    <?php else: ?>
        <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-card-header">
                    <div><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></div>
                    <div><strong>Date:</strong> <?php echo date('M d, Y', strtotime($order['date'])); ?></div>
                    <div><strong>Total:</strong> ₹<?php echo number_format($order['total'], 2); ?></div>
                    <div><strong>Status:</strong> <span class="badge bg-warning text-dark"><?php echo isset($order['status']) && $order['status'] !== null ? htmlspecialchars($order['status']) : 'Pending'; ?></span></div>
                </div>
                <div class="order-card-body">
                    <?php foreach ($order['items'] as $item): ?>
                        <div class="order-item">
                            <img src="<?php echo htmlspecialchars(isset($item['images']) && !empty($item['images']) ? $item['images'][0] : $item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="order-item-img">
                            <div class="order-item-details">
                                <h5><?php echo htmlspecialchars($item['name']); ?></h5>
                                <p>Quantity: <?php echo $item['quantity'] ?? 1; ?></p>
                                <p>Price: ₹<?php echo number_format($item['price'], 2); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
