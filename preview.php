<?php
	
	include 'includes/session.php';
	include 'includes/slugify.php';

	$output = array('error'=>false,'list'=>'');

	$sql = "SELECT * FROM election";
	$query = $conn->query($sql);

	while($row = $query->fetch_assoc()){
		$position = slugify($row['type_election']);
		$pos_id = $row['id_election'];
		if(isset($_POST[$position])){
			if($row['max_vote'] > 1){
				if(count($_POST[$position]) > $row['max_vote']){
					$output['error'] = true;
					$output['message'][] = '<li>Vous ne pouvez choisir que '.$row['max_vote'].' candidat pour '.$row['type_election'].'</li>';
				}
				else{
					foreach($_POST[$position] as $key => $values){
						$sql = "SELECT * FROM candidat WHERE id_candidat = '$values'";
						$cmquery = $conn->query($sql);
						$cmrow = $cmquery->fetch_assoc();
						$output['list'] .= "
							<div class='row votelist'>
		                      	<span class='col-sm-4'><span class='pull-right'><b>".$row['type_election']." :</b></span></span> 
		                      	<span class='col-sm-8'>".$cmrow['prenom_candidat']." ".$cmrow['nom_candidat']."</span>
		                    </div>
						";
					}

				}
				
			}
			else{
				$candidate = $_POST[$position];
				$sql = "SELECT * FROM candidat WHERE id_candidat = '$candidate'";
				$csquery = $conn->query($sql);
				$csrow = $csquery->fetch_assoc();
				$output['list'] .= "
					<div class='row votelist'>
                      	<span class='col-sm-4'><span class='pull-right'><b>".$row['type_election']." :</b></span></span> 
                      	<span class='col-sm-8'>".$csrow['prenom_candidat']." ".$csrow['nom_candidat']."</span>
                    </div>
				";
			}

		}
		
	}

	echo json_encode($output);


?>