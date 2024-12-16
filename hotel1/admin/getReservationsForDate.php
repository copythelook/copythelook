<?php
include '../config.php'; // Σύνδεση με βάση δεδομένων

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $selectedDate = $_POST['date'];

    $query = "
        SELECT id, Name, Noofadult, Noofchild, Noofinfant, RoomType, BeddingNumber, Meal, cin, cout, stat, din AS DateMade
        FROM roombook
        WHERE ('$selectedDate' BETWEEN cin AND cout)";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['Name']}</td>";
            echo "<td>{$row['Noofadult']}</td>";
            echo "<td>{$row['Noofchild']}</td>";
            echo "<td>{$row['Noofinfant']}</td>";
            echo "<td>{$row['RoomType']}</td>";
            echo "<td>{$row['BeddingNumber']}</td>";
            echo "<td>{$row['Meal']}</td>";
            echo "<td>{$row['cin']}</td>";
            echo "<td>{$row['cout']}</td>";
            echo "<td>{$row['stat']}</td>";
            echo "<td>{$row['DateMade']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='12'>Δεν βρέθηκαν κρατήσεις για την ημερομηνία αυτή.</td></tr>";
    }
} else {
    echo "<tr><td colspan='12'>Λανθασμένη κλήση.</td></tr>";
}
?>



