<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'import_excel.php'; ?>
<?php include 'send_emails.php'; ?>
<?php include 'includes/send_mails_modal.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">

  <div class="wrapper">

    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
        Gérer les électeurs
              </h1>
      </section>
      <!-- Main content -->
      <section class="content">
      <?php
    if (isset($_GET['success'])) {
        echo '
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> Succès !</h4>
                '.$_GET['success'].'
                
            </div>
        ';
        unset($_SESSION['success']);
    }
?>

        <?php

          if(isset($_SESSION['error'])){
            echo "
              <div class='alert alert-danger alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-warning'></i> Erreur !</h4>
                ".$_SESSION['error']."
              </div>
            ";
            unset($_SESSION['error']);
          }
          if(isset($_SESSION['success'])){
            echo "
              <div class='alert alert-success alert-dismissible'>
                <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
                <h4><i class='icon fa fa-check'></i> Réussite !</h4>
                ".$_SESSION['success']."
              </div>
            ";
            unset($_SESSION['success']);
          }
        ?>
        <div class="row">
    <div class="col-xs-12">
        <div class="box">
        <div class="box-header with-border" style="display: flex; justify-content: space-between;">
    <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat" style="margin-right: 10px;"><i class="fa fa-plus"></i> Nouveau</a>
    
    <form method="post" action="send_emails.php" enctype="multipart/form-data">
        <button type="submit" class="btn btn-primary btn-sm btn-flat" style="margin-left: 800px;"><i class="fa fa-envelope"></i> Envoyer e-mails</button>
    </form>
</div>

            
            <div class="box-body">
            
<form id="importForm" method="post" enctype="multipart/form-data" class="form-inline justify-content-end"action="import_excel.php" >
    <div class="form-group mb-2">
        <input type="file" name="excel_file" id="excelFile" class="form-control">
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <button type="submit" id="importButton" name="import" class="btn btn-success btn-sm btn-flat">Importer Excel</button>
    </div>
</form>
<script>
    document.getElementById("importButton").addEventListener("click", function() {
        var fileInput = document.getElementById("excelFile");
        if (!fileInput.files || fileInput.files.length === 0) {
            alert("Veuillez sélectionner un fichier Excel.");
            return;
        }
        document.getElementById("importForm").submit();
    });
</script>

                <br>
                <table id="example1" class="table table-bordered">
                    <thead>
                        <th>ID Electeur</th>
                        <th>Prenom Electeur</th>
                        <th>Nom Electeur</th>
                        <th>Profile</th>
                        <th>Actions</th>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM electeur";
                        $query = $conn->query($sql);
                        while($row = $query->fetch_assoc()){
                            $image = (!empty($row['photo'])) ? '../images/'.$row['photo'] : '../images/profile.jpg';
                            echo "
                                <tr>
                                    <td>".$row['id_electeur']."</td>
                                    <td>".$row['prenom_electeur']."</td>
                                    <td>".$row['nom_electeur']."</td>
                                    <td>
                                        <img src='".$image."' width='30px' height='30px'>
                                        <a href='#edit_photo' data-toggle='modal' class='pull-right photo' data-id='".$row['id_electeur']."'><span class='fa fa-edit'></span></a>
                                    </td>
                                    <td>
                                        <button class='btn btn-success btn-sm Modifier btn-flat' data-id='".$row['id_electeur']."'><i class='fa fa-edit'></i> Modifier</button>
                                        <button class='btn btn-danger btn-sm Supprimer btn-flat' data-id='".$row['id_electeur']."'><i class='fa fa-trash'></i> Supprimer</button>
                                    </td>
                                </tr>
                            ";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

    <?php include 'includes/voters_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>

  <script>
  $(function(){
  $(document).on('click', '.Modifier', function(e){
    e.preventDefault();
    $('#edit').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.Supprimer', function(e){
    e.preventDefault();
    $('#delete').modal('show');
    var id = $(this).data('id');
    getRow(id);
  });

  $(document).on('click', '.photo', function(e){
    e.preventDefault();
    var id = $(this).data('id');
    getRow(id);
  });

});


  function getRow(id){
    $.ajax({
      type: 'POST',
      url: 'voters_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        $('.id').val(response.id_electeur);
        $('#edit_firstname').val(response.prenom_electeur);
        $('#edit_lastname').val(response.nom_electeur);
        $('#edit_mail').val(response.mail_electeur);
        $('#edit_mot_de_passe_electeur').val(response.mot_de_passe_electeur);
        $('.fullname').html(response.prenom_electeur+' '+response.nom_electeur);
      }
    });
  }
  </script>
  <script>

</body>
</html>
