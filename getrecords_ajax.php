<?php 	
    include('secure/DBconn.php');

// script adapted from: https://programmerblog.net/jquery-ajax-get-example-php-mysql/
$result_array = array();

/* SQL query to get results from database */
$sql = "SELECT DISTINCT WMADistance FROM tblwma";
$result = mysqli_query($conn,$sql);

/* If there are results from database push to result array */
if (mysqli_num_rows($result) > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($result_array, $row);
    }
}

//echo '<pre>'; print_r($result_array); echo '</pre>';
/* send a JSON encded array to client */
// in original script, but the below 'header..' line prevents any results from being returned by calling script (so commented out)
// header('Content-type: application/json');
 echo json_encode($result_array);
$conn->close();
?>