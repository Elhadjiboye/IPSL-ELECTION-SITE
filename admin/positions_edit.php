<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$type_election = $_POST['type_election'];
		$max_vote = $_POST['max_vote'];

		$sql = "UPDATE election SET type_election = '$type_election', max_vote = '$max_vote' WHERE id_election = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'L\'élection a été mise à jour avec succès.';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Veuillez remplir le formulaire de modification d\'abord.';
	}

	header('location: positions.php');

?>