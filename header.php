<?php require_once 'init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title ?? 'HOMETECH'; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.bundle.min.js"></script>
    <style>
      /* Modern Header & Navigation Styles */
      .modern-header {
        background: #2874f0;
        box-shadow: 0 2px 12px rgba(40,116,240,0.08);
        padding: 0;
        position: sticky;
        top: 0;
        z-index: 1000;
      }
      .header-container {
        max-width: 100vw;
        width: 100%;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        padding: 0 2vw;
        min-height: 72px;
      }
      .brand-name {
        font-size: 2.1rem;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        letter-spacing: 1px;
        margin-right: 32px;
      }
      .main-navigation {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 2vw;
        position: relative;
      }
      .nav-items {
        display: flex;
        align-items: center;
        gap: 1.5vw;
        margin: 0;
        list-style: none;
        padding: 0;
      }
      .nav-items li a {
        color: #fff;
        font-size: 1.08rem;
        font-weight: 500;
        text-decoration: none;
        padding: 8px 18px;
        border-radius: 6px;
        transition: background 0.18s, color 0.18s;
      }
      .nav-items li a:hover, .nav-items li.active a {
        background: #fff;
        color: #2874f0;
      }
      .nav-dropdown {
        position: relative;
      }
      .nav-dropdown > a {
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
      }
      .nav-dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background: #2874f0;
        border-radius: 10px;
        box-shadow: 0 4px 24px rgba(40,116,240,0.13);
        min-width: 210px;
        z-index: 9999;
        margin-top: 8px;
        padding: 10px 0;
        border: 1px solid #fff;
        animation: fadeIn 0.2s;
        opacity: 0;
        pointer-events: none;
        transition: all 0.22s cubic-bezier(.4,1.7,.6,.97);
      }
      .nav-dropdown:hover .nav-dropdown-menu {
        display: block;
        opacity: 1;
        pointer-events: auto;
        box-shadow: 0 12px 32px rgba(40,116,240,0.18);
        transform: scale(1.04);
      }
      .nav-dropdown-menu .dropdown-item {
        display: flex;
        align-items: center;
        padding: 12px 22px;
        color: #fff;
        font-weight: 500;
        font-size: 1.08rem;
        text-decoration: none;
        background: none;
        border: none;
        transition: background 0.18s, color 0.18s;
      }
      .nav-dropdown-menu .dropdown-item:hover {
        background: #fff;
        color: #000;
        border-radius: 6px;
      }
      .nav-dropdown-menu img {
        margin-right: 12px;
        width: 26px;
        height: 26px;
      }
      .nav-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-left: auto;
        justify-content: flex-end;
        flex-wrap: nowrap;
      }
      .nav-greeting {
        font-size: 1.05rem;
        color: #fff;
        font-weight: 500;
        margin-right: 8px;
        white-space: nowrap;
      }
      .nav-logout img, .nav-actions img {
        vertical-align: middle;
        border-radius: 50%;
        transition: box-shadow 0.18s;
        background: #fff;
        padding: 2px;
      }
      .nav-logout img:hover, .nav-actions img:hover {
        box-shadow: 0 2px 8px rgba(40,116,240,0.18);
        background: #2874f0;
      }
      @media (max-width: 900px) {
        .header-container, .main-navigation, .nav-items, .brand-section, .Products, .Companies, .site-footer, .footer-container {
          padding: 0 1vw;
          gap: 1vw;
        }
        .header-container {
          flex-direction: column;
          align-items: stretch;
          min-height: 56px;
        }
        .main-navigation {
          flex-direction: column;
          align-items: stretch;
          gap: 8px;
        }
        .nav-items {
          flex-direction: column;
          gap: 8px;
        }
        .nav-actions {
          justify-content: flex-end;
          flex-direction: row;
          gap: 10px;
        }
      }
      @media (max-width: 600px) {
        .header-container, .main-navigation, .nav-items, .brand-section, .Products, .Companies, .site-footer, .footer-container {
          padding: 0 0.5vw;
          gap: 0.5vw;
        }
        .header-container {
          flex-direction: column;
          align-items: stretch;
          min-height: 44px;
        }
        .main-navigation {
          flex-direction: column;
          align-items: stretch;
          gap: 6px;
        }
        .nav-items {
          flex-direction: column;
          gap: 6px;
        }
        .nav-actions {
          flex-direction: row;
          gap: 8px;
        }
      }
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }
    </style>
</head>
<body> 
<!-- Sleek Modern Header -->
<header class="modern-header">
  <div class="header-container">
    <a href="index.php" class="brand-name">HOMETECH</a>
    <nav class="main-navigation">
      <ul class="nav-items">
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Explore</a></li>
        <li class="nav-dropdown">
          <a href="#">Products <span style="font-size:1.1em;vertical-align:middle;">&#9662;</span></a>
          <div class="nav-dropdown-menu">
<?php
  $rawCategories = [];
  foreach ($products as $p) {
    if (isset($p['category']) && trim($p['category']) !== '') {
      $rawCategories[] = trim($p['category']);
    }
  }
  $uniqueMap = [];
  foreach ($rawCategories as $cat) {
    $norm = strtolower(trim($cat));
    if (!isset($uniqueMap[$norm])) {
      $uniqueMap[$norm] = $cat;
    }
  }
  $categories = array_values($uniqueMap);
  $categoryIcons = [];
  foreach ($categories as $cat) {
    $slug = strtolower(trim($cat));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    $categoryIcons[$cat] = 'Images/' . $slug . '.png';
  }
  if ($categories) {
    foreach ($categories as $cat) {
      $icon = isset($categoryIcons[$cat]) ? $categoryIcons[$cat] : 'Images/product.png';
      $iconPath = $icon;
      $iconExists = file_exists($iconPath);
      $tooltip = $iconExists ? $iconPath : (basename($iconPath) . ' (missing, using fallback)');
      $imgSrc = $iconExists ? $iconPath : 'Images/product.png';
      echo '<a class="dropdown-item" href="products.php?category=' . urlencode($cat) . '" title="' . htmlspecialchars($tooltip) . '"><img src="' . $imgSrc . '" style="width:22px;height:22px;margin-right:10px;vertical-align:middle;">' . htmlspecialchars($cat) . '</a>';
    }
  } else {
    echo '<span style="padding:12px 22px;color:#999;">No categories found</span>';
  }
?>
          </div>
        </li>
        <li><a href="deals.php">Deals</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="contact.php">Contact</a></li>
      </ul>
      <form method="get" action="products.php" style="display:flex;align-items:center;margin-left:18px;min-width:180px;max-width:220px;">
        <input type="text" name="search" class="search-bar" placeholder="Search..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" style="width:120px;font-size:0.98rem;padding:7px 10px;margin-right:6px;border-radius:6px;">
      </form>
    </nav>
    <div class="nav-actions">
      <a href="cart.php"><img src="Images/shopping-bag.png" alt="Cart" style="width:32px;height:32px;" /></a>
      <?php if (isset($_SESSION["username"])): ?>
        <a href="profile.php" title="Profile"><img src="Images/user.png" alt="Profile" style="width:32px;height:32px;" /></a>
        <a href="logout.php" class="nav-logout"><img src="Images/logout.png" alt="Logout" style="width:32px;height:32px;"></a>
        <span class="nav-greeting">Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?></span>
      <?php else: ?>
        <a href="signin.php"><img src="Images/user.png" alt="User" style="width:32px;height:32px;" /></a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main>
