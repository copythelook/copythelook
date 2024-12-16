<?php
session_start();
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BlueBird - Admin</title>
    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/room.css">
</head>

<body>
    <div class="addroomsection">
        <form action="" method="POST">
            <label for="rtype">Type of Room :</label>
            <select name="rtype" class="form-control">
                <option value selected></option>
                <option value="APTS SEA VIEW">APTS SEA VIEW</option>
                <option value="APTS GARDEN VIEW">APTS GARDEN VIEW</option>
                <option value="STUDIO SEA VIEW">STUDIO SEA VIEW</option>
                <option value="STUDIO GARDEN VIEW">STUDIO GARDEN VIEW</option>
                <option value="Overbook">OVERBOOK</option>
            </select>

            <label for="bedding_number">Bedding Number:</label>
            <input type="text" name="bedding_number" class="form-control" required>

            <button type="submit" class="btn btn-success" name="addroom">Add Room</button>
        </form>

        <?php
        if (isset($_POST['addroom'])) {
            $typeofroom = $_POST['rtype'];
            $bedding_number = $_POST['bedding_number'];

            $sql = "INSERT INTO room (rtype, bedding) VALUES ('$typeofroom', '$bedding_number')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                header("Location: room.php");
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        ?>
    </div>

    <!-- Room Selection Form -->
    <div class="roomselection">
        <form action="" method="POST">
            <label for="RoomType">Select Room Type:</label>
            <select name="RoomType" class="selectinput">
                <option value="" disabled selected>Select Room Type</option>
                <?php
                // Query to get all distinct room types
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

            <button type="submit" class="btn btn-primary" name="showrooms">Show Rooms</button>
        </form>

        <?php
        if (isset($_POST['showrooms'])) {
            $roomType = $_POST['RoomType'];

            // Query to get all room numbers for the selected type
            $rooms_query = "SELECT * FROM room WHERE rtype = '$roomType'";
            $result = mysqli_query($conn, $rooms_query);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<h4>Rooms for $roomType:</h4><ul>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<li>Room ID: " . $row['id'] . " - Bedding Number: " . $row['bedding'] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No rooms available for this type.</p>";
            }
        }
        ?>
    </div>

    <div class="room">
        <?php
        $sql = "SELECT * FROM room";
        $re = mysqli_query($conn, $sql);
        ?>
        <?php
        while ($row = mysqli_fetch_array($re)) {
            $id = $row['rtype'];
            $bedding_number = $row['bedding']; // Bedding number

            if ($id == "APTS SEA VIEW") {
                echo "<div class='roombox roomboxsuperior'>
                        <div class='text-center no-boder'>
                            <i class='fa-solid fa-bed fa-4x mb-2'></i>
                            <h3>" . $row['rtype'] . "  " . $bedding_number . "</h3>
                            <a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div>";
            } else if ($id == "APTS GARDEN VIEW") {
                echo "<div class='roombox roomboxdelux'>
                        <div class='text-center no-boder'>
                            <i class='fa-solid fa-bed fa-4x mb-2'></i>
                            <h3>" . $row['rtype'] . "  " . $bedding_number . "</h3>
                            <a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div>";
            } else if ($id == "STUDIO SEA VIEW") {
                echo "<div class='roombox roomboguest'>
                        <div class='text-center no-boder'>
                            <i class='fa-solid fa-bed fa-4x mb-2'></i>
                            <h3>" . $row['rtype'] . "  " . $bedding_number . "</h3>
                            <a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div>";
            } else if ($id == "STUDIO GARDEN VIEW") {
                echo "<div class='roombox roomboxsingle'>
                        <div class='text-center no-boder'>
                            <i class='fa-solid fa-bed fa-4x mb-2'></i>
                            <h3>" . $row['rtype'] . " " . $bedding_number . "</h3>
                            <a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div>";
            } else if ($id == "Overbook") {
                echo "<div class='roombox roomboxdelux'>
                        <div class='text-center no-boder'>
                            <i class='fa-solid fa-bed fa-4x mb-2'></i>
                            <h3>" . $row['rtype'] . "  " . $bedding_number . "</h3>
                            <a href='roomdelete.php?id=". $row['id'] ."'><button class='btn btn-danger'>Delete</button></a>
                        </div>
                    </div>";
            }
        }
        ?>
    </div>

</body>

</html>
