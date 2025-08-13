<?php
require_once 'init.php';

if (!isset($_SESSION['username'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['username'];
$cartFile = 'cart_' . md5($username) . '.json';
$cart = file_exists($cartFile) ? json_decode(file_get_contents($cartFile), true) : [];

if (empty($cart)) {
    header('Location: cart.php?empty=1');
    exit();
}

$total = 0;
foreach ($cart as $product) {
    $total += (isset($product['price']) ? floatval($product['price']) : 0) * (isset($product['quantity']) ? intval($product['quantity']) : 1);
}

$addressFile = 'address_' . md5($username) . '.json';
$savedAddress = file_exists($addressFile) ? json_decode(file_get_contents($addressFile), true) : null;

// Handle address form submission
if (isset($_POST['save_address'])) {
    $addressData = [
        'fullname' => trim($_POST['fullname']),
        'address' => trim($_POST['address']),
        'city' => trim($_POST['city']),
        'state' => trim($_POST['state']),
        'pincode' => trim($_POST['pincode']),
        'phone' => trim($_POST['phone'])
    ];
    $_SESSION['checkout_address'] = $addressData;
    file_put_contents($addressFile, json_encode($addressData, JSON_PRETTY_PRINT));
    header('Location: checkout.php');
    exit();
}

if (isset($_POST['use_saved_address']) && $savedAddress) {
    $_SESSION['checkout_address'] = $savedAddress;
    header('Location: checkout.php');
    exit();
}

// Handle final payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    $orderHistoryFile = 'orders_' . md5($username) . '.json';
    $orderHistory = file_exists($orderHistoryFile) ? json_decode(file_get_contents($orderHistoryFile), true) : [];

    $newOrder = [
        'id' => uniqid('ord_'),
        'date' => date('Y-m-d H:i:s'),
        'items' => $cart,
        'total' => $total,
        'shipping_address' => $_SESSION['checkout_address'],
        'status' => 'Processing'
    ];

    $orderHistory[] = $newOrder;
    file_put_contents($orderHistoryFile, json_encode($orderHistory, JSON_PRETTY_PRINT));

    // Clear the cart and session address
    file_put_contents($cartFile, json_encode([], JSON_PRETTY_PRINT));
    unset($_SESSION['checkout_address']);

    header('Location: orders.php?order_success=1');
    exit();
}

$page_title = 'Checkout';
require_once 'header.php';
$checkout_step = isset($_SESSION['checkout_address']) ? 'payment' : 'address';
?>
<style>
    .checkout-container {
        max-width: 1200px;
        margin: 40px auto;
        display: flex;
        flex-wrap: wrap;
        gap: 32px;
        padding: 0 15px;
    }
    .checkout-main {
        flex: 2;
        min-width: 300px;
    }
    .checkout-sidebar {
        flex: 1;
        min-width: 300px;
    }
    .checkout-step-box, .order-summary-box {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 24px;
    }
    .checkout-step-box h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .order-summary-box h3 {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .saved-address-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .saved-address-item {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .summary-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .summary-total {
        font-weight: 700;
        font-size: 1.2rem;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }
    .payment-options .nav-link {
        color: #333;
    }
    .payment-options .nav-link.active {
        color: #fff;
        background-color: #2874f0;
    }
    .payment-details {
        padding: 20px;
        border: 1px solid #ddd;
        border-top: none;
        border-radius: 0 0 8px 8px;
    }
    @media (max-width: 768px) {
        .checkout-container {
            flex-direction: column-reverse;
        }
    }
</style>

<div class="checkout-container">
    <main class="checkout-main">
        <div class="checkout-step-box">
            <?php if ($checkout_step === 'address'): ?>
                <h2>Step 1: Shipping Address</h2>
                <?php if ($savedAddress && is_array($savedAddress) && !empty($savedAddress)): ?>
                    <div class="saved-address-box">
                        <h4>Use Saved Address</h4>
                        <form method="post">
                            <div class="form-check saved-address-item">
                                <input class="form-check-input" type="radio" name="selected_address" value="saved" id="addr_saved" checked>
                                <label class="form-check-label" for="addr_saved">
                                    <strong><?php echo htmlspecialchars($savedAddress['fullname']); ?></strong><br>
                                    <?php echo htmlspecialchars($savedAddress['address']); ?>, <?php echo htmlspecialchars($savedAddress['city']); ?>, <?php echo htmlspecialchars($savedAddress['state']); ?> - <?php echo htmlspecialchars($savedAddress['pincode']); ?><br>
                                    Phone: <?php echo htmlspecialchars($savedAddress['phone']); ?>
                                </label>
                            </div>
                            <button type="submit" name="use_saved_address" class="btn btn-primary mt-3">Deliver Here</button>
                        </form>
                    </div>
                    <hr class="my-4">
                    <h4>Or Enter a New Address</h4>
                <?php endif; ?>
                <form method="post">
                    <div class="mb-3"><input type="text" name="fullname" class="form-control" placeholder="Full Name" required></div>
                    <div class="mb-3"><input type="text" name="address" class="form-control" placeholder="Address Line" required></div>
                    <div class="mb-3"><input type="text" name="city" class="form-control" placeholder="City" required></div>
                    <div class="mb-3"><input type="text" name="state" class="form-control" placeholder="State" required></div>
                    <div class="mb-3"><input type="text" name="pincode" class="form-control" placeholder="Pincode" required></div>
                    <div class="mb-3"><input type="text" name="phone" class="form-control" placeholder="Phone Number" required></div>
                    <button type="submit" name="save_address" class="btn btn-success w-100">Save and Continue</button>
                </form>
            <?php else: // Payment Step ?>
                <h2>Step 2: Payment</h2>
                <div class="address-summary mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h4>Delivering to:</h4>
                        <p class="mb-0"><?php echo htmlspecialchars($_SESSION['checkout_address']['fullname']); ?>, <?php echo htmlspecialchars($_SESSION['checkout_address']['address']); ?>, <?php echo htmlspecialchars($_SESSION['checkout_address']['city']); ?></p>
                    </div>
                    <a href="checkout.php?step=address" class="btn btn-sm btn-outline-primary">Change</a>
                </div>
                <form method="post" id="paymentForm">
                    <div class="payment-options">
                        <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                            <li class="nav-item" role="presentation"><a class="nav-link active" id="card-tab" data-bs-toggle="tab" href="#card-payment" role="tab">Credit/Debit Card</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" id="upi-tab" data-bs-toggle="tab" href="#upi-payment" role="tab">UPI</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" id="netbanking-tab" data-bs-toggle="tab" href="#netbanking-payment" role="tab">Net Banking</a></li>
                            <li class="nav-item" role="presentation"><a class="nav-link" id="cod-tab" data-bs-toggle="tab" href="#cod-payment" role="tab">Cash on Delivery</a></li>
                        </ul>
                        <div class="tab-content payment-details">
                            <div class="tab-pane fade show active" id="card-payment" role="tabpanel">
                                <div class="mb-3"><input type="text" class="form-control" placeholder="Card Number"></div>
                                <div class="row">
                                    <div class="col-md-6 mb-3"><input type="text" class="form-control" placeholder="MM/YY"></div>
                                    <div class="col-md-6 mb-3"><input type="text" class="form-control" placeholder="CVV"></div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="upi-payment" role="tabpanel">
                                <div class="mb-3"><input type="text" class="form-control" placeholder="Enter UPI ID"></div>
                            </div>
                            <div class="tab-pane fade" id="netbanking-payment" role="tabpanel">
                                <select class="form-select">
                                    <option selected>Choose your bank...</option>
                                    <option>State Bank of India</option>
                                    <option>HDFC Bank</option>
                                    <option>ICICI Bank</option>
                                    <option>Axis Bank</option>
                                </select>
                            </div>
                            <div class="tab-pane fade" id="cod-payment" role="tabpanel">
                                <p>You can pay in cash at the time of delivery.</p>
                            </div>
                        </div>
                    </div>
                <button type="submit" name="process_payment" class="btn btn-success w-100 mt-4">Pay ₹<?php echo number_format($total, 2); ?></button>
                </form>
            <?php endif; ?>
        </div>
    </main>
    <aside class="checkout-sidebar">
        <div class="order-summary-box">
            <h3>Order Summary</h3>
            <?php foreach($cart as $item): ?>
                <div class="summary-item">
                    <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity'] ?? 1; ?>)</span>
                    <span>₹<?php echo number_format($item['price'] * ($item['quantity'] ?? 1), 2); ?></span>
                </div>
            <?php endforeach; ?>
            <div class="summary-item summary-total">
                <span>Total</span>
                <span>₹<?php echo number_format($total, 2); ?></span>
            </div>
        </div>
    </aside>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var paymentTabs = new bootstrap.Tab(document.getElementById('card-tab'));
    paymentTabs.show();
});
</script>

<?php require_once 'footer.php'; ?>
