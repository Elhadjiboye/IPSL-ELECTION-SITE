<?php
	include 'includes/session.php';

	if(isset($_POST['upload'])){
		$id = $_POST['id'];
		$photo = $_FILES['photo']['name'];
		if(!empty($photo)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);	
		}
		
		$sql = "UPDATE electeur SET photo = '$photo' WHERE id_electeur = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Photo mise à jour avec succès.';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Sélectionnez d\'abord l\'électeur pour mettre à jour la photo';
	}

	header('location: voters.php');
?>