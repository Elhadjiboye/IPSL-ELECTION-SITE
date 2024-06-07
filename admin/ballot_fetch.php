<?php
	include 'includes/session.php';
	include 'includes/slugify.php';

	$sql = "SELECT * FROM election";
	$pquery = $conn->query($sql);

	$output = '';
	$candidate = '';

	$sql = "SELECT * FROM election ORDER BY priority ASC";
	$query = $conn->query($sql);
	$num = 1;
	while($row = $query->fetch_assoc()){
		$input = ($row['max_vote'] > 1) ? '<input type="checkbox" class="flat-red '.slugify($row['type_election']).'" name="'.slugify($row['type_election'])."[]".'">' : '<input type="radio" class="flat-red '.slugify($row['type_election']).'" name="'.slugify($row['type_election']).'">';

		$sql = "SELECT * FROM candidat WHERE id_election='".$row['id_election']."'";
		$cquery = $conn->query($sql);
		while($crow = $cquery->fetch_assoc()){
			$image = (!empty($crow['photo'])) ? '../images/'.$crow['photo'] : '../images/profile.jpg';
			$candidate .= '
				<li>
					'.$input.'<img src="'.$image.'" height="100px" width="100px" class="clist"><span class="cname clist">'.$crow['prenom_candidat'].' '.$crow['nom_candidat'].'</span>
				</li>
			';
		}

		$instruct = ($row['max_vote'] > 1) ? 'Vous pouvez sélectionner jusqu\'à '.$row['max_vote'].' candidat' : 'Sélectionnez uniquement un candidat';
		
		$updisable = ($row['priority'] == 1) ? 'disabled' : '';
		$downdisable = ($row['priority'] == $pquery->num_rows) ? 'disabled' : '';

		$output .= '
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-solid" id="'.$row['id_election'].'">
						<div class="box-header with-border">
							<h3 class="box-title"><b>'.$row['type_election'].'</b></h3>
							<div class="pull-right box-tools">
				                <button type="button" class="btn btn-default btn-sm moveup" data-id="'.$row['id_election'].'" '.$updisable.'><i class="fa fa-arrow-up"></i> </button>
				                <button type="button" class="btn btn-default btn-sm movedown" data-id="'.$row['id_election'].'" '.$downdisable.'><i class="fa fa-arrow-down"></i></button>
				            </div>
						</div>
						<div class="box-body">
							<p>'.$instruct.'
								<span class="pull-right">
									<button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="'.slugify($row['type_election']).'"><i class="fa fa-refresh"></i> 
									Réinitialiser</button>
								</span>
							</p>
							<div id="candidate_list">
								<ul>
									'.$candidate.'
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		';

		$sql = "UPDATE election SET priority = '$num' WHERE id_election = '".$row['id_election']."'";
		$conn->query($sql);

		$num++;
		$candidate = '';
	}

	echo json_encode($output);
?>