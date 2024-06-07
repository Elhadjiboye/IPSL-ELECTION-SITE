<?php
	include 'includes/conn.php';
	session_start();

	if(isset($_SESSION['voter'])){
		$sql = "SELECT * FROM electeur WHERE id_electeur = '".$_SESSION['voter']."'";
		$query = $conn->query($sql);
		$user_id = $query->fetch_assoc();
	}
	else{
		header('location: index.html');
		exit();
	}
?>