<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$prenom= $_POST['prenom_electeur'];
		$nom = $_POST['nom_electeur'];
		$password = $_POST['mot_de_passe_electeur'];
		$photo = $_FILES['photo']['name'];
		if(!empty($photo)){
			move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);	
		}
		//generate voters id
		$voter = substr(md5(uniqid(mt_rand(), true)), 0, 15);

		$sql = "INSERT INTO electeur (id_electeur, nom_electeur, prenom_electeur, mot_de_passe_electeur, photo) VALUES ('$voter', '$nom', '$prenom', '$password', '$photo')";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Électeur ajouté avec succès.';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}

	}
	else{
		$_SESSION['error'] = 'Veuillez remplir d\'abord le formulaire d\'ajout.';
	}

	header('location: voters.php');
?>