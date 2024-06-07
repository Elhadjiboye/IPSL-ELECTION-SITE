<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$type_election = $_POST['type_election'];
		$max_vote = $_POST['max_vote'];

		$sql = "SELECT * FROM election ORDER BY priority DESC LIMIT 1";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		$priority = $row['priority'] + 1;
		
		$sql = "INSERT INTO election (type_election, max_vote, priority) VALUES ('$type_election', '$max_vote', '$priority')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Election ajoutée avec succès';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Veuillez remplir d\'abord le formulaire d\'ajout.';
	}

	header('location: positions.php');
?>