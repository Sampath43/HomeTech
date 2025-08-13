<?php
$page_title = 'Contact Us';
require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact | HOMETECH</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <style>
    .contact-header {
        background-color: #f8f9fa;
        padding: 4rem 1rem;
        text-align: center;
        border-bottom: 1px solid #e9ecef;
    }
    .contact-form-section {
        background: #fff;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .contact-info-section {
        padding: 20px;
    }
  </style>
</head>
<body>
<div class="contact-header">
    <div class="container">
        <h1 class="display-4">Get in Touch</h1>
        <p class="lead">We'd love to hear from you. Contact us with any questions or feedback.</p>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="contact-form-section">
                <h3>Send us a Message</h3>
                <form>
                    <div class="mb-3">
                        <label for="contactName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="contactName" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="contactEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="contactMessage" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="contact-info-section">
                <h3>Contact Information</h3>
                <p><strong>Address:</strong> 123 Tech Avenue, Silicon Valley, CA 94000</p>
                <p><strong>Phone:</strong> (123) 456-7890</p>
                <p><strong>Email:</strong> support@hometech.com</p>
                <h4 class="mt-4">Business Hours</h4>
                <p>Monday - Friday: 9:00 AM - 8:00 PM</p>
                <p>Saturday - Sunday: 10:00 AM - 6:00 PM</p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
</body>
</html>
