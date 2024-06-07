<?php
	include 'includes/session.php';

	if(isset($_GET['return'])){
		$return = $_GET['return'];
	}
	else{
		$return = 'home.php';
	}

	if(isset($_POST['save'])){
		$curr_password = $_POST['curr_password'];
		$prenom = $_POST['Prenom'];
		$nom = $_POST['nom'];
		$password = $_POST['mot_de_passe'];
		$mail = $_POST['mail'];
		$photo = $_FILES['photo']['name'];

		if (!empty($curr_password)) {
			if ($curr_password == $user['mot_de_passe_admin']) {
				if (!empty($photo)) {
					move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$photo);
					$filename = $photo;
				} else {
					$filename = $user['photo'];
				}

				if ($password != $user['mot_de_passe_admin']) {
					// Mettre à jour le mot de passe dans le tableau $user
					$user['mot_de_passe_admin'] = $password;
				}

				$sql = "UPDATE admin SET prenom_admin = '$prenom', nom_admin = '$nom',  mot_de_passe_admin = '$password', mail_admin = '$mail', photo = '$filename' WHERE id_admin = '".$user['id_admin']."'";

				if ($conn->query($sql)) {
					$_SESSION['success'] = 'Profil administrateur mis à jour avec succès.';
				} else {
					$_SESSION['error'] = $conn->error;
				}
			} else {
				$_SESSION['error'] = 'Mot de passe incorrect';
			}
		} else {
			$_SESSION['error'] = 'Veuillez remplir les détails requis en premier.';
		}
	}

	header('location:'.$return);
?>
