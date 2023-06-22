<?php session_start() ?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="path_to_your_css_file.css">
</head>
<body>

<?php include('header.php'); ?>

<div class="form-container">
    <h1>Update Profile</h1>
    <form action="update_profile.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" readonly><br>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $_SESSION['first_name']; ?>" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $_SESSION['last_name']; ?>" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" required><br>

        <label for="password">New Password (leave blank to keep current password):</label>
        <input type="password" id="password" name="password"><br>

        <input class="form-submit" type="submit" value="Update Profile">
    </form>
</div>
</body>
</html>
