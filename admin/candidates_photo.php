<?php
	include 'includes/session.php';

	if(isset($_POST['upload'])){
		$id = $_POST['id'];
		$photo= $_FILES['photo']['name'];
		if(!empty($photo)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);	
		}
		
		$sql = "UPDATE candidat SET photo = '$photo' WHERE id_candidat = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = '
			La photo a été mise à jour avec succès.';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Sélectionnez d\'abord le candidat pour mettre à jour la photo.';
	}

	header('location: candidates.php');
?>