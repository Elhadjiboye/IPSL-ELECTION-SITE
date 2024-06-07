<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$prenom = $_POST['prenom_electeur'];
		$nom = $_POST['nom_electeur'];
		$mail = $_POST['mail_electeur'];
		$password = $_POST['mot_de_passe_electeur'];

		$sql = "SELECT * FROM electeur WHERE id_electeur = $id";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		if($password == $row['mot_de_passe_electeur']){
			$password = $row['mot_de_passe_electeur'];
		}
		else{
			$password = $_POST['mot_de_passe_electeur'];
		}

		$sql = "UPDATE electeur SET prenom_electeur = '$prenom', nom_electeur= '$nom', mail_electeur= '$mail', mot_de_passe_electeur = '$password' WHERE id_electeur = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Electeur mis à jour avec succès';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
	}
	else{
		$_SESSION['error'] = 'Veuillez remplir d\'abord le formulaire de modification.';
	}

	header('location: voters.php');

?>