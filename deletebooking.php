<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Booking</title>
</head>
 
<body>
    <?php
    include "config.php";
    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);
 
    
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL." . mysqli_connect_error();
        exit; //stop processing the page further.
    }
 
    function cleanInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }
 
    // Check if id exists
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) || !is_numeric($id)) {
            echo "<h2>Invalid booking id</h2>";
            exit;
        } else {
            $id = intval($id);
        }
    }
 
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && $_POST['submit'] == 'Delete') {
        $error = 0;
        $msg = "Error: ";
 
        // Validate ID
        if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {
            $id = intval($_POST['id']);
        } else {
            $error++;
            $msg .= 'Invalid Booking ID';
            $id = 0;
        }
 
        if ($error == 0 && $id > 0) {
            $query = "DELETE FROM booking WHERE BookingID = ?";
            $stmt = mysqli_prepare($DBC, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
 
            // Check if deletion was successful
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "<h5>Booking deleted.</h5>";
            } else {
                echo "<h5>Error: Failed to delete booking.</h5>";
            }
 
            mysqli_stmt_close($stmt);
        } else {
            echo "<h5>$msg</h5>" . PHP_EOL;
        }
    }
 
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $query = 'SELECT booking.BookingID, room.roomname,
                  booking.Checkin_date, booking.Checkout_date,
                  customer.firstname, customer.lastname
                  FROM booking
                  INNER JOIN room ON booking.RoomID = room.roomID
                  INNER JOIN customer ON booking.CustomerID = customer.customerID
                  WHERE BookingID = ?';
 
        $stmt = mysqli_prepare($DBC, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
 
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $bookingID = $row['BookingID'];
 
            echo "<h1>Booking Details View</h1>
                  <h2><a href='listbookings.php'>[Return to the booking listing]</a>
                  <a href='index.php'>[Return to the main page]</a></h2>";
 
            echo "<fieldset><legend>Booking Detail #$bookingID</legend><dl>";
            echo "<dt>Room name:</dt><dd>{$row['roomname']}</dd>" . PHP_EOL;
            echo "<dt>Check-in Date:</dt><dd>{$row['Checkin_date']}</dd>" . PHP_EOL;
            echo "<dt>Check-out Date:</dt><dd>{$row['Checkout_date']}</dd>" . PHP_EOL;
            echo "<dt>Customer Name:</dt><dd>{$row['firstname']} {$row['lastname']}</dd>" . PHP_EOL;
            echo '</dl></fieldset>' . PHP_EOL;
 
            echo "<form method='POST'>
                    <h4>Are you sure you want to delete this booking?</h4>
                    <input type='hidden' name='id' value='$bookingID'>
                    <input type='submit' name='submit' value='Delete'>
                    <a href='listbookings.php'>Cancel</a>
                  </form>";
        } else {
            echo "<h5>No booking found! Possibly deleted!</h5>";
        }
 
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
    }
 
    mysqli_close($DBC);
    ?>
</body>
 
</html>
