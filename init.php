<?php
session_start();
// Ensure products data is loaded safely
$products_content = file_exists('products.json') ? file_get_contents('products.json') : '[]';
$products = json_decode($products_content, true);
if (!is_array($products)) {
    $products = [];
}
?>
