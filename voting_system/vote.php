<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'templates/header.php';
include_once 'includes/Database.php';
include_once 'includes/Election.php';
include_once 'includes/Candidate.php';

$database = new Database();
$db = $database->connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $candidate_id = $_POST['candidate_id'];
    $election_id = $_POST['election_id'];

    // Check if the user has already voted in this election
    $query = 'SELECT * FROM votes WHERE user_id = :user_id AND election_id = :election_id';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':election_id', $election_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo '<p>You have already voted in this election.</p>';
    } else {
        $query = 'INSERT INTO votes (user_id, election_id, candidate_id) VALUES(:user_id, :election_id, :candidate_id)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':election_id', $election_id);
        $stmt->bindParam(':candidate_id', $candidate_id);

        if ($stmt->execute()) {
            $candidate = new Candidate($db);
            $candidate->vote($candidate_id);
            echo '<p>Thank you for voting!</p>';
        } else {
            echo '<p>Failed to cast your vote. Please try again.</p>';
        }
    }
}
?>

<h2>Vote</h2>
<form action="vote.php" method="POST">
    <label for="election_id">Election:</label>
    <select id="election_id" name="election_id" onchange="loadCandidates()" required>
        <option value="">Select Election</option>
        <?php
        $election = new Election($db);
        $stmt = $election->read();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
        ?>
    </select>
    <label for="candidate_id">Candidate:</label>
    <select id="candidate_id" name="candidate_id" required>
        <option value="">Select Candidate</option>
    </select>
    <button type="submit">Vote</button>
</form>

<script>
function loadCandidates() {
    var electionId = document.getElementById('election_id').value;
    var candidateSelect = document.getElementById('candidate_id');

    candidateSelect.innerHTML = '<option value="">Select Candidate</option>';

    if (electionId) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'load_candidates.php?election_id=' + electionId, true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                console.log('Response:', xhr.responseText); // Log the response
                var candidates = JSON.parse(xhr.responseText);
                if (Array.isArray(candidates)) {
                    candidates.forEach(function(candidate) {
                        var option = document.createElement('option');
                        option.value = candidate.id;
                        option.text = candidate.name;
                        candidateSelect.appendChild(option);
                    });
                } else {
                    console.error('Expected an array of candidates.');
                }
            } else {
                console.error('Failed to load candidates.');
            }
        };
        xhr.send();
    } else {
        candidateSelect.innerHTML = '<option value="">Select Candidate</option>';
    }
}

document.getElementById('election_id').addEventListener('change', loadCandidates);

</script>

<?php include 'templates/footer.php'; ?>
