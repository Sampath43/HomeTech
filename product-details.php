<?php
require_once 'init.php';

$product = null;
if (isset($_GET['id'])) {
    foreach ($products as $p) {
        if ($p['id'] === $_GET['id']) {
            $product = $p;
            break;
        }
    }
}

if (!$product) {
    $page_title = 'Error';
    require_once 'header.php';
    echo '<h2 style="text-align:center;margin-top:60px;">Product not found.</h2>';
    require_once 'footer.php';
    exit();
}

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['username'])) {
        header('Location: signin.php');
        exit();
    }
    $cartFile = 'cart_' . md5($_SESSION['username']) . '.json';
    $cart = file_exists($cartFile) ? json_decode(file_get_contents($cartFile), true) : [];
    
    $productId = $product['id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if (isset($cart[$productId])) {
        $cart[$productId]['quantity'] += $quantity;
    } else {
        $cart[$productId] = $product;
        $cart[$productId]['quantity'] = $quantity;
    }

    file_put_contents($cartFile, json_encode($cart, JSON_PRETTY_PRINT));
    header('Location: product-details.php?id=' . urlencode($product['id']) . '&added=1');
    exit();
}

// Handle buy now
if (isset($_POST['buy_now'])) {
    if (!isset($_SESSION['username'])) {
        header('Location: signin.php');
        exit();
    }
    $cartFile = 'cart_' . md5($_SESSION['username']) . '.json';
    $cart = [
        $product['id'] => [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1,
            'image' => $product['image'],
            'category' => $product['category'],
            'description' => $product['description'],
            'images' => $product['images'],
        ]
    ];

    file_put_contents($cartFile, json_encode($cart, JSON_PRETTY_PRINT));
    header('Location: checkout.php');
    exit();
}

$page_title = htmlspecialchars($product['name']);
require_once 'header.php';
?>
<style>
    .details-main {
      max-width: 1200px;
      margin: 40px auto;
      display: flex;
      gap: 48px;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 12px rgba(40,116,240,0.08);
      padding: 40px;
      align-items: flex-start;
    }
    .image-gallery {
        flex: 1;
        max-width: 450px;
    }
    .main-image-container {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 15px;
    }
    .main-image {
        width: 100%;
        height: 400px;
        object-fit: contain;
    }
    .thumbnail-container {
        display: flex;
        gap: 10px;
        justify-content: center;
    }
    .thumbnail {
        width: 80px;
        height: 80px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        object-fit: contain;
        transition: border-color 0.2s;
    }
    .thumbnail:hover, .thumbnail.active {
        border-color: #2874f0;
    }
    .details-info {
      flex: 1.5;
      padding-left: 20px;
    }
    .details-title {
      font-size: 2.2rem;
      font-weight: 700;
      color: #212121;
      margin-bottom: 10px;
    }
    .details-category {
      color: #878787;
      font-size: 1.1rem;
      margin-bottom: 15px;
    }
    .details-price {
      color: #212121;
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 10px;
    }
    .details-desc {
      color: #212121;
      font-size: 1.1rem;
      line-height: 1.6;
      margin-bottom: 20px;
    }
    .details-actions {
      margin-top: 20px;
    }
    .quantity-selector {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }
    .quantity-selector label {
        margin-right: 10px;
        font-weight: 600;
    }
    .quantity-selector input {
        width: 60px;
        text-align: center;
    }
    .details-add-btn {
      background: #ff9f00;
      color: #fff;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      padding: 16px 32px;
      font-size: 1.1rem;
      cursor: pointer;
      transition: background 0.2s;
      margin-right: 10px;
    }
    .buy-now-btn {
        background: #fb641b;
        color: #fff;
    }
    .details-add-btn:hover {
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

<div class="brand-section"><h2>PRODUCT DETAILS</h2></div>
<?php if (isset($_GET['added'])): ?>
  <div class="alert alert-success" style="text-align:center; max-width: 800px; margin: 0 auto 20px auto;">Product added to cart!</div>
<?php endif; ?>
<div class="details-main">
  <div class="image-gallery">
    <div class="main-image-container">
        <img src="<?php echo htmlspecialchars(isset($product['images']) && !empty($product['images']) ? $product['images'][0] : $product['image']); ?>" id="mainProductImg" class="main-image" alt="<?php echo htmlspecialchars($product['name']); ?>">
    </div>
    <?php if (!empty($product['images']) && is_array($product['images']) && count($product['images']) > 1): ?>
    <div class="thumbnail-container">
        <?php foreach ($product['images'] as $index => $img): ?>
            <img src="<?php echo htmlspecialchars($img); ?>" class="thumbnail <?php if ($index === 0) echo 'active'; ?>" onclick="showMainImage('<?php echo htmlspecialchars($img); ?>', this)">
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
  <div class="details-info">
    <h1 class="details-title"><?php echo htmlspecialchars($product['name']); ?></h1>
    <p class="details-category">Category: <?php echo htmlspecialchars($product['category']); ?></p>
    <h2 class="details-price">₹<?php echo isset($product['price']) ? number_format($product['price'],2) : '0.00'; ?></h2>
    <p class="details-desc"><?php echo htmlspecialchars($product['description']); ?></p>
    <form method="post" action="product-details.php?id=<?php echo urlencode($product['id']); ?>">
    <div class="quantity-selector">
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" class="form-control" value="1" min="1" max="10">
    </div>
    <div class="details-actions">
      <button type="submit" name="add_to_cart" class="details-add-btn">ADD TO CART</button>
      <button type="submit" name="buy_now" class="details-add-btn buy-now-btn">BUY NOW</button>
    </div>
  </form>
</div>
</div>

<script>
function showMainImage(src, el) {
  document.getElementById('mainProductImg').src = src;
  document.querySelectorAll('.thumbnail').forEach(img => img.classList.remove('active'));
  el.classList.add('active');
}
</script>
<?php require_once 'footer.php'; ?>
