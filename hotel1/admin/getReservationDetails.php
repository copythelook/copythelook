<?php
include '../config.php';

// Έλεγχος αν το idname υπάρχει και είναι έγκυρο
if (isset($_POST['idname'])) {
    $reservationName = $_POST['idname'];  // Αλλάξαμε την παράμετρο σε 'idname'

    // Προετοιμασμένο ερώτημα για την αποφυγή SQL Injection
    $query = "SELECT * FROM roombook WHERE idname = ?";  // Χρησιμοποιούμε το idname αντί για το id

    // Ετοιμάζουμε το ερώτημα
    if ($stmt = mysqli_prepare($conn, $query)) {
        // Δέσμευση της παραμέτρου
        mysqli_stmt_bind_param($stmt, "s", $reservationName);  // Χρησιμοποιούμε το 's' γιατί το idname θα είναι συμβολοσειρά

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
    echo json_encode(["error" => "Ακατάλληλη ή απουσία παράμετρος idname."]);
}

// Κλείσιμο της σύνδεσης με τη βάση δεδομένων
mysqli_close($conn);
?>
