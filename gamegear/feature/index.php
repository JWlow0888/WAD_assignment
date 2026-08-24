<?php
$pageTitle = "Featured Items On Sale";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../style/style.css">
    <title>Featured Items | GameGear Exchange</title>
</head>
<body>
  	<?php include('../content/header.php'); ?>
 	<?php include('../content/navigation.php'); ?>
<div id="contentWrapper">
        <h2>⚡ Weekend Flash Clearance ⚡</h2>
        <p>Certified pre-owned gear at maximum markdown.</p>

    <div class="filter-controls">
        <button class="filter-btn active" onclick="filterItems('all')">All Featured</button>
        <button class="filter-btn" onclick="filterItems('limited')">Limited Drops</button>
        <button class="filter-btn" onclick="filterItems('discount')">Discounted Gear</button>
        <button class="filter-btn" onclick="filterItems('retro')">Retro Consoles</button>
    </div>


    <div class="featured-grid">

        <div class="item-card" data-category="limited">

            <img src="ps6.jpg">
            <div class="card-body">
                <h3>PlayStation 6 (Digital Edition)</h3>
                <p>Condition: <strong>Refurbished - Like New</strong></p>
                <p>Includes original DualSense wireless controller, HDMI 2.1 cable, and 1-year store warranty. Repasted and thermal tested.</p>
                <div class="price-box">
                    <h5>Old Price: RM 2,099</h5>
                    <h5>New Price: RM 1,499</h5>
                </div>
                <a href="/gamegear/listings/" class="button">View Details</a>
            </div>
        </div>

        <div class="item-card" data-category="discount">
            <h5>-35% OFF</h5>
            <img src="3070.jpg">
            <div class="card-body">
                <h3>ASUS TUF RTX 3070 8GB OC</h3>
                <p>Condition: <strong>Used - Pristine (Non-Mining)</strong></p>
                <p>Stress-tested on FurMark for 6 hours. Zero coil whine, clean fan bearings, original box and anti-static packaging included.</p>
                <div class="price-box">
                    <h5>Old Price: RM 1,850</h5>
                    <h5>New Price: RM 1,199</h5>
                </div>
                <a href="/gamegear/listings/" class="button">View Details</a>
            </div>
        </div>

        <div class="item-card" data-category="limited">
            <h5>Collector's Edition</h5>
            <img src="pc.jpg">
            <div class="card-body">
                <h3>AMD Gaming PC (ROG) </h3>
                <p>Condition: <strong>Slightly used</strong></p>
                <p>GameGear Exchange builds the fastest PCs for gamers, creators, workstation & business use. Backed by enterprise-grade QA testing and a lifetime desktop warranty.</p>
                <div class="price-box">
                    <h5>Old Price: RM 10000</h5>
                    <h5>New Price: RM 9900</h5>
                </div>
                <a href="/gamegear/listings/" class="button">View Details</a>
            </div>
        </div>

        <div class="item-card" data-category="discount">
            <h5>-40% OFF</h5>
            <img src="keyboard.jpg">
            <div class="card-body">
                <h3>Keychron Q1 QMK Custom Mechanical Keyboard (Lubed Switches)</h3>
                <p>Condition: <strong>Open Box</strong></p>
                <p>Full CNC aluminum body, Gateron G Pro Red switches (hand-lubricated with Krytox 205g0), tape-modded for deep sound profile.</p>
                <div class="price-box">
                    <h5>Old Price: RM 680</h5>
                    <h5>New Price: RM 409</h5>
                </div>
                <a href="/gamegear/listings/" class="cta-button">View Details</a>
            </div>
        </div>
    </div>

    <h2 style="margin-top: 60px;">Hardware Inspection Checklist</h2>
    <p>Every featured product satisfies our 3-tier inspection protocol before being cataloged:</p>

    <table class="specs-table">
        <caption>GGE Certified Inspection Criteria</caption>
        <thead>
            <tr>
                <th>Tier</th>
                <th>Category</th>
                <th>Testing Procedure & Validation Benchmark</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Level 1</td>
                <td>Sanitization</td>
                <td>Ultrasonic PCB cleaning, thermal paste re-application, port debris clearing.</td>
            </tr>
            <tr>
                <td>Level 2</td>
                <td>Diagnostics</td>
                <td>Continuous load burn-in test (3DMark / MemTest86 / Controller Deadzone check).</td>
            </tr>
            <tr>
                <td>Level 3</td>
                <td>Certification</td>
                <td>Serial tracking registration, tamper seal attachment, 30-day warranty assignment.</td>
            </tr>
        </tbody>
    </table>
</div>
	<?php include('../content/footer.php'); ?>
</body>
</html>