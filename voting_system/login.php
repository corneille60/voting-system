<?php
session_start();
include 'templates/header.php';
include_once 'includes/Database.php';
include_once 'includes/User.php';

$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User($db);
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if ($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        header('Location: index.php');
        exit();
    } else {
        echo '<p>Login failed. Incorrect username or password.</p>';
    }
}
?>

<h2>Login</h2>
<form action="login.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>
    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Login</button>
</form>

<?php include 'templates/footer.php'; ?>
