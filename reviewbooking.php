<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room Review</title>
</head>
<body>
    <?php
    // Include config file
    include "config.php"; // Load database credentials

    // Connect to the database
    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

    // Check connection
    if (mysqli_connect_error()) {
        echo "Error: Unable to connect to MySQL. " . mysqli_connect_error();
        exit; // Stop further processing
    }

    // Function to clean input (not validating type and content)
    function cleanInput($data)
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // Check if 'id' exists and is valid
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $id = $_GET['id'];
        if (empty($id) || !is_numeric($id)) {
            echo "<h2>Invalid booking ID</h2>";
            exit;
        }
    }
    

    // Handle form submission
    if (isset($_POST['submit']) && $_POST['submit'] == 'Update') {
        $roomReview = cleanInput($_POST['room_review']);
        $id = cleanInput($_POST['id']);

        // Prepare update statement
        $upd = "UPDATE `booking` SET Room_review=? WHERE BookingID=?";
        $stmt = mysqli_prepare($DBC, $upd); // Prepare the query
        mysqli_stmt_bind_param($stmt, 'si', $roomReview, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        // Print update confirmation message
        echo "<h5>Room review updated</h5>";
    }

    // Fetch current room review for display
    $query = "SELECT Room_review FROM `booking` WHERE BookingID=" . $id;
    $result = mysqli_query($DBC, $query);
    $rowcount = mysqli_num_rows($result);

    ?>
    <h1>Edit Room Review</h1>
    <h2>
        <a href='listbookings.php'>[Return to the Booking list]</a>
        <a href="index.php">[Return to main page]</a>
    </h2>
    <div>
        <div>
            <form method="POST">
                <div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                </div>
                <?php
                if ($rowcount > 0) {
                    $row = mysqli_fetch_assoc($result);
                    ?>
                    <div>
                        <label for="room_review">Room Review:</label>
                        <input type="text" id="room_review" name="room_review" value="<?php echo $row['Room_review']; ?>">
                    </div>
                    <?php
                } else {
                    echo "<h5>No booking found!</h5>";
                }
                ?>
                <br> <br>
                <div>
                    <input type="submit" name="submit" value="Update">
                </div>
            </form>
            <?php
            mysqli_free_result($result);
            mysqli_close($DBC);
            ?>
        </div>
    </div>
</body>
</html>
