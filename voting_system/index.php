<?php
session_start();
include 'templates/header.php';
?>

<div class="container">
    <?php if (isset($_SESSION['user_id'])): ?>
        <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Select an action below:</p>
        <ul>
            <li><a href="create_election.php">Create Election</a></li>
            <li><a href="vote.php">Vote</a></li>
            <li><a href="results.php">View Results</a></li>
        </ul>
    <?php else: ?>
        <h2>Welcome to the Voting System</h2>
        <p>Please <a href="login.php">log in</a> to participate in the voting process.</p>
    <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>
