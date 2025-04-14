<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judalet’s Fire and Ice - Community</title>
    <link rel="stylesheet" href="style.css">
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
        <section class="community-section">
            <h2>Community & Events</h2>
            <div class="events">
                <h3>Upcoming Events</h3>
                <div class="event">
                    <p>Veterans Day Fundraiser - November 11, 2025</p>
                    <button class="banner-btn">See You There!</button>
                    <p>Show your event wristband for 10% off!</p>
                </div>
            </div>
            <div class="donation-form">
                <h3>Support Our Veterans</h3>
                <p>100% of your donation goes directly to local veterans. We personally distribute every dollar to support our heroes in the Southeast Texas Area.</p>
                <select name="donation-amount">
                    <option value="1">$1</option>
                    <option value="5">$5</option>
                    <option value="10">$10</option>
                    <option value="custom">Custom Amount</option>
                </select>
                <input type="number" id="custom-donation" style="display:none;" placeholder="Enter amount">
                <button class="order-btn" onclick="submitDonation()">Donate Now</button>
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
    <script>
        function submitDonation() {
            let amount = $('select[name="donation-amount"]').val();
            if (amount === 'custom') {
                amount = $('#custom-donation').val() || 0;
                $('#custom-donation').show();
            }
            if (amount > 0) {
                alert("Donation of $" + amount + " submitted! (Placeholder - integrate with database later)");
                // Add PHP/MySQL integration here later
            } else {
                alert("Please enter a valid amount!");
            }
        }
        $('select[name="donation-amount"]').change(function() {
            if ($(this).val() === 'custom') $('#custom-donation').show();
            else $('#custom-donation').hide();
        });
    </script>
</body>
</html>