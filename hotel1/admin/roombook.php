<?php
session_start();
include '../config.php';
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Database connection successful";
}
error_reporting(E_ALL);
ini_set('display_errors', 1);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- boot -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <!-- fontowesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!--script1-->
    <script src="javascript/script1.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- sweet alert -->
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./css/roombook.css">
    <title>BlueBird - Admin</title>
</head>

<body>
    <!-- guestdetailpanel -->

    <div id="guestdetailpanel">
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
						<option value selected >No of adults</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <!-- <option value="1">2</option>
                        <option value="1">3</option> -->
                    </select>
                    <select name="Noofchild" class="selectinput">
						<option value selected >No of childrer</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <!-- <option value="1">2</option>
                        <option value="1">3</option> -->
                    </select>
                    <select name="Noofinfant" class="selectinput">
						<option value selected >No of infants</option>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <!-- <option value="1">2</option>
                        <option value="1">3</option> -->
                    </select>
                    
                    <input type="number" name="price" placeholder="Price">

                    
                </div>

                <div class="line"></div>

                <div class="reservationinfo">
                    <h4>Reservation information</h4>

                    <label for="din"> Date of reservation</label>
                            <input name="din" type ="date">
                            <div class="datesection">
                        <span>
                            <label for="cin"> Check-In</label>
                            <input name="cin" type ="date">
                        </span>
                        <span>
                            <label for="cout"> Check-Out</label>
                            <input name="cout" type ="date">
                        </span>
                    </div>
                            
<!-- second page form -->
 <!-- Dropdown για διαθέσιμα δωμάτια -->
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

        <!-- Dropdown για bedding που θα γεμίζει δυναμικά -->
       
            <select id="BeddingNumber" name="BeddingNumber" class="selectinput" required>
                <option value="" disabled selected>First, select a room type</option>
            </select>
        
            <script>
$(document).ready(function() {
    $('#RoomType').change(function() {
        var selectedType = $(this).val(); // Επιλεγμένος τύπος δωματίου
        var cin = $('input[name="cin"]').val(); // Ημερομηνία check-in
        var cout = $('input[name="cout"]').val(); // Ημερομηνία check-out

        if (selectedType && cin && cout) {
            // AJAX αίτημα για λήψη διαθέσιμων bedding
            $.ajax({
                type: 'POST',
                url: 'getBeddingOptions.php', // PHP αρχείο που θα δημιουργήσουμε
                data: { rtype: selectedType, cin: cin, cout: cout },
                success: function(response) {
                    console.log(response); // Εμφάνιση για debugging
                    $('#BeddingNumber').html(response); // Ενημέρωση dropdown με τις διαθέσιμες επιλογές
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    alert('Error while fetching bedding options');
                }
            });
        } else {
            alert('Please select room type and ensure dates are filled.');
            $('#BeddingNumber').html('<option value="" disabled selected>First, select a room type</option>');
        }
    });
});
</script>


    

                    <!--<select name="NoofRoom" class="selectinput">
						<option value selected >No of Room</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                       </select>
                    -->
                   
                    <select name="Meal" class="selectinput">
						<option value selected >Meal</option>
                        <option value="Room only">Room only</option>
                        <option value="Breakfast">Breakfast</option>
						<option value="Half Board">Half Board</option>
						<option value="Full Board">Full Board</option>
					</select>
                    
                </div>
            </div>
            <div class="footer">
                <button class="btn btn-success" name="guestdetailsubmit">Submit</button>
            </div>
        </form>
   
        <?php
        if (isset($_POST['guestdetailsubmit'])) {
    // Λήψη των δεδομένων από τη φόρμα
            $idname = $_POST['idname'];
            $Name = $_POST['Name'];
            $Noofadult = $_POST['Noofadult'];
            $Noofchild = $_POST['Noofchild'];
            $Noofinfant = $_POST['Noofinfant'];
            $RoomType = $_POST['RoomType']; 
            $BeddingNumber = $_POST['BeddingNumber'];
            $Meal = $_POST['Meal'];
            $cin = $_POST['cin'];
            $cout = $_POST['cout'];
            $din = $_POST['din'];
    
    // Έλεγχος για κενά πεδία (προαιρετικά)
        if (empty($Name) || empty($idname) || empty($cin) || empty($cout)) {
        echo "<script>swal({
            title: 'Please fill in all required fields.',
            icon: 'error',
        });</script>";
        } else {
        // Προετοιμασία για την εισαγωγή στη βάση δεδομένων
        $sta = "NotConfirm";
        $sql = "INSERT INTO roombook(idname,Name, Noofadult, Noofchild, Noofinfant, RoomType,  BeddingNumber, Meal, cin, cout, stat, din, nodays)
                VALUES ('$idname', '$Name', '$Noofadult', '$Noofchild', '$Noofinfant', '$RoomType', '$BeddingNumber', '$Meal', '$cin', '$cout', '$sta', '$din', DATEDIFF('$cout', '$cin'))";
        
        // Εκτέλεση του SQL query
        $result = mysqli_query($conn, $sql);
        
        // Έλεγχος αν το query εκτελέστηκε επιτυχώς
        if ($result) {
            echo "<script>
                swal({
                    title: 'Reservation successful',
                    icon: 'success',
                }).then(function() {
                    window.location.href = 'roombook.php'; // Redirect to roombook.php after the alert
                });
            </script>";
        } else {
            echo "<script>
                swal({
                    title: 'Something went wrong',
                    text: 'Error: " . mysqli_error($conn) . "',
                    icon: 'error',
                });
            </script>";
        }
    }
}
?>
     
        <!-- ==== room book php ====-->
        <?php       
            if (isset($_POST['guestdetailsubmit'])) {
                $Name = $_POST['Name'];
                $idname=$_POST['idname'];
                $Noofadult = $_POST['Noofadult'];
                $Noofchild = $_POST['Noofchild'];
                $Noofinfant = $_POST['Noofinfant'];
                $RoomType = $_POST['RoomType']; // Προστέθηκε
                $BeddingNumber = $_POST['BeddingNumber'];
                $Meal = $_POST['Meal'];
                $cin = $_POST['cin'];
                $cout = $_POST['cout'];
                $din = $_POST['din'];

                if($Name == "" || $idname ==""){
                    echo "<script>swal({
                        title: 'Fill the proper details',
                        icon: 'error',
                    });
                    </script>";
                }
                else{
                    $sta = "NotConfirm";
                    $sql = "INSERT INTO roombook(Name, idname,Noofadult, Noofchild, Noofinfant, RoomType, BeddingNumber, Meal, cin, cout, stat, din, nodays) 
                    VALUES ('$Name','idname', '$Noofadult', '$Noofchild', '$Noofinfant', '$RoomType', '$Bed', '$BeddingNumber', '$Meal', '$cin', '$cout', '$sta', '$din', DATEDIFF('$cout', '$cin'))";

                    $result = mysqli_query($conn, $sql);

                    

                    // if($f1=="NO")
                    // {
                    //     echo "<script>swal({
                    //         title: 'Superior Room is not available',
                    //         icon: 'error',
                    //     });
                    //     </script>";
                    // }
                    // else if($f2=="NO")
                    // {
                    //     echo "<script>swal({
                    //         title: 'Guest House is not available',
                    //         icon: 'error',
                    //     });
                    //     </script>";
                    // }
                    // else if($f3 == "NO")
                    // {
                    //     echo "<script>swal({
                    //         title: 'Si Room is not available',
                    //         icon: 'error',
                    //     });
                    //     </script>";
                    // }
                    // else if($f4 == "NO")
                    // {
                    //     echo "<script>swal({
                    //         title: 'Deluxe Room is not available',
                    //         icon: 'error',
                    //     });
                    //     </script>";
                    // }
                    // else if($result = mysqli_query($conn, $sql))
                    // {
                        if ($result) {
                            echo "<script>swal({
                                title: 'Reservation successful',
                                icon: 'success',
                            });
                        </script>";
                        } else {
                            echo "<script>swal({
                                    title: 'Something went wrong',
                                    icon: 'error',
                                });
                        </script>";
                        }
                    // }
                }
            }
        ?>
    </div>

    
    <!-- ================================================= -->
    <div class="searchsection">
        <input type="text" name="search_bar" id="search_bar" placeholder="search..." onkeyup="searchFun()">
        <button class="adduser" id="adduser" onclick="adduseropen()"><i class="fa-solid fa-bookmark"></i> Add</button>
        <form action="./exportdata.php" method="post">
            <button class="exportexcel" id="exportexcel" name="exportexcel" type="submit"><i class="fa-solid fa-file-arrow-down"></i></button>
        </form>
    </div>

    <div class="roombooktable" class="table-responsive-xl">
        <?php
            $roombooktablesql = "SELECT * FROM roombook";
            $roombookresult = mysqli_query($conn, $roombooktablesql);
            $nums = mysqli_num_rows($roombookresult);
        ?>
        <table class="table table-bordered" id="table-data">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Id Reservation</th>
                    <th scope="col">Name</th>
                    <th scope="col">Adults</th>
                    <th scope="col">Childs</th>
                    <th scope="col">Infant</th>
                    <th scope="col">Type of Room</th>
                    <th scope="col">Date of Reservation</th>
                    <th scope="col">No of Room</th>
                    <th scope="col">Meal</th>
                    <th scope="col">Check-In</th>
                    <th scope="col">Check-Out</th>
                    <th scope="col">No of Day</th>
                    <th scope="col">Status</th>
                    <th scope="col" class="action">Action</th>
                    <!-- <th>Delete</th> -->
                </tr>
            </thead>

            <tbody>
            <?php
            while ($res = mysqli_fetch_array($roombookresult)) {
            ?>
                <tr>
                    <td><?php echo $res['id'] ?></td>
                    <td><?php echo $res['idname'] ?></td>
                    <td><?php echo $res['Name'] ?></td>                    
                    <td><?php echo $res['Noofadult'] ?></td>
                    <td><?php echo $res['Noofchild'] ?></td>
                    <td><?php echo $res['Noofinfant'] ?></td>
                    <td><?php echo $res['RoomType'] ?></td>
                    <td><?php echo $res['din'] ?></td>
                    <td><?php echo $res['BeddingNumber'] ?></td>
                    <td><?php echo $res['Meal'] ?></td>
                    <td><?php echo $res['cin'] ?></td>
                    <td><?php echo $res['cout'] ?></td>
                    <td><?php echo $res['nodays'] ?></td>
                    <td><?php echo $res['stat'] ?></td>
                    <td class="action">
                        <?php
                            if($res['stat'] == "Confirm")
                            {
                                echo " ";
                            }
                            else
                            {
                                echo "<a href='roomconfirm.php?id=". $res['id'] ."'><button class='btn btn-success'>Confirm</button></a>";
                            }
                        ?>
                        <a href="roombookedit.php?id=<?php echo $res['id'] ?>"><button class="btn btn-primary">Edit</button></a>
                        <a href="roombookdelete.php?id=<?php echo $res['id'] ?>"><button class='btn btn-danger'>Delete</button></a>
                    </td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
<script src="./javascript/roombook.js"></script>



</html>
