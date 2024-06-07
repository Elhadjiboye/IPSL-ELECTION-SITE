


<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">
	      <section class="content">
	      	<?php
	      		$parse = parse_ini_file('admin/config.ini', FALSE, INI_SCANNER_RAW);
    			$title = $parse['election_title'];
	      	?>
	      	<h1 class="page-header text-center title"><b><?php echo strtoupper($title); ?></b></h1>
	        <div class="row">
	        	<div class="col-sm-10 col-sm-offset-1">
	        		<?php
				        if(isset($_SESSION['error'])){
				        	?>
				        	<div class="alert alert-danger alert-dismissible">
				        		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					        	<ul>
					        		<?php
					        			foreach($_SESSION['error'] as $error){
					        				echo "
					        					<li>".$error."</li>
					        				";
					        			}
					        		?>
					        	</ul>
					        </div>
				        	<?php
				         	unset($_SESSION['error']);

				        }
				        if(isset($_SESSION['success'])){
				          	echo "
				            	<div class='alert alert-success alert-dismissible'>
				              		<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
				              		<h4><i class='icon fa fa-check'></i> Succès !</h4>
				              	".$_SESSION['success']."
				            	</div>
				          	";
				          	unset($_SESSION['success']);
				        }

				    ?>
 
				    <div class="alert alert-danger alert-dismissible" id="alert" style="display:none;">
		        		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			        	<span class="message"></span>
			        </div>
				  
				   <?php
				    $voter= $_SESSION["voter"];
				    	$sql = "SELECT * FROM vote WHERE id_electeur = '".$voter."'";
				    	$vquery = $conn->query($sql);
				    	if($vquery->num_rows > 0){
				    		?>
				    		<div class="text-center">
					    		<h3>Vous avez déjà voté pour cette élection</h3>
					    		<a href="#view" data-toggle="modal" class="btn btn-flat btn-primary btn-lg">Voir le bulletin de vote</a>
					    	</div>
				    		<?php
				    	}

				 
				    	else{
				    		?>
						    <form method="POST" id="ballotForm" action="submit_ballot.php">
						    <?php
include 'includes/slugify.php';

$candidate = '';
$sql = "SELECT * FROM election ORDER BY priority ASC";
$query = $conn->query($sql);
while($row = $query->fetch_assoc()){
    $sql_candidat = "SELECT * FROM candidat WHERE id_election='".$row['id_election']."'";
    $cquery = $conn->query($sql_candidat);
    while($crow = $cquery->fetch_assoc()){
        $slug = slugify($row['type_election']);
        $checked = '';
        if(isset($_SESSION['post'][$slug])){
            $value = $_SESSION['post'][$slug];

            if(is_array($value)){
                foreach($value as $val){
                    if($val == $crow['id_candidat']){
                        $checked = 'checked';
                    }
                }
            }
            else{
                if($value == $crow['id_candidat']){
                    $checked = 'checked';
                }
            }
        }
        $input = ($row['max_vote'] > 1) ? '<input type="checkbox" class="flat-red '.$slug.'" name="'.$slug."[]".'" value="'.$crow['id_candidat'].'" '.$checked.'>' : '<input type="radio" class="flat-red '.$slug.'" name="'.slugify($row['type_election']).'" value="'.$crow['id_candidat'].'" '.$checked.'>';
        $image = (!empty($crow['photo'])) ? 'images/'.$crow['photo'] : 'images/profile.jpg';
        $candidate .= '
												<li>
													'.$input.'<button type="button" class="btn btn-primary btn-sm btn-curve clist platform" style="background-color: #4682B4 ;color:black ; font-size: 12px; font-family:Times" data-platform="'.$crow['programme_detude'].'" data-fullname="'.$crow['prenom_candidat'].' '.$crow['nom_candidat'].'"><i class="fa fa-search"></i> Programme</button><img src="'.$image.'" height="100px" width="100px" class="clist"><span class="cname clist">'.$crow['prenom_candidat'].' '.$crow['nom_candidat'].'</span>
												</li>
											';
    }

    $instruct = ($row['max_vote'] > 1) ? 'Vous pouvez sélectionner jusqu\'à'.$row['max_vote'].' candidat' : 'Sélectionnez uniquement un candidat';

    echo '
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-solid" id="'.$row['id_election'].'">
                    <div class="box-header with-border">
                        <h3 class="box-title"><b>'.$row['type_election'].'</b></h3>
                    </div>
                    <div class="box-body">
                        <p>'.$instruct.'
                            <span class="pull-right">
                                <button type="button" class="btn btn-success btn-sm btn-flat reset" data-desc="'.slugify($row['type_election']).'"><i class="fa fa-refresh"></i> Réinitialiser</button>
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

    $candidate = '';

}	

?>

				        		<div class="text-center">
					        		<button type="button" class="btn btn-success btn-flat" id="preview"><i class="fa fa-file-text"></i> Aperçu</button> 
					        		<button type="submit" class="btn btn-primary btn-flat" name="vote"><i class="fa fa-check-square-o"></i>Soumettre</button>
					        	</div>
				        	</form>
				        	<!-- End Voting Ballot -->
				    		<?php
				    	}

				    ?>

	        	</div>
	        </div>
	      </section>
	     
	    </div>
	  </div>

  	<?php include 'includes/ballot_modal.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
<script>
$(function(){
	$('.content').iCheck({
		checkboxClass: 'icheckbox_flat-green',
		radioClass: 'iradio_flat-green'
	});

	$(document).on('click', '.reset', function(e){
	    e.preventDefault();
	    var desc = $(this).data('desc');
	    $('.'+desc).iCheck('uncheck');
	});

	$(document).on('click', '.platform', function(e){
		e.preventDefault();
		$('#platform').modal('show');
		var platform = $(this).data('platform');
		var fullname = $(this).data('fullname');
		$('.candidate').html(fullname);
		$('#plat_view').html(platform);
	});

	$('#preview').click(function(e){
		e.preventDefault();
		var form = $('#ballotForm').serialize();
		if(form == ''){
			$('.message').html('Vous devez voter pour au moins un candidat');
			$('#alert').show();
		}
		else{
			$.ajax({
				type: 'POST',
				url: 'preview.php',
				data: form,
				dataType: 'json',
				success: function(response){
					if(response.error){
						var errmsg = '';
						var messages = response.message;
						for (i in messages) {
							errmsg += messages[i]; 
						}
						$('.message').html(errmsg);
						$('#alert').show();
					}
					else{
						$('#preview_modal').modal('show');
						$('#preview_body').html(response.list);
					}
				}
			});
		}
		
	});

});
</script>
</body>
</html>