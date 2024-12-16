<?php
session_start();
include '../config.php';

// Λήψη της ημερομηνίας εκκίνησης ή χρήση της τρέχουσας ημερομηνίας αν δεν έχει οριστεί
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-d");
$endDate = date("Y-m-d", strtotime("$startDate +29 days"));

// Λήψη των τύπων δωματίων από τον πίνακα "room"
$roomQuery = "SELECT * FROM room ORDER BY rtype,bedding";
$roomResult = mysqli_query($conn, $roomQuery);

if (!$roomResult) {
    die("Error executing query: " . mysqli_error($conn));
}

// Λήψη των κρατήσεων που περιλαμβάνουν την επιλεγμένη χρονική περίοδο
$reservationQuery = "SELECT * FROM roombook WHERE (cin <= '$endDate') AND (cout >= '$startDate')";
$reservationResult = mysqli_query($conn, $reservationQuery);

$reservations = [];
while ($row = mysqli_fetch_assoc($reservationResult)) {
    $reservation = [
        'cin' => $row['cin'],
        'cout' => $row['cout'],
        'RoomType' => $row['RoomType'],
        'BeddingNumber' => $row['BeddingNumber'],
        'Name' => $row['Name'],
        'idname' => $row['idname']
    ];
    $reservations[] = $reservation;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
   
    <style>
        .calendar {
            overflow: auto; /* Επιτρέπει την κύλιση */
            max-width: 100%; /* Μην ξεπερνά την οθόνη */
            max-height: 500px; /* Μπορείς να αλλάξεις το ύψος ανάλογα με το layout */
        }
        .table {
            min-width: 1200px; /* Βεβαιώσου ότι ο πίνακας έχει αρκετό πλάτος για να χρειάζεται κύλιση */
        }

        .reservation-block {
            position: relative !important;
            background-color: #90EE90 !important; /* Ανοιχτό πράσινο χρώμα φόντου */
            border: 2px solid black !important; /* Μαύρο περίγραμμα */
            border-radius: 5px !important;
            text-align: center !important;
            vertical-align: middle !important;
            font-weight: bold !important;
            overflow: hidden !important; /* Αποφυγή υπερχείλισης */
            cursor: pointer !important;
            z-index: 2 !important;
            padding: 10px;
        }

        .reservation-block span {
            position: relative !important;
            z-index: 2 !important;
        }

        /* Ρυθμίσεις για το παραλληλόγραμμο */
        .reservation-block:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: inherit;
            transform: skew(-20deg);
            transform-origin: top left;
        }

        .reservation-block:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: inherit;
            transform: skew(20deg);
            transform-origin: top right;
        }
        </style>
</head>
<body>

<div class="container mt-4">
    <form action="staff.php" method="GET" class="mb-3">
        <label for="start_date">Ημερομηνία Έναρξης:</label>
        <input type="date" name="start_date" id="start_date" value="<?php echo $startDate; ?>">
        <button type="submit" class="btn btn-primary">Προβολή</button>
    </form>

    <h2><?php echo date("F Y", strtotime($startDate)); ?> - Προβολή Κρατήσεων για 30 ημέρες</h2>

    <div class="calendar">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Τύπος Δωματίου</th>
                    <th>Κρεβάτια</th>
                    <?php
                    for ($i = 0; $i < 30; $i++) {
                        $currentDate = date("Y-m-d", strtotime("$startDate +$i days"));
                        echo "<th>" . date("d/m", strtotime($currentDate)) . "</th>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                 while ($roomRow = mysqli_fetch_assoc($roomResult)) {
        $roomType = isset($roomRow['rtype']) ? $roomRow['rtype'] : 'Μη διαθέσιμο';
        $bedding = isset($roomRow['bedding']) ? $roomRow['bedding'] : 'Μη διαθέσιμο';

        echo "<tr>";
        echo "<td>$roomType</td>";
        echo "<td>$bedding</td>";

        $day = 0;
        while ($day < 30) {
            $currentDate = date("Y-m-d", strtotime("$startDate +$day days"));
            $found = false;

            foreach ($reservations as $reservation) {
                // Ελέγχει αν το currentDate βρίσκεται στο εύρος της κράτησης
                if ($reservation['BeddingNumber'] == $bedding 
                    && $currentDate >= $reservation['cin'] 
                    && $currentDate <= $reservation['cout']) {
                    
                    $nextReservation = null;
                    // Έλεγχος για την επόμενη κράτηση που ξεκινά την ίδια μέρα που τελειώνει η τρέχουσα
                    foreach ($reservations as $nextRes) {
                        if ($nextRes['BeddingNumber'] == $bedding 
                            && $nextRes['cin'] == $reservation['cout']) {
                            $nextReservation = $nextRes;
                            break;
                        }
                    }

                    // Αν υπάρχει συνεχόμενη κράτηση
                    if ($currentDate == $reservation['cout'] && $nextReservation) {
                        echo "<td style='background: #90EE90; border-top: 2px solid black; text-align: center; font-weight: bold;' onclick='openReservationForm({$reservation['idname']})'>";
                        echo "{$reservation['idname']} ➔ {$nextReservation['idname']}";
                        echo "</td>";
                        $day++;
                        $found = true;
                        break;
                    }

                    // Αν είναι απλή ημέρα κράτησης
                    echo "<td style='background-color: #90EE90; text-align: center; font-weight: bold;' onclick='openReservationForm({$reservation['idname']})'>";
                    echo "{$reservation['idname']}";
                    echo "</td>";
                    $day++;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                // Αν δεν υπάρχει κράτηση για την ημερομηνία, άδειο κελί
                echo "<td></td>";
                $day++;
            }
        }

        echo "</tr>";
    }
    ?>
</tbody>
        </table>
    </div>
</div>

<!-- Guest Details Panel (Hidden initially) -->
<div id="guestdetailpanel" style="display:none;">
    <form action="" method="POST" class="guestdetailpanelform">
        <div class="head">
            <h3>RESERVATION</h3>
            <i class="fa-solid fa-circle-xmark" onclick="adduserclose()"></i>
        </div>
        <div class="middle">
            <div class="guestinfo">
                <h4>Guest information</h4>
                <input type="text" name="idname" placeholder="Reservation Number" required>
                <input type="text" name="Name" placeholder="Name" required>

                <select name="Noofadult" class="selectinput">
                    <option value selected>No of adults</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                <select name="Noofchild" class="selectinput">
                    <option value selected>No of children</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                <select name="Noofinfant" class="selectinput">
                    <option value selected>No of infants</option>
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>

                <input type="number" name="price" placeholder="Price">
            </div>

            <div class="line"></div>

            <div class="reservationinfo">
                <h4>Reservation information</h4>

                <label for="din">Date of reservation</label>
                <input name="din" type="date">
                <div class="datesection">
                    <span>
                        <label for="cin">Check-In</label>
                        <input name="cin" type="date">
                    </span>
                    <span>
                        <label for="cout">Check-Out</label>
                        <input name="cout" type="date">
                    </span>
                </div>

                <select name="RoomType" id="RoomType" class="selectinput">
                    <option value="" disabled selected>Select Room Type</option>
                    <?php
                    // Ερώτημα για να πάρεις τους τύπους δωματίων από τη βάση
                    $rooms_query = "SELECT DISTINCT rtype FROM room";
                    $result = mysqli_query($conn, $rooms_query);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['rtype'] . "'>" . $row['rtype'] . "</option>";
                        }
                    } else {
                        echo "<option value='' disabled>No rooms available</option>";
                    }
                    ?>
                </select>

                <select id="BeddingNumber" name="BeddingNumber" class="selectinput" required>
                    <option value="" disabled selected>First, select a room type</option>
                </select>

                <select name="Meal" class="selectinput">
                    <option value selected>Meal</option>
                    <option value="HB">HB</option>
                    <option value="FB">FB</option>
                    <option value="AI">AI</option>
                </select>
            </div>
        </div>

        <div class="foot">
            <input type="submit" value="Update">
        </div>
    </form>
</div>

<script>
// Άνοιγμα νέου παραθύρου με τις πληροφορίες της κράτησης
function openReservationForm(reservationIdName) {  // Χρησιμοποιούμε reservationIdName αντί reservationId
    $.ajax({
        type: 'POST',
        url: 'getReservationDetails.php',  // Το PHP αρχείο που επιστρέφει τα δεδομένα
        data: { idname: reservationIdName },  // Αλλάζουμε το όνομα του παραμέτρου σε idname
        success: function(response) {
            const reservation = JSON.parse(response);
            
            // Δημιουργία HTML περιεχομένου για το νέο παράθυρο
            const newWindowContent = `
                <html>
                <head>
                    <title>Κράτηση Λεπτομέρειες</title>
                    <style>
                        body { font-family: Arial, sans-serif; background-color: #f1f1f1; padding: 20px; }
                        .container { background-color: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
                        h2 { color: #2c3e50; }
                        .field { margin: 10px 0; }
                        .field label { font-weight: bold; }
                        .field input { width: 100%; padding: 10px; border-radius: 5px; margin-top: 5px; border: 1px solid #ddd; }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h2>Λεπτομέρειες Κράτησης</h2>
                        <div class="field">
                            <label for="idname">ID Name</label>
                            <input type="text" id="idname" value="${reservation.idname}" readonly>
                        </div>
                        <div class="field">
                            <label for="Name">Όνομα</label>
                            <input type="text" id="Name" value="${reservation.Name}" readonly>
                        </div>
                        <div class="field">
                            <label for="Noofadult">Αριθμός Ενηλίκων</label>
                            <input type="text" id="Noofadult" value="${reservation.Noofadult}" readonly>
                        </div>
                        <div class="field">
                            <label for="Noofchild">Αριθμός Παιδιών</label>
                            <input type="text" id="Noofchild" value="${reservation.Noofchild}" readonly>
                        </div>
                        <div class="field">
                            <label for="Noofinfant">Αριθμός Βρεφών</label>
                            <input type="text" id="Noofinfant" value="${reservation.Noofinfant}" readonly>
                        </div>
                        <div class="field">
                            <label for="cout">Ημερομηνία Κράτησης</label>
                            <input type="text" id="cout" value="${reservation.din}" readonly>
                        </div>
                        <div class="field">
                            <label for="din">Ημερομηνία Άφιξης</label>
                            <input type="text" id="din" value="${reservation.cin}" readonly>
                        </div>
                        <div class="field">
                            <label for="cin">Ημερομηνία Αναχώρησης</label>
                            <input type="text" id="cin" value="${reservation.cout}" readonly>
                        </div>
                        <div class="field">
                            <label for="RoomType">Τύπος Δωματίου</label>
                            <input type="text" id="RoomType" value="${reservation.RoomType}" readonly>
                        </div>
                        <div class="field">
                            <label for="BeddingNumber">Αριθμός Δωματίου</label>
                            <input type="text" id="BeddingNumber" value="${reservation.BeddingNumber}" readonly>
                        </div>
                        <div class="field">
                            <label for="Meal">Γεύμα</label>
                            <input type="text" id="Meal" value="${reservation.Meal}" readonly>
                        </div>
                    </div>
                </body>
                </html>
            `;

            // Άνοιγμα νέου παραθύρου και εμφάνιση του περιεχομένου
            const newWindow = window.open('', '_blank', 'width=800,height=600');
            newWindow.document.write(newWindowContent);
            newWindow.document.close();
        },
        error: function(xhr, status, error) {
            console.error("Error loading reservation details:", error);
        }
    });
}
</script>
</body>
</html>











