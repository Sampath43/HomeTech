<?php
require_once 'init.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.html');
    exit();
}

$cartFile = 'cart_' . md5($_SESSION['username']) . '.json';
$cart = file_exists($cartFile) ? json_decode(file_get_contents($cartFile), true) : [];

if (isset($_POST['remove']) && isset($cart[$_POST['remove']])) {
    unset($cart[$_POST['remove']]);
    file_put_contents($cartFile, json_encode($cart, JSON_PRETTY_PRINT));
    header('Location: cart.php');
    exit();
}

if (isset($_POST['update_quantity'])) {
    $productId = $_POST['product_id'];
    $quantity = intval($_POST['quantity']);
    if (isset($cart[$productId]) && $quantity > 0) {
        $cart[$productId]['quantity'] = $quantity;
        file_put_contents($cartFile, json_encode($cart, JSON_PRETTY_PRINT));
    }
    header('Location: cart.php');
    exit();
}

$total = 0;
foreach ($cart as $product) {
    $total += (isset($product['price']) ? floatval($product['price']) : 0) * (isset($product['quantity']) ? intval($product['quantity']) : 1);
}

$page_title = 'Your Cart';
require_once 'header.php';
?>
<style>
    .cart-main {
      max-width: 1200px;
      margin: 40px auto;
      display: flex;
      gap: 32px;
      align-items: flex-start;
      padding: 0 15px;
    }
    .cart-items {
      flex: 2;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 12px rgba(40,116,240,0.08);
      padding: 24px;
    }
    .cart-summary {
      flex: 1;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 12px rgba(40,116,240,0.08);
      padding: 24px;
      position: sticky;
      top: 100px;
    }
    .cart-product {
      display: flex;
      align-items: center;
      border-bottom: 1px solid #eee;
      padding: 20px 0;
      gap: 24px;
    }
    .cart-product:last-child { border-bottom: none; }
    .cart-product-img {
      width: 120px;
      height: 120px;
      background: #f7f7f7;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .cart-product-img img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .cart-product-details {
      flex: 1;
    }
    .cart-product-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #212121;
      margin-bottom: 8px;
    }
    .cart-product-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: #2874f0;
    }
    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 10px;
    }
    .quantity-selector input {
        width: 50px;
        text-align: center;
    }
    .cart-product-actions {
      margin-left: auto;
    }
    .cart-empty {
      text-align: center;
      color: #888;
      font-size: 1.2rem;
      padding: 60px 0;
    }
    .cart-summary h3 {
      font-size: 1.4rem;
      color: #212121;
      margin-bottom: 20px;
      border-bottom: 1px solid #eee;
      padding-bottom: 10px;
    }
    .cart-summary-total {
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 20px;
      color: #212121;
      display: flex;
      justify-content: space-between;
    }
    .cart-summary-btn {
      width: 100%;
      font-size: 1.1rem;
      padding: 14px 0;
      background: #fb641b;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: background 0.2s;
    }
    .cart-summary-btn:hover {
      opacity: 0.9;
    }
    .brand-section h2 {
      text-align: center;
      font-size: 2.5rem;
      margin-top: 40px;
      margin-bottom: 30px;
      font-weight: 700;
      color: #333;
    }
</style>

<div class="brand-section"><h2>YOUR SHOPPING CART</h2></div>

<div class="cart-main">
  <div class="cart-items">
    <?php if ($cart && count($cart) > 0): ?>
      <?php foreach ($cart as $key => $product): ?>
        <div class="cart-product">
          <div class="cart-product-img">
            <img src="<?php echo htmlspecialchars(isset($product['images']) && !empty($product['images']) ? $product['images'][0] : $product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
          </div>
          <div class="cart-product-details">
            <div class="cart-product-title"><?php echo htmlspecialchars($product['name']); ?></div>
            <div class="cart-product-price">₹<?php echo isset($product['price']) ? number_format($product['price'],2) : '0.00'; ?></div>
            <form method="post" class="quantity-selector">
                <input type="hidden" name="product_id" value="<?php echo $key; ?>">
                <label for="quantity-<?php echo $key; ?>">Quantity:</label>
                <input type="number" id="quantity-<?php echo $key; ?>" name="quantity" value="<?php echo isset($product['quantity']) ? $product['quantity'] : 1; ?>" min="1" class="form-control form-control-sm">
                <button type="submit" name="update_quantity" class="btn btn-secondary btn-sm">Update</button>
            </form>
          </div>
          <div class="cart-product-actions">
            <form method="post">
              <button type="submit" name="remove" value="<?php echo $key; ?>" class="btn btn-danger btn-sm">Remove</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="cart-empty">Your cart is empty. <a href="products.php">Continue shopping</a>.</div>
    <?php endif; ?>
  </div>
  <?php if ($cart && count($cart) > 0): ?>
  <div class="cart-summary">
    <h3>Price Details</h3>
    <div class="cart-summary-total">
        <span>Total</span>
        <span>₹<?php echo number_format($total,2); ?></span>
    </div>
    <button type="button" onclick="window.location.href='checkout.php'" class="cart-summary-btn" <?php if ($total == 0) echo 'disabled'; ?>>Proceed to Checkout</button>
  </div>
  <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>
