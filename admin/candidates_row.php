<?php 
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];
		$sql = "SELECT *, candidat.id_candidat AS canid FROM candidat LEFT JOIN election ON election.id_election=candidat.id_election WHERE candidat.id_candidat = '$id'";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		echo json_encode($row);
	}
?>