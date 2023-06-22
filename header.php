<!DOCTYPE html>
<html>
<head>
    <title>Restaurant</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <div class="logo">Logo</div>
        <a href="index.php" class="restaurant-name">Restaurant Name</a>
        <nav class="desktop-nav">
            <a class="links" href="#">About Us</a>
            <a class="links" href="#">Contact</a>
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <div class="user-menu">
                <?php echo $_SESSION['username']; ?>
                <div class="user-submenu">
                    <a href="profile.php">Profile</a>
                    <a href="<?php echo ($_SESSION['user_type'] === 'customer' ? 'reservations_user.php' : 'reservations.php'); ?>">Reservations</a>
                    <a href="#">Settings</a>
                    <a href="logout.php">Logout</a>
                </div>
            </div>
            <?php else: ?>
            <a class="links" href="login.php">Login</a>
            <a class="links" href="register.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>
</body>
</html>
