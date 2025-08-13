<?php 
$page_title = 'HOMETECH - Welcome';
require_once 'header.php'; 
?>

    <div class="Promotion">
      <img src="Images/lg-banne3.webp">
    </div>
    <!--Carousel-->
    <div id="carouselExampleControls" class="carousel carousel-dark slide" data-bs-ride="carousel">
      <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="Images/IFB-Vendor-Banner.jpeg" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="Images/Whirlpool-V-Banner.jpg" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="Images/vendor-banner-template-3.jpg" class="d-block w-100" alt="...">
          </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!--Companies-->
    <div class="Companies">
      <div class="Brand-Page">
        <a href="Samsung.html"><img src="Images/samsung-seeklogo.png"></a>
      </div>
      <div class="Brand-Page">
        <a href="LG.html"><img src="Images/lg-electronics-seeklogo.png"></a>
      </div>
      <div class="Brand-Page">
        <a href="Bosch.html"><img src="Images/bosch-seeklogo.png"></a>
      </div>
      <div class="Brand-Page">
        <a href="whirpool.html"><img src="Images/whirpool-seeklogo.png"></a>
      </div>
      <div class="Brand-Page">
        <a href="IFB.html"><img src="Images/ifb-logo-png_seeklogo-311671.png"></a>
      </div>
      <div class="Brand-Page">
        <a href="Voltas.html"><img src="Images/voltas-logo_brandlogos.net_rmdfr.png"></a>
      </div>
    </div>

<!--Product Cards-->
<div class="brand-section"><h2><?php echo isset($_GET['search']) && trim($_GET['search']) ? 'Search Results' : 'BEST SELLERS'; ?></h2></div>
<div class="Products">
  <?php 
    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
    if ($searchQuery !== '') {
        $displayProducts = array_filter($products, function($product) use ($searchQuery) {
            return stripos($product['name'], $searchQuery) !== false || stripos($product['description'] ?? '', $searchQuery) !== false;
        });
    } else {
        // Show at most 4 random products for BEST SELLERS
        $displayProducts = $products;
        shuffle($displayProducts);
        $displayProducts = array_slice($displayProducts, 0, 4);
    }
  
  if ($displayProducts && count($displayProducts) > 0): ?>
    <?php foreach (array_reverse($displayProducts) as $product): ?>
      <a href="product-details.php?id=<?php echo urlencode($product['id']); ?>" class="Product-Card">
        <div class="Product-Image">
          <img src="<?php echo htmlspecialchars(isset($product['images']) && !empty($product['images']) ? $product['images'][0] : $product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>
        <div class="Content">
          <p><?php echo htmlspecialchars($product['name']); ?></p>
        </div>
      </a>
    <?php endforeach; ?>
  <?php else: ?>
    <p style="text-align:center;width:100%;">No products found.</p>
  <?php endif; ?>
</div>

<?php require_once 'footer.php'; ?>