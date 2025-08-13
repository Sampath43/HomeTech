<?php
$page_title = 'Deals';
require_once 'header.php';
?>
<style>
    .deals-header {
        background: linear-gradient(45deg, #2874f0, #ff9f00);
        color: white;
        padding: 4rem 1rem;
        text-align: center;
    }
    .deal-card {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .deal-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
</style>

<div class="deals-header">
    <div class="container">
        <h1 class="display-4">Exclusive Deals</h1>
        <p class="lead">Save big on your favorite home tech with our limited-time offers!</p>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 deal-card">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Smart Refrigerators</h5>
                    <p class="card-text">Up to <strong>30% OFF</strong> on select models. Keep your food fresher for longer!</p>
                    <a href="products.php?category=refrigerator" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 deal-card">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">4K Televisions</h5>
                    <p class="card-text">Get a cinematic experience at home. Flat <strong>₹10,000 OFF</strong>!</p>
                    <a href="products.php?category=television" class="btn btn-primary">Explore TVs</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 deal-card">
                <div class="card-body text-center p-4">
                    <h5 class="card-title">Air Conditioners</h5>
                    <p class="card-text">Stay cool this summer with our energy-efficient ACs. Free installation included!</p>
                    <a href="products.php?category=air+conditioner" class="btn btn-primary">View ACs</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
