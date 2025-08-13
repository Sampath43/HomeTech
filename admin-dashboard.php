<?php
require_once 'init.php';

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.html?error=1');
    exit();
}

$users = json_decode(file_get_contents('users.json'), true);

// Handle product deletion, editing, and adding
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_product'])) {
        $edit_index = (int)$_POST['edit_product'];
        if (isset($products[$edit_index])) {
            $products[$edit_index]['name'] = trim($_POST['edit_name']);
            $products[$edit_index]['description'] = trim($_POST['edit_description']);
            $products[$edit_index]['category'] = trim($_POST['edit_category']);
            $products[$edit_index]['price'] = floatval($_POST['edit_price']);
            $images = isset($_POST['edit_images']) ? array_filter(array_map('trim', explode("\n", $_POST['edit_images']))) : [];
            if (!empty($images)) {
                $products[$edit_index]['images'] = $images;
                $products[$edit_index]['image'] = $images[0];
            } else {
                $products[$edit_index]['image'] = trim($_POST['edit_image']);
                unset($products[$edit_index]['images']);
            }
            file_put_contents('products.json', json_encode($products, JSON_PRETTY_PRINT));
            header('Location: admin-dashboard.php?edited=1');
            exit();
        }
    }

    if (isset($_POST['delete_product'])) {
        array_splice($products, (int)$_POST['delete_product'], 1);
        file_put_contents('products.json', json_encode($products, JSON_PRETTY_PRINT));
        header('Location: admin-dashboard.php?deleted=1');
        exit();
    }

    if (isset($_POST['add_product'])) {
        $new_product = [
            'id' => uniqid('prod_', true),
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'category' => trim($_POST['category']),
            'price' => floatval($_POST['price'])
        ];
        $images = isset($_POST['images']) ? array_filter(array_map('trim', explode("\n", $_POST['images']))) : [];
        if (!empty($images)) {
            $new_product['images'] = $images;
            $new_product['image'] = $images[0];
        } else {
            $new_product['image'] = trim($_POST['image']);
        }
        $products[] = $new_product;
        file_put_contents('products.json', json_encode($products, JSON_PRETTY_PRINT));
        header('Location: admin-dashboard.php?success=1');
        exit();
    }

    if (isset($_POST['delete_user'])) {
        $users = array_values(array_filter($users, fn($user) => $user['username'] !== $_POST['delete_user']));
        file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));
        header('Location: admin-dashboard.php?userdeleted=1');
        exit();
    }
}

$page_title = 'Admin Dashboard';
require_once 'header.php';
?>
<style>
    .dashboard-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 15px;
    }
    .dashboard-section {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.05);
        padding: 24px;
        margin-bottom: 24px;
    }
    .dashboard-section h2 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
    }
</style>

<div class="dashboard-container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>. Manage your store below.</p>

    <div class="dashboard-section">
        <h2>Manage Products</h2>
        <?php if (isset($_GET['success'])): ?><div class="alert alert-success">Product added.</div><?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?><div class="alert alert-warning">Product deleted.</div><?php endif; ?>
        <?php if (isset($_GET['edited'])): ?><div class="alert alert-info">Product updated.</div><?php endif; ?>
        
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add New Product</button>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php foreach ($products as $i => $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category']); ?></td>
                        <td>₹<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal-<?php echo $i; ?>">Edit</button>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                <button type="submit" name="delete_product" value="<?php echo $i; ?>" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Manage Users</h2>
        <?php if (isset($_GET['userdeleted'])): ?><div class="alert alert-warning">User deleted.</div><?php endif; ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr><th>Username</th><th>Role</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td>
                            <?php if ($user['role'] !== 'admin'): ?>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                <button type="submit" name="delete_user" value="<?php echo htmlspecialchars($user['username']); ?>" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Product Name" required></div>
          <div class="mb-3"><textarea name="description" class="form-control" placeholder="Description" required></textarea></div>
          <div class="mb-3"><input type="text" name="category" class="form-control" placeholder="Category" required></div>
          <div class="mb-3"><input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required></div>
          <div class="mb-3"><input type="text" name="image" class="form-control" placeholder="Main Image URL"></div>
          <div class="mb-3"><textarea name="images" class="form-control" rows="3" placeholder="Additional Image URLs (one per line)"></textarea></div>
          <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Product Modals -->
<?php foreach ($products as $i => $product): ?>
<div class="modal fade" id="editProductModal-<?php echo $i; ?>" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Product</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="post">
          <input type="hidden" name="edit_product" value="<?php echo $i; ?>">
          <div class="mb-3"><input type="text" name="edit_name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required></div>
          <div class="mb-3"><textarea name="edit_description" class="form-control" required><?php echo htmlspecialchars($product['description']); ?></textarea></div>
          <div class="mb-3"><input type="text" name="edit_category" class="form-control" value="<?php echo htmlspecialchars($product['category']); ?>" required></div>
          <div class="mb-3"><input type="number" step="0.01" name="edit_price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required></div>
          <div class="mb-3"><input type="text" name="edit_image" class="form-control" value="<?php echo htmlspecialchars($product['image']); ?>"></div>
          <div class="mb-3"><textarea name="edit_images" class="form-control" rows="3"><?php echo isset($product['images']) ? htmlspecialchars(implode("\n", $product['images'])) : ''; ?></textarea></div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endforeach; ?>

<?php require_once 'footer.php'; ?>
