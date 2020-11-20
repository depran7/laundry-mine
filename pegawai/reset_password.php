<?php 
require 'functions.php';

$id = $_GET["id"];

if( reset_password($id) > 0 ) {
	echo "
		<script>
			alert('password berhasil direset!');
			document.location.href = 'index.php';
		</script>
	";
} else {
	echo "
		<script>
			alert('password gagal direset!');
			document.location.href = 'index.php';
		</script>
	";
}

?>