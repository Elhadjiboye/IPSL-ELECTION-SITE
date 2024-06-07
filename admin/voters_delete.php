<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$sql = "DELETE FROM electeur WHERE id_electeur = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Electeur supprimé avec succès.';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Sélectionnez d\'abord l\'élément à supprimer.';
	}

	header('location: voters.php');
	
?>