<?php
// Σύνδεση με τη βάση δεδομένων
$server = "localhost";
$username = "bluebird_user";
$password = "password";
$database = "bluebirdhotel";

$conn = mysqli_connect($server, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Συνάρτηση για ανάκτηση των δωματίων
function getRooms($conn) {
    $sql = "SELECT id, type FROM room";
    $result = mysqli_query($conn, $sql);
    $resources = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $resources[] = [
            'id' => $row['id'],
            'title' => $row['type']
        ];
    }
    return $resources;
}

// Συνάρτηση για λήψη κρατήσεων ως events
function getReservations($conn, $startDate, $endDate) {
    // Χρησιμοποιούμε προετοιμασμένα statements για ασφάλεια
    $sql = "SELECT id, Name, Noofadult, Noofchild, Noofinfant, RoomType, Bed, Meal, cin, cout 
            FROM roombook 
            WHERE (cin BETWEEN ? AND ?) 
               OR (cout BETWEEN ? AND ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssss', $startDate, $endDate, $startDate, $endDate);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $events = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $events[] = [
            'resourceId' => $row['id'], // Σύνδεση κράτησης με το δωμάτιο
            'title' => $row['Name'],
            'start' => $row['cin'],
            'end' => $row['cout'],
            'extendedProps' => [
                'name' => $row['Name'],
                'adults' => $row['Noofadult'],
                'children' => $row['Noofchild'],
                'infants' => $row['Noofinfant'],
                'roomType' => $row['RoomType'],
                'bed' => $row['Bed'],
                'meal' => $row['Meal']
            ]
        ];
    }

    return $events;
}

// Λήψη ημερομηνιών
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date("Y-m-d");
$endDate = date("Y-m-d", strtotime("$startDate +29 days")); // 30 ημέρες από την επιλεγμένη ημερομηνία

// Λήψη δωματίων και κρατήσεων
$resources = getRooms($conn);
$events = getReservations($conn, $startDate, $endDate);

// Κλείσιμο σύνδεσης με τη βάση
mysqli_close($conn);

// Επιστροφή δεδομένων σε JSON για χρήση στο JavaScript
header('Content-Type: application/json');
echo json_encode(['resources' => $resources, 'events' => $events]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room Reservation Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/resource-timeline.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/resource-timeline.min.js"></script>
    <style>
        #calendar {
            max-width: 900px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <h2>Room Reservation Calendar</h2>

    <div id="calendar"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'resourceTimelineMonth',
                resourceAreaHeaderContent: 'Rooms',
                resources: <?php echo json_encode($resources); ?>,  // Διασφαλίζει ότι τα δεδομένα των δωματίων περνάνε σωστά
                events: <?php echo json_encode($events); ?>,  // Διασφαλίζει ότι τα δεδομένα των κρατήσεων περνάνε σωστά
                eventClick: function(info) {
                    // Άνοιγμα παραθύρου με στοιχεία κράτησης
                    var reservationInfo = ` 
                        <h3>Reservation Details</h3>
                        <p><strong>Name:</strong> ${info.event.extendedProps.name}</p>
                        <p><strong>Adults:</strong> ${info.event.extendedProps.adults}</p>
                        <p><strong>Children:</strong> ${info.event.extendedProps.children}</p>
                        <p><strong>Infants:</strong> ${info.event.extendedProps.infants}</p>
                        <p><strong>Room Type:</strong> ${info.event.extendedProps.roomType}</p>
                        <p><strong>Bedding:</strong> ${info.event.extendedProps.bed}</p>
                        <p><strong>Meal:</strong> ${info.event.extendedProps.meal}</p>
                        <p><strong>Check-In:</strong> ${info.event.start.toISOString().split('T')[0]}</p>
                        <p><strong>Check-Out:</strong> ${info.event.end.toISOString().split('T')[0]}</p>
                    `;
                    var newWindow = window.open("", "Reservation Details", "width=400,height=400");
                    newWindow.document.write(reservationInfo);
                }
            });
            calendar.render();
        });
    </script>

</body>
</html>

