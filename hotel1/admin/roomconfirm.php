<?php
include '../config.php';

$id = $_GET['id'];

// Fetch reservation details
$sql = "SELECT * FROM roombook WHERE id = '$id'";
$re = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($re);

if ($row) {
    $Name = $row['Name'];
    $idname = $row['idname'];
    $Noofadult = $row['Noofadult'];
    $Noofchild = $row['Noofchild'];
    $Noofinfant = $row['Noofinfant'];
    $type = $row['type'];
    $Bed = $row['Bed'];
    $NoofRoom = $row['NoofRoom'];
    $Meal = $row['Meal'];
    $cin = $row['cin'];
    $cout = $row['cout'];
    $din = $row['din'];
    $stat = $row['stat']; // Reservation status
}

// If status is not "Confirm", update it to "Confirm"
if ($stat == "NotConfirm") {
    // Update the booking status to Confirm
    $sql = "UPDATE roombook SET stat = 'Confirm' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Redirect to the roombook page after updating
        header("Location: roombook.php");
        exit();  // Ensure no further code runs
    } else {
        echo "<script>alert('Error while confirming the reservation');</script>";
    }
} else {
    // If already confirmed, show an alert
    echo "<script>alert('Reservation already confirmed');</script>";
    header("Location: roombook.php");
    exit();
}
?>
