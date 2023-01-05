<?php
include 'db_connection.php';

$conn = OpenCon();

echo "Connected Successfully";

$sql = "SELECT * FROM Results";
$result = $conn->query($sql);

CloseCon($conn);

// if ($result->num_rows > 0) {
//     //output data of each row
//     while($row = $result->fetch_assoc()) {
//         echo "\nname = " . $row["system_name"] . "\n";
//     }
// } else {
//     echo "0 results";
// }


?>