<!DOCTYPE html>
<html lang="en">
 
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Bookings</title>
</head>
 
<body>
 
    <?php
    include "config.php";
 
    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);
 
    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; //stop processing the page further
    }
 
    
    // Prepare a query and send it to the server
    $query = "SELECT
        booking.BookingID,
        room.roomname,
        booking.Checkin_date,
        booking.Checkout_date,
        customer.firstname,
        customer.lastname
    FROM
        booking
    JOIN
        room ON booking.RoomID = room.roomID
    JOIN
        customer ON booking.CustomerID = customer.customerID
    ORDER BY
        booking.BookingID;";
 
    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);
    ?>
 
    <h1>Current Bookings</h1>
    <h2><a href="makebooking.php">[Make a Booking]</a><a href="index.php">[Return to Main Page]</a></h2>
 
    <table border="1">
        <thead>
            <tr>
                <th>Current Bookings (Room, Check-in Date, Check-out Date)</th>
                <th>Customer</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($rowcount > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['BookingID'];
                    echo '<tr><td>' . $row['roomname'] . ', ' . $row['Checkin_date'] . ', ' . $row['Checkout_date'] . '</td>';
                    echo '<td>' . $row['firstname'] . ' ' . $row['lastname'] . '</td>';
                    echo '<td><a href="previewbooking.php?id=' . $id . '">[view]</a>';
                    echo '<a href="editbooking.php?id=' . $id . '">[edit]</a>';
                    echo '<a href="reviewbooking.php?id=' . $id . '">[manage room]</a>';
                    echo '<a href="deletebooking.php?id=' . $id . '">[delete]</a></td>';
                    echo '</tr>' . PHP_EOL;
                }
            } else {
                echo "<tr><td colspan='3'><h2>No bookings found!</h2></td></tr>";
            }
            mysqli_free_result($result);
            mysqli_close($DBC);
            ?>
        </tbody>
    </table>
</body>
</html>
