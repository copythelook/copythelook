<?php
include '../config.php';

// Έλεγχος αν το id υπάρχει και είναι αριθμός
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $reservationId = $_POST['id'];

    // Προετοιμασμένο ερώτημα για την αποφυγή SQL Injection
    $query = "SELECT * FROM roombook WHERE id = ?";
    
    // Ετοιμάζουμε το ερώτημα
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Δέσμευση της παραμέτρου
        mysqli_stmt_bind_param($stmt, "i", $reservationId);

        // Εκτέλεση του ερωτήματος
        mysqli_stmt_execute($stmt);

        // Λήψη των αποτελεσμάτων
        $result = mysqli_stmt_get_result($stmt);

        // Αν βρέθηκαν αποτελέσματα, επιστρέφουμε την κράτηση ως JSON
        if ($result && mysqli_num_rows($result) > 0) {
            $reservation = mysqli_fetch_assoc($result);
            echo json_encode($reservation); // Επιστρέφουμε τα δεδομένα σε μορφή JSON
        } else {
            echo json_encode(["error" => "Δεν βρέθηκαν δεδομένα για την κράτηση."]);
        }

        // Κλείσιμο της δήλωσης
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode(["error" => "Δεν ήταν δυνατή η εκτέλεση του ερωτήματος."]);
    }
} else {
    echo json_encode(["error" => "Ακατάλληλη ή απουσία παράμετρος ID."]);
}

// Κλείσιμο της σύνδεσης με τη βάση δεδομένων
mysqli_close($conn);
?>
