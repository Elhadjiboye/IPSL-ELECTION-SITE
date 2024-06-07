<?php
	include 'includes/session.php';
	include 'includes/slugify.php';
	

	if(isset($_POST['vote'])){
		if(count($_POST) == 1){
			$_SESSION['error'][] = 'Veuillez voter pour au moins un candidat';
		}
		else{
			$voter = $_SESSION["voter"]; // Récupérer l'électeur de la session
			$_SESSION['post'] = $_POST;
			$sql = "SELECT * FROM election";
			$query = $conn->query($sql);
			$error = false;
			$sql_array = array();
			while($row = $query->fetch_assoc()){
				$position = slugify($row['type_election']);
				$pos_id = $row['id_election'];
				if(isset($_POST[$position])){
					if($row['max_vote'] > 1){
						if(count($_POST[$position]) > $row['max_vote']){
							$error = true;
							$_SESSION['error'][] = 'Vous ne pouvez choisir qu\'un seul.'.$row['max_vote'].' Candidat pour '.$row['type_election'];
						}
						else{
							foreach($_POST[$position] as $key => $values){
								$sql_array[] = "INSERT INTO vote (id_electeur, id_candidat, id_election) VALUES ('$voter', '$values', '$pos_id')";
							}

						}
						
					}
					else{
						$candidate = $_POST[$position];
						$sql_array[] = "INSERT INTO vote (id_electeur, id_candidat, id_election) VALUES ('$voter', '$candidate', '$pos_id')";
					}

				}
				
			}

			if(!$error){
				foreach($sql_array as $sql_row){
					$conn->query($sql_row);
				}

				unset($_SESSION['post']);
				$_SESSION['success'] = 'Bulletin soumis';

			}

		}

	}
	else{
		$_SESSION['error'][] = 'Sélectionnez d\'abord un candidat pour voter.';
	}

	header('location: home.php');
?>
