<?php
include 'templates/header.php';
include_once 'includes/Database.php';
include_once 'includes/User.php';

$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User($db);
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->register()) {
        echo '<p>Registration successful. <a href="login.php">Login here</a>.</p>';
    } else {
        echo '<p>Registration failed. Username may already be taken.</p>';
    }
}
?>

<h2>Register</h2>
<form action="register.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Register</button>
</form>

<?php include 'templates/footer.php'; ?>
