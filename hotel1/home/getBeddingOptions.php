<?php
session_start();
include '../config.php'; // Σύνδεση με τη βάση δεδομένων

// Ελέγξτε αν έχουν ληφθεί οι απαραίτητες παράμετροι
if (isset($_POST['rtype'], $_POST['cin'], $_POST['cout'])) {
    $rtype = mysqli_real_escape_string($conn, $_POST['rtype']); // Τύπος δωματίου
    $cin = mysqli_real_escape_string($conn, $_POST['cin']); // Ημερομηνία check-in
    $cout = mysqli_real_escape_string($conn, $_POST['cout']); // Ημερομηνία check-out

    // Βρίσκουμε όλα τα δωμάτια για τον επιλεγμένο τύπο δωματίου
    $roomQuery = "SELECT * FROM room WHERE rtype = '$rtype'";
    $result = mysqli_query($conn, $roomQuery);

    // Ελέγχουμε αν υπάρχουν αποτελέσματα
    if (mysqli_num_rows($result) > 0) {
        $availableRooms = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $bedding = $row['bedding']; // Ο αριθμός κρεβατιών στον πίνακα "room"

            // Ελέγχουμε αν το συγκεκριμένο bedding έχει κρατήσεις για την επιλεγμένη περίοδο στον πίνακα "roombook"
            $availabilityQuery = "
                SELECT * FROM roombook
                WHERE RoomType = '$rtype'
                AND BeddingNumber = '$bedding'
                AND (cin < '$cout' AND cout > '$cin')"; // Ελέγχει για κρατήσεις που επικαλύπτονται

            $availabilityResult = mysqli_query($conn, $availabilityQuery);

            // Αν δεν υπάρχουν κρατήσεις για αυτό το bedding, το θεωρούμε διαθέσιμο
            if (mysqli_num_rows($availabilityResult) == 0) {
                $availableRooms[] = $bedding;
            }
        }

        // Επιστρέφουμε τα διαθέσιμα bedding ή μήνυμα ότι δεν υπάρχουν διαθέσιμα δωμάτια
        if (count($availableRooms) > 0) {
            foreach ($availableRooms as $bedding) {
                echo "<option value='$bedding'>$bedding</option>";
            }
        } else {
            echo "<option value='' disabled>No available rooms for selected dates</option>";
        }
    } else {
        echo "<option value='' disabled>No bedding available for this room type</option>";
    }
} else {
    echo "<option value='' disabled>Invalid request</option>";
}
?>





