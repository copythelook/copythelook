<?php
session_start();
include '../config.php'; // Σύνδεση με τη βάση δεδομένων

$selectedMonth = isset($_POST['month']) && !empty($_POST['month']) ? $_POST['month'] : date('Y-m');

if (!empty($selectedMonth)) {
    $year = date('Y', strtotime($selectedMonth));
    $month = date('m', strtotime($selectedMonth));
} else {
    $year = date('Y');
    $month = date('m');
}

$firstDayOfMonth = date('Y-m-d', strtotime("first day of $selectedMonth"));
$lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));

$roomQuery = "SELECT rtype, COUNT(*) as total_rooms FROM room GROUP BY rtype";
$roomResult = mysqli_query($conn, $roomQuery);

$rooms = [];
if ($roomResult && mysqli_num_rows($roomResult) > 0) {
    while ($row = mysqli_fetch_assoc($roomResult)) {
        $rooms[$row['rtype']] = $row['total_rooms'];
    }
}

$reservationQuery = "
    SELECT RoomType, BeddingNumber, cin, cout
    FROM roombook
    WHERE (cin <= '$lastDayOfMonth') AND (cout >= '$firstDayOfMonth')";
$reservationResult = mysqli_query($conn, $reservationQuery);

$reservations = [];
if ($reservationResult && mysqli_num_rows($reservationResult) > 0) {
    while ($row = mysqli_fetch_assoc($reservationResult)) {
        $dateRange = new DatePeriod(
            new DateTime($row['cin']),
            new DateInterval('P1D'),
            (new DateTime($row['cout']))->modify('+1 day') // Συμπεριλαμβάνει την ημέρα checkout
        );
        foreach ($dateRange as $date) {
            $reservations[$date->format('Y-m-d')][$row['RoomType']][] = $row['BeddingNumber'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Availability</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
  /* Στυλ για την περιοχή του πίνακα που να καλύπτει το 100% του πλάτους */
/* Ο πίνακας θα καλύπτει το πλήρες πλάτος της σελίδας */
.table {
    max-height: 1000vh; /* Ρύθμιση για το μέγιστο ύψος του πίνακα */
    overflow-y: auto; /* Κύλιση όταν ο πίνακας είναι πιο ψηλός από το διαθέσιμο ύψος */
    margin-bottom: 100px; /* Κενό κάτω από τον πίνακα για να μην κολλήσει η τελευταία γραμμή */
}

tbody {
    overflow-y: auto;
    padding-bottom: 20px; /* Εξασφαλίζει ότι η τελευταία γραμμή έχει λίγο κενό από κάτω */
}

th, td {
    white-space: nowrap;
    text-align: center;
}

thead {
    background-color: #f8f9fa;
    position: sticky;
    top: 0;
    z-index: 1;
}

table {
    width: 100%;
}

tr {
    display: table-row;
}

td, th {
    width: 33%;
    padding: 8px;
    box-sizing: border-box;
}


</style>
   
</head>
<body>
<div class="container mt-4">
    <form method="POST" class="mb-3">
        <label for="month">Επιλέξτε Μήνα:</label>
        <input type="month" name="month" id="month" value="<?php echo $selectedMonth; ?>" class="form-control">
        <button type="submit" class="btn btn-primary mt-2">Εμφάνιση</button>
    </form>
    <h2>Διαθέσιμα Δωμάτια για τον Μήνα <?php echo date("F Y", strtotime($selectedMonth)); ?></h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Ημερομηνία</th>
                <th>Κρατήσεις</th>
                <th>Διαθέσιμα Δωμάτια Ανά Τύπο</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $daysInMonth = date('t', strtotime($selectedMonth));
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $currentDate = $year . '-' . $month . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                echo "<tr class='day-row' data-date='" . $currentDate . "'>";
                echo "<td>" . date('d/m/Y', strtotime($currentDate)) . "</td>";
                $reservationsList = "";
                $availableRoomsList = "";

                if (!empty($rooms)) {
                    foreach ($rooms as $roomType => $totalRooms) {
                        $reservationsCount = isset($reservations[$currentDate][$roomType]) ? count($reservations[$currentDate][$roomType]) : 0;
                        $availableRooms = $totalRooms - $reservationsCount;
                        $reservationsList .= "$roomType: $reservationsCount κρατήσεις<br>";
                        $availableRoomsList .= "$roomType: $availableRooms διαθέσιμα δωμάτια<br>";
                    }
                }

                echo "<td>" . $reservationsList . "</td>";
                echo "<td>" . $availableRoomsList . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Modal Παράθυρο -->
    <div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- modal-xl για μεγαλύτερο πλάτος -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalLabel">Λεπτομέρειες Κρατήσεων</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive"> <!-- Προσθήκη για κύλιση αν χρειαστεί -->
                    <table class="table table-bordered" id="reservation-table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Adults</th>
                                <th>Children</th>
                                <th>Infants</th>
                                <th>Room Type</th>
                                <th>Bedding Number</th>
                                <th>Meal</th>
                                <th>Check-In</th>
                                <th>Check-Out</th>
                                <th>Status</th>
                                <th>Date of Reservation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Δεδομένα που φορτώνονται δυναμικά -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>

<script>
    $(document).on('click', '.day-row', function() {
        var date = $(this).data('date');
        console.log("Selected Date:", date);

        $.ajax({
            type: 'POST',
            url: 'getReservationsForDate.php',
            data: { date: date },
            success: function(response) {
                $('#reservation-table tbody').html(response); // Ενημέρωση πίνακα με κρατήσεις
                $('#reservationModal').modal('show'); // Εμφάνιση modal
            },
            error: function(xhr, status, error) {
                console.error("Error:", status, error);
                alert('Σφάλμα κατά την ανάκτηση των κρατήσεων.');
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>