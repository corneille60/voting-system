<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

include 'templates/header.php';
include_once 'includes/Database.php';
include_once 'includes/Election.php';
include_once 'includes/Candidate.php';

$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $election = new Election($db);
    $election->name = $_POST['election_name'];
    $election->description = $_POST['election_description'];

    if ($election->create()) {
        $election_id = $db->lastInsertId();
        $candidate_names = $_POST['candidate_names'];
        foreach ($candidate_names as $candidate_name) {
            $candidate = new Candidate($db);
            $candidate->name = $candidate_name;
            $candidate->election_id = $election_id;
            $candidate->create();
        }
        echo '<p>Election and candidates created successfully.</p>';
    } else {
        echo '<p>Failed to create election.</p>';
    }
}
?>

<h2>Create Election</h2>
<form action="create_election.php" method="POST">
    <label for="election_name">Election Name:</label>
    <input type="text" id="election_name" name="election_name" required>
    <label for="election_description">Election Description:</label>
    <textarea id="election_description" name="election_description" required></textarea>
    <label for="candidate_names[]">Candidates:</label>
    <div id="candidate_container">
        <input type="text" name="candidate_names[]" required>
    </div>
    <button type="button" onclick="addCandidate()">Add Another Candidate</button>
    <button type="submit">Create Election</button>
</form>

<script>
function addCandidate() {
    var container = document.getElementById('candidate_container');
    var input = document.createElement('input');
    input.type = 'text';
    input.name = 'candidate_names[]';
    input.required = true;
    container.appendChild(input);
}
</script>

<?php include 'templates/footer.php'; ?>
