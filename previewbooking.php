<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Booking Details</title>
</head>
<body>
<?php
// Include database configuration file
include "config.php";

// Connect to MySQL database
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

// Check for connection errors
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL." . mysqli_connect_error();
    exit; // Stop processing the page further if connection fails
}

// Check if id exists and is valid
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid booking id</h2>";
        exit; // Stop processing if booking id is invalid or missing
    }
}


// Query to retrieve booking details based on booking ID
$query = 'SELECT booking.BookingID, room.roomname,
                 booking.Checkin_date, booking.Checkout_date,
                 customer.firstname, customer.lastname,
                 booking.Contact_number, booking.Booking_extra, booking.Room_review
          FROM booking
          INNER JOIN room ON booking.RoomID = room.roomID
          INNER JOIN customer ON booking.CustomerID = customer.customerID
          WHERE booking.BookingID=' . $id;

// Execute the query
$result = mysqli_query($DBC, $query);

// Count the number of rows returned
$rowcount = mysqli_num_rows($result);
?>
 
<!-- HTML section to display booking details -->
<h1>Booking Details View</h1>
<h2>
    <a href="listbookings.php">[Return to the booking listing]</a>
    <a href="index.php">[Return to the main page]</a>
</h2>
<?php
// Check if any rows were returned from the query
if ($rowcount > 0) {
    echo "<fieldset><legend>Booking Detail #$id</legend><dl>";
    $row = mysqli_fetch_assoc($result);

    // Display each booking detail
    echo "<dt>Room name: </dt><dd>" . $row['roomname'] . "</dd>" . PHP_EOL;
    echo "<dt>Check-in Date: </dt><dd>" . $row['Checkin_date'] . "</dd>" . PHP_EOL;
    echo "<dt>Check-out Date: </dt><dd>" . $row['Checkout_date'] . "</dd>" . PHP_EOL;
    echo "<dt>Customer Name: </dt><dd>" . $row['firstname'] . " " . $row['lastname'] . "</dd>" . PHP_EOL;
    echo "<dt>Contact Number: </dt><dd>" . $row['Contact_number'] . "</dd>" . PHP_EOL;
    echo "<dt>Booking Extra: </dt><dd>" . $row['Booking_extra'] . "</dd>" . PHP_EOL;
    echo "<dt>Room Review: </dt><dd>" . $row['Room_review'] . "</dd>" . PHP_EOL;

    echo '</dl></fieldset>' . PHP_EOL;

} else {
    echo "<h5>No booking found! Possibly deleted!</h5>";
}

// Free result set and close database connection
mysqli_free_result($result);
mysqli_close($DBC);
?>
 
</body>
</html>
