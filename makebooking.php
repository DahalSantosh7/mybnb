<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
    <script>
        $(document).ready(function(){
    $(".fromDate").datepicker({dateFormat:"yy-mm-dd"});
    $(".toDate").datepicker({dateFormat:"yy-mm-dd"});
});

function searchRooms() {
    var fromDate = $(".fromDate").val();
    var toDate = $(".toDate").val();

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            $("#result").html(this.responseText);
        }
    }
    xhttp.open("GET", "roomsearch.php?fromDate=" + fromDate + "&toDate=" + toDate, true);
    xhttp.send();
}


    </script>
</head>
<body>
<?php
include "config.php";
 
$DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);
 
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
    exit;
}
 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $roomID = intval($_POST['room']);
    $checkinDate = mysqli_real_escape_string($DBC, $_POST['fromDate']);
    $checkoutDate = mysqli_real_escape_string($DBC, $_POST['toDate']);
    $contactNumber = mysqli_real_escape_string($DBC, $_POST['contactNumber']);
    $bookingExtras = mysqli_real_escape_string($DBC, $_POST['bookingExtras']);
 
    // Assuming customerID is known (replace with appropriate value or session variable)
    $customerID = 1; // Example value, replace with actual customerID
 
    $query = "INSERT INTO booking (RoomID, CustomerID, Checkin_date, Checkout_date, Contact_number, Booking_extra)
              VALUES ('$roomID', '$customerID', '$checkinDate', '$checkoutDate', '$contactNumber', '$bookingExtras')";
 
    if (mysqli_query($DBC, $query)) {
        echo "<h2>Booking successfully added!</h2>";
    } else {
        echo "<h2>Error: " . mysqli_error($DBC) . "</h2>";
    }
 
    mysqli_close($DBC);
} else {
    echo "<h2>Invalid request method.</h2>";
}
?>
 
    <h2>Make a Booking</h2>
    <h2>
        <a href="listbookings.php">[Return to the Booking Listing]</a>
        <a href="index.php">[Return to the Main Page]</a>
    </h2>
    <h3>Booking for Test</h3>
    <form method="post">
        <p>
            <label for="room">Room (name, type, beds):</label>
            <select name="room" id="room" required>
                <?php
                include "config.php";
                $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
                if (mysqli_connect_errno()) {
                    echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
                    exit;
                }
               
                $query = "SELECT roomID, roomname, roomtype, beds FROM room";
                $result = mysqli_query($DBC, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['roomID'] . '">' . $row['roomname'] . ' ' . $row['roomtype'] . ' ' . $row['beds'] . '</option>';
                }
               
                mysqli_free_result($result);
                mysqli_close($DBC);
                ?>
            </select>
        </p>
        <p>
            <label for="fromDate">Check-In Date:</label>
            <input type="text" class="fromDate" name="fromDate" placeholder="yy-mm-dd" pattern="\d{4}-\d{2}-\d{2}" title="Date format: yyyy-mm-dd" required>
        </p>
        <p>
            <label for="toDate">Check-Out Date:</label>
            <input type="text" class="toDate" name="toDate" placeholder="yy-mm-dd" pattern="\d{4}-\d{2}-\d{2}" title="Date format: yyyy-mm-dd" required>
        </p>
        <p>
            <label for="contactNumber">Contact Number:</label>
            <input type="tel" id="contactNumber" name="contactNumber" placeholder="(###) ###-####" title="Phone number format: (###) ###-####" required>
        </p>
        <p>
            <label for="bookingExtras">Booking Extras:</label>
            <textarea id="bookingExtras" name="bookingExtras" rows="5" cols="30"></textarea>
        </p>
        <p>
            <input type="submit" value="Add">
            <input type="reset" value="Cancel">
        </p>
    </form>

    <div class="container">
    <h1>Search Rooms</h1>
    <p>
      <label for="fromDate">From Date:</label>
      <input type="text" class="fromDate" name="fromDate" placeholder="yy-mm-dd" pattern="\d{4}-\d{2}-\d{2}" title="Date format: yyyy-mm-dd" required>

      <label for="toDate">To Date:</label>
      <input type="text" class="toDate" name="toDate" placeholder="yy-mm-dd" pattern="\d{4}-\d{2}-\d{2}" title="Date format: yyyy-mm-dd" required>
    </p>
    <p>
      <input type="button" value="Search" onclick="searchRooms()">
    </p>
    <div id="result"></div>
  </div>

</body>
</html>
