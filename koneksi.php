<?php
$url = "localhost";
$username  = "root";
$password = "";
$db_name = "laundry-mine";
$conn = mysqli_connect($url, $username, $password, $db_name);

// ROLES
$roles = [
  [
    'id' => 1,
    'name' => 'Pengambil'
  ],
  [
    'id' => 2,
    'name' => 'Pencuci'
  ],
  [
    'id' => 3,
    'name' => 'Penyetrika'
  ],
  [
    'id' => 4,
    'name' => 'Pendistribusi'
  ],
  [
    'id' => 5,
    'name' => 'Admin'
  ],
  [
    'id' => 6,
    'name' => 'Penyerahan'
  ]
];

function query($query)
{

	global $conn;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$rows[] = $row;
	}
	return $rows;
}

function is_role($role_name)
{
	$role_id = 0;
	switch ($role_name) {
		case 'pengambil':
			$role_id = 1;
			break;
		case 'pencuci':
			$role_id = 2;
			break;
		case 'penyetrika':
			$role_id = 3;
			break;
		case 'pendistribusi':
			$role_id = 4;
			break;
		case 'admin':
			$role_id = 5;
			break;
		case 'penyerahan':
			$role_id = 6;
			break;

		default:
			$role_id = 0;
			break;
	}
	if (session_id() == '') {
		session_start();
	}
	return $role_id == $_SESSION['user']['role'];
}

function is_roles($roles)
{
	foreach ($roles as $role) {
		if (is_role($role)) {
			return true;
		}
	}
	return false;
}
