<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		$sql = "DELETE FROM candidat WHERE id_candidat = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Candidat supprimé avec succès.';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Sélectionnez d\'abord l\'élément à supprimer.';
	}

	header('location: candidates.php');
	
?>