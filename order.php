<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judalet’s Fire and Ice - Order</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div id="location-display" class="location-display-bar"></div>

    <div id="location-modal" class="modal">
        <div class="modal-content">
            <h3>Select Your Location</h3>
            <select id="location-select">
                <option value="Woodville">Woodville</option>
                <option value="Location2">Location 2</option>
            </select>
            <button id="confirm-location">Confirm</button>
        </div>
    </div>

    <header class="header">
        <div class="logo">
            <a href="index.php"><img src="logo.png" alt="Judalet’s Fire and Ice Logo" class="logo-img"></a>
        </div>
        <div class="tagline">Custom Drinks, Endless Flavors – Order Online!</div>
        <div class="right-section">
            <div class="contact">Toll-Free: 1-888-945-0911</div>
            <button class="menu-toggle">☰</button>
            <nav class="nav">
                <ul>
                    <li><a href="index.php" class="nav-icon" data-tooltip="Home"><i class="fas fa-home"></i></a></li>
                    <li><a href="order.php" class="nav-icon" data-tooltip="Order"><i class="fas fa-shopping-cart"></i></a></li>
                    <li><a href="rewards.php" class="nav-icon" data-tooltip="Rewards"><i class="fas fa-gift"></i></a></li>
                    <li><a href="community.php" class="nav-icon" data-tooltip="Community"><i class="fas fa-users"></i></a></li>
                    <li><a href="reviews.php" class="nav-icon" data-tooltip="Reviews"><i class="fas fa-star"></i></a></li>
                </ul>
            </nav>
            <button class="order-btn">Order Online</button>
        </div>
    </header>

    <main>
        <section class="order-section">
            <h2>Explore Our Drinks</h2>
            <?php
            $successMessage = "";
            include 'connect.php';
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST['add_to_cart'])) {
                    $product_id = $_POST['product_id'];
                    $quantity = $_POST['quantity'];
                    // Add to cart (placeholder)
                    header("Location: order.php?success=1");
                    exit();
                } elseif (isset($_POST['buy_now'])) {
                    $product_id = $_POST['product_id'];
                    $quantity = $_POST['quantity'];
                    header("Location: checkout.php?product_id=$product_id&quantity=$quantity");
                    exit();
                }
            }
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                $successMessage = "<p style='color:green;'>Action completed successfully!</p>";
            }
            echo $successMessage;

            // Fetch products from menu table
            $sql = "SELECT * FROM menu";
            $result = $conn->query($sql);
            $products = $result->fetch_all(MYSQLI_ASSOC);

            // Fetch specials
            $specialsSql = "SELECT * FROM menu WHERE special = 1";
            $specialsResult = $conn->query($specialsSql);
            $specials = $specialsResult->fetch_all(MYSQLI_ASSOC);
            ?>
            <div class="product-list">
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <div class="swiper product-carousel">
                            <div class="swiper-wrapper">
                                <?php
                                $imageArray = explode(',', $product['images']);
                                foreach ($imageArray as $image) {
                                    if (!empty(trim($image))) {
                                        echo "<div class='swiper-slide'><img src='" . trim($image) . "' alt='" . $product['name'] . "' style='width:150px; height:auto;'></div>";
                                    }
                                }
                                ?>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>
                        <h3><?php echo $product['name']; ?></h3>
                        <p>Category: <?php echo $product['category']; ?></p>
                        <p>Subcategory: <?php echo $product['subcategory']; ?></p>
                        <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="quantity" value="1" min="1" style="width:50px;">
                            <button type="submit" name="add_to_cart" class="order-btn-small">Add to Cart</button>
                            <button type="submit" name="buy_now" class="order-btn-small">Buy Now</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="specials-section">
                <h3>Our Specials</h3>
                <div class="product-list">
                    <?php if ($specialsResult->num_rows > 0): ?>
                        <?php foreach ($specials as $special): ?>
                            <div class="product-item">
                                <div class="swiper product-carousel">
                                    <div class="swiper-wrapper">
                                        <?php
                                        $imageArray = explode(',', $special['images']);
                                        foreach ($imageArray as $image) {
                                            if (!empty(trim($image))) {
                                                echo "<div class='swiper-slide'><img src='" . trim($image) . "' alt='" . $special['name'] . "' style='width:150px; height:auto;'></div>";
                                            }
                                        }
                                        ?>
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                                <h3><?php echo $special['name']; ?></h3>
                                <p>Category: <?php echo $special['category']; ?></p>
                                <p>Subcategory: <?php echo $special['subcategory']; ?></p>
                                <p>Price: $<?php echo number_format($special['price'], 2); ?></p>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="product_id" value="<?php echo $special['id']; ?>">
                                    <input type="number" name="quantity" value="1" min="1" style="width:50px;">
                                    <button type="submit" name="add_to_cart" class="order-btn-small">Add to Cart</button>
                                    <button type="submit" name="buy_now" class="order-btn-small">Buy Now</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No specials available at the moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section left">
                <div class="contact">Toll-Free: 1-888-945-0911</div>
                <div class="social">
                    <a href="#">Facebook</a> | <a href="#">Instagram</a>
                </div>
            </div>
            <div class="footer-section center">
                <button class="donate-btn">
                    <img src="flag-usa.png" alt="American Flag" class="flag-image"> <span class="btn-text">Support Our Veterans</span>
                </button>
                <div class="total-donations">
                    <p>Total Donations: $10,000 <span>(Placeholder - Update via CMS)</span></p>
                </div>
            </div>
            <div class="footer-section right">
                <div class="donation-note">
                    <p>100% of your donation goes directly to local veterans. We personally distribute every dollar to support our heroes in the Southeast Texas Area.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        document.getElementById('confirm-location').addEventListener('click', function() {
            const location = document.getElementById('location-select').value;
            document.getElementById('location-display').textContent = `Ordering from: ${location}`;
            document.getElementById('location-modal').style.display = 'none';
        });

        // Initialize Swiper
        var swipers = document.querySelectorAll('.product-carousel');
        swipers.forEach(function(swiperElement) {
            new Swiper(swiperElement, {
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                loop: false,
                slidesPerView: 1,
                spaceBetween: 10,
            });
        });
    </script>
</body>
</html>