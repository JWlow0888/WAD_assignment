<?php
$pageTitle = "About GameGear Exchange";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css"> <!-- Adjust path if needed -->
    <title>About Us | GameGear Exchange</title>
</head>
<body>
  <?php include('../content/header.php'); ?>
  <?php include('../content/navigation.php'); ?>

<div id="contentWrapper">
    <div class="about-container">
        
        <section class="about-hero">
            <h2>Empowering Gamers, One Setup at a Time</h2>
            <p>At GameGear Exchange, we believe that epic gaming experiences shouldn't require an empty wallet. Founded in 2024 by a group of passionate gamers, our platform was built to solve a common problem: the high barrier to entry for quality gaming hardware.</p>
        </section>

        <section class="about-story">
            <h3>Our Story</h3>
            <p>We started out swapping graphics cards, old consoles and physical game discs within our local Discord servers. We quickly realized the secondhand market was filled with risks—scams, broken parts and zero warranties. We wanted a dedicated space where gamers could trade gear safely, so we built GameGear Exchange. Today, we are the premier destination for verified, pre-owned gaming equipment.</p>
        </section>

        <section class="about-features">
            <h3 style="text-align: center; margin-bottom: 30px;">What Sets Us Apart?</h3>
            <div class="features-grid">
                
                <div class="feature-card">
                    <div class="feature-icon">✅</div>
                    <h4>Verified Quality</h4>
                    <p>Every item sold through our premium exchange is tested, stress-benched, and cleaned by our in-house hardware technicians.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">♻️</div>
                    <h4>Sustainability</h4>
                    <p>By choosing pre-owned gear, you are keeping perfectly good electronics out of landfills and reducing e-waste.</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🎮</div>
                    <h4>Gamer-First Support</h4>
                    <p>We don't just sell hardware; we know hardware. Our support team consists of PC builders and console enthusiasts who understand your needs.</p>
                </div>

            </div>
        </section>

        <section class="about-cta">
            <h3>Ready to Level Up?</h3>
            <p>Whether you are a competitive esports player looking for a 240Hz monitor or a retro-enthusiast hunting for a pristine Nintendo 64, you belong here.</p>
            <br>
            <a href="/gamegear/listings/index.php" class="cta-button large-btn">Explore the Marketplace</a>
        </section>

    </div>
</div>

  <?php include('../content/footer.php'); ?>
</body>
</html>