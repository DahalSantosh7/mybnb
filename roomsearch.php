<?php
// Database connection settings
$servername = "127.0.0.1:3306";
$username = "root";
$password = "";
$dbname = "bnb";

// Get the from date and to date from the query string
$fromDate = $_GET['fromDate'];
$toDate = $_GET['toDate'];

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Prepare the SQL query to search for available rooms
$sql = "SELECT * FROM room WHERE roomID NOT IN (
            SELECT RoomID
            FROM booking
            WHERE Checkin_date <= ? AND Checkout_date >= ?
        )";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $toDate, $fromDate); // Note: Parameters are swapped for correct SQL logic
$stmt->execute();

// Get the result of the query
$result = $stmt->get_result();


// Build HTML for displaying results
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Room ID</th>
                <th>Room Name</th>
                <th>Description</th>
                <th>Room Type</th>
                <th>Beds</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['roomID'] . "</td>";
        echo "<td>" . $row['roomname'] . "</td>";
        echo "<td>" . $row['description'] . "</td>";
        echo "<td>" . $row['roomtype'] . "</td>";
        echo "<td>" . $row['beds'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No available rooms found.";
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();
?>
