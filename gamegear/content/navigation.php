<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav id="topNavigation">
	<ul>
		<li><a href="/gamegear/">Home</a></li>
		<li><a href="/gamegear/about/">About GameGear Exchange</a></li>
		<li><a href="/gamegear/feature/">Featured Items</a></li>
		<li><a href="/gamegear/listings/index.php">Product Listings & Details</a></li>
		<li><a href="/gamegear/purchase/purchase.php">Purchase Available Products</a></li>
		<li><a href="/gamegear/news/">Latest Announcement</a></li>
		<li><a href="/gamegear/contact/">Contact Us</a></li>
		<?php if(isset($_SESSION['user_email'])): ?>
                <li>
                    <a href="/gamegear/user_logout.php" onclick="return confirm('Are you sure you want to log out?');" style="color: #ffcdd2; font-weight: bold;">
                        Log Out (<?php echo htmlspecialchars($_SESSION['user_name']); ?>)
                    </a>
                </li>
            <?php else: ?>
                <li><a href="/gamegear/user_login.php">Login (Buyer)</a></li>
            <?php endif; ?>
	</ul>
</nav>

<?php if (!isset($hidePageTitle) || !$hidePageTitle): ?>
    <div style="background-color: rgb(240,244,248); text-align: center; padding: 40px 18px 0px 18px;">
        <h1 style="color: rgb(33, 37, 41); margin: 0; font-size: 2.2em;">
            <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : ''; ?>
        </h1>
    </div>
<?php endif; ?>