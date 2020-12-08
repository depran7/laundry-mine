<?php 
require 'functions.php';

$id = $_GET["id"];

if( serah_ke_laundry($id) > 0 ) {
	echo "
		<script>
			alert('data berhasil diserahkan!');
			document.location.href = 'index.php';
		</script>
	";
} else {
	echo "
		<script>
			alert('data gagal diserahkan!');
			document.location.href = 'index.php';
		</script>
	";
}

?>