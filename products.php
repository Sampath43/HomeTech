<?php
require_once 'init.php';

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['username'])) {
        header('Location: login.html');
        exit();
    }
    $cartFile = 'cart_' . md5($_SESSION['username']) . '.json';
    $cart = file_exists($cartFile) ? json_decode(file_get_contents($cartFile), true) : [];
    if (!is_array($cart)) { $cart = []; }
    $productId = $_POST['add_to_cart'];
    if (!isset($cart[$productId])) {
        foreach ($products as $product) {
            if ($product['id'] === $productId) {
                $cart[$productId] = $product;
                $cart[$productId]['quantity'] = 1;
                break;
            }
        }
    } else {
        $cart[$productId]['quantity']++;
    }
    file_put_contents($cartFile, json_encode($cart, JSON_PRETTY_PRINT));
    $queryString = $_SERVER['QUERY_STRING'];
    parse_str($queryString, $queryParams);
    $queryParams['added'] = '1';
    $newQueryString = http_build_query($queryParams);
    header('Location: products.php?' . $newQueryString);
    exit();
}

$page_title = 'Products';
require_once 'header.php';

$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? strtolower(trim($_GET['category'])) : '';
if ($category) {
    $filteredProducts = array_filter($products, function($product) use ($category) {
        if (!isset($product['category'])) return false;
        return stripos(strtolower($product['category']), $category) !== false;
    });
} elseif ($searchQuery !== '') {
    $filteredProducts = array_filter($products, function($product) use ($searchQuery) {
        return stripos($product['name'], $searchQuery) !== false || stripos($product['description'] ?? '', $searchQuery) !== false;
    });
} else {
    $filteredProducts = $products;
}
?>
<style>
    .products-main {
      position: relative;
      z-index: 1;
      max-width: 1300px;
      margin: 40px auto;
      display: flex;
      flex-wrap: wrap;
      gap: 32px;
      justify-content: center;
      padding: 0 15px;
    }
    .product-card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 12px rgba(40,116,240,0.08);
      width: 280px;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 24px 18px 18px 18px;
      transition: box-shadow 0.2s, transform 0.2s;
      position: relative;
      text-decoration: none;
      color: inherit;
    }
    .product-card:hover {
      box-shadow: 0 8px 32px rgba(40,116,240,0.18);
      transform: translateY(-4px) scale(1.03);
    }
    .product-img {
      width: 200px;
      height: 200px;
      background: #f7f7f7;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 18px;
      overflow: hidden;
    }
    .product-img img {
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
    }
    .product-title {
      font-size: 1.15rem;
      font-weight: 600;
      color: #212121;
      margin-bottom: 6px;
      text-align: center;
    }
    .product-desc {
      color: #757575;
      font-size: 0.98rem;
      margin-bottom: 8px;
      text-align: center;
      min-height: 40px;
    }
    .product-price {
      color: #2874f0;
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: 12px;
    }
    .product-actions {
      width: 100%;
      display: flex;
      justify-content: center;
      margin-top: auto;
    }
    .add-btn {
      background: #2874f0;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-weight: 600;
      padding: 10px 24px;
      font-size: 1.05rem;
      cursor: pointer;
      transition: background 0.2s;
    }
    .add-btn:hover {
      background: #1565c0;
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

<div class="brand-section"><h2><?php
if (isset($_GET['category'])) {
    echo htmlspecialchars(ucwords(str_replace('-', ' ', $_GET['category'])));
} elseif ($searchQuery) {
    echo 'Search Results';
} else {
    echo 'All Products';
}
?></h2></div>
<?php if (isset($_GET['added'])): ?>
  <div class="alert alert-success" style="text-align:center; max-width: 800px; margin: 0 auto 20px auto;">Product added to cart!</div>
<?php endif; ?>
<div class="products-main">
<?php if ($filteredProducts && count($filteredProducts) > 0): ?>
  <?php foreach ($filteredProducts as $product): ?>
    <div class="product-card">
      <a href="product-details.php?id=<?php echo urlencode($product['id']); ?>" style="text-decoration:none;color:inherit;display:flex;flex-direction:column;align-items:center;width:100%;">
        <div class="product-img">
          <img src="<?php echo htmlspecialchars(isset($product['images']) && !empty($product['images']) ? $product['images'][0] : $product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="product-title"><?php echo htmlspecialchars($product['name']); ?></div>
        <div class="product-desc"><?php echo htmlspecialchars($product['description']); ?></div>
        <div class="product-price">₹<?php echo isset($product['price']) ? number_format($product['price'],2) : '0.00'; ?></div>
      </a>
      <div class="product-actions">
        <form method="post">
          <button type="submit" name="add_to_cart" value="<?php echo htmlspecialchars($product['id']); ?>" class="add-btn">Add to Cart</button>
        </form>
      </div>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <p style="text-align:center;width:100%;">No products found.</p>
<?php endif; ?>
</div>
<?php require_once 'footer.php'; ?>
