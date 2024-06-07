<?php
	include 'includes/session.php';

	$sql = "DELETE FROM vote";
	if($conn->query($sql)){
		$_SESSION['success'] = "Réinitialisation des votes effectuée avec succès.";
	}
	else{
		$_SESSION['error'] = "Quelque chose s'est mal passé lors de la réinitialisation.";
	}

	header('location: votes.php');

?>