<?php
	include 'includes/session.php';

	if(isset($_POST['id'])){
		$id = $_POST['id'];

		$sql = "SELECT * FROM election";
		$pquery = $conn->query($sql);

		$output = array('error'=>false);

		$sql = "SELECT * FROM election WHERE id_election='$id'";	
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();

		$priority = $row['priority'] + 1;

		if($priority > $pquery->num_rows){
			$output['error'] = true;
			$output['message'] = 'Cette election est déjà en bas.';
		}
		else{
			$sql = "UPDATE election SET priority = priority - 1 WHERE priority = '$priority'";
			$conn->query($sql);

			$sql = "UPDATE election SET priority = '$priority' WHERE id_election = '$id'";
			$conn->query($sql);
		}

		echo json_encode($output);

	}
	
?>