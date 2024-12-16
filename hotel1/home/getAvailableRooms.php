<?php
include '../config.php'; // Σύνδεση με τη βάση δεδομένων

if (isset($_POST['rtype'])) {
    $rtype = $_POST['rtype']; // Παίρνουμε τον τύπο δωματίου από το POST αίτημα

    // Ερώτημα για να πάρεις τα bedding options για τον επιλεγμένο τύπο δωματίου
    $sql = "SELECT DISTINCT bedding FROM room WHERE rtype = '$rtype'";
    $result = mysqli_query($conn, $sql);

    // Έλεγχος αν το query επέστρεψε αποτελέσματα
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . $row['bedding'] . "'>" . $row['bedding'] . "</option>";
        }
    } else {
        echo "<option value='' disabled>No bedding available</option>";
    }
} else {
    echo "<option value='' disabled>Select a room type first</option>";
}
?>