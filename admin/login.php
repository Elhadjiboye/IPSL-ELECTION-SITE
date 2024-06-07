<?php
	session_start();
	include 'includes/conn.php';

	if(isset($_POST['login'])){
		$mail = $_POST['mail'];
		$password = $_POST['password'];

		$sql = "SELECT * FROM admin WHERE mail_admin = '$mail'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			$_SESSION['error'] = 'Adresse e-mail ou mot de passe incorrect. Veuillez réessayer !';
		}
		else{
			$row = $query->fetch_assoc();
			if($password == $row['mot_de_passe_admin']){
				$_SESSION['admin'] = $row['id_admin'];
			}
			else{
				$_SESSION['error'] = 'Adresse e-mail ou mot de passe incorrect. Veuillez réessayer !';
			}
		}
	}
	else{
		$_SESSION['error'] = 'Veuillez fournir votre adresse e-mail et votre mot de passe pour continuer !';
	}

	header('location: index.php');
?>
