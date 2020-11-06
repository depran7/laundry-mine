<?php
$url = "localhost";
$username  = "root";
$password = "";
$db_name = "laundry-mine";
$conn = mysqli_connect($url, $username, $password, $db_name);

function query($query) {
	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
  return $rows;
}