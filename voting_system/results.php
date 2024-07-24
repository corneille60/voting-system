<?php
include 'templates/header.php';
include_once 'includes/Database.php';

$database = new Database();
$db = $database->connect();
?>

<h2>Results</h2>
<table>
    <thead>
        <tr>
            <th>Election</th>
            <th>Candidate</th>
            <th>Votes</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = 'SELECT e.name AS election_name, c.name AS candidate_name, c.votes 
                  FROM candidates c 
                  JOIN elections e ON c.election_id = e.id 
                  ORDER BY e.id, c.votes DESC';
        $stmt = $db->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['election_name'] . '</td>';
            echo '<td>' . $row['candidate_name'] . '</td>';
            echo '<td>' . $row['votes'] . '</td>';
            echo '</tr>';
        }
        ?>
    </tbody>
</table>

<?php include 'templates/footer.php'; ?>
