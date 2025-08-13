<?php
$page_title = 'Services';
require_once 'header.php';
?>
<style>
    .services-header {
        background-color: #f8f9fa;
        padding: 4rem 1rem;
        text-align: center;
        border-bottom: 1px solid #e9ecef;
    }
    .service-icon {
        font-size: 3rem;
        color: #2874f0;
    }
    .service-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1);
    }
</style>
<div class="services-header">
    <div class="container">
        <h1 class="display-4">Our Services</h1>
        <p class="lead">We offer a range of services to ensure your home tech runs smoothly.</p>
    </div>
</div>

<div class="container my-5">
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 service-card">
                <div class="card-body p-4">
                    <div class="service-icon mb-3">🔧</div>
                    <h5 class="card-title">Expert Installation</h5>
                    <p class="card-text">Our certified technicians provide professional installation for all major appliances, ensuring optimal performance from day one.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 service-card">
                <div class="card-body p-4">
                    <div class="service-icon mb-3">🛡️</div>
                    <h5 class="card-title">Extended Warranty</h5>
                    <p class="card-text">Protect your investments with our extended warranty plans. Enjoy peace of mind with coverage beyond the manufacturer's warranty.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 service-card">
                <div class="card-body p-4">
                    <div class="service-icon mb-3">🚚</div>
                    <h5 class="card-title">Same-Day Delivery</h5>
                    <p class="card-text">Need it now? We offer same-day delivery on in-stock items for orders placed before 2 PM in select locations.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
