<?php 
require 'functions.php';

$id = $_GET["id"];
$trs_serah_terima_id = $_GET['trs_serah_terima_id'];

if( hapus($id) > 0 ) {
	echo "
		<script>
			alert('data berhasil dihapus!');
			document.location.href = 'index.php?trs_serah_terima_id=$trs_serah_terima_id';
		</script>
	";
} else {
	echo "
		<script>
			alert('data gagal ditambahkan!');
			document.location.href = 'index.php?trs_serah_terima_id=$trs_serah_terima_id';
		</script>
	";
}

?>