  <?php include 'includes/session.php'; ?>
  <?php include 'includes/header.php'; ?>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <?php include 'includes/navbar.php'; ?>
      <?php include 'includes/menubar.php'; ?>

      <div class="content-wrapper">
        <section class="content-header">
          <h1>
            Gérer les Elections 
          </h1>
        </section>
        <section class="content">
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
                  <h4><i class='icon fa fa-check'></i>  Réussite !</h4>
                  ".$_SESSION['success']."
                </div>
              ";
              unset($_SESSION['success']);
            }
          ?>
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header with-border">
                  <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Nouveau</a>
                </div>
                <div class="box-body">
                  <table id="example1" class="table table-bordered">
                    <thead>
                      <th class="hidden"></th>
                      <th>Type Election</th>
                      <th>Maximum Vote</th>
                      <th>Actions</th>
                    </thead>
                    <tbody>
                      <?php
                        $sql = "SELECT * FROM election ORDER BY priority ASC";
                        $query = $conn->query($sql);
                        while($row = $query->fetch_assoc()){
                          echo "
                            <tr>
                              <td class='hidden'></td>
                              <td>".$row['type_election']."</td>
                              <td>".$row['max_vote']."</td>
                              <td>
                                <button class='btn btn-success btn-sm Modifier btn-flat' data-id='".$row['id_election']."'><i class='fa fa-edit'></i> Modifier</button>
                                <button class='btn btn-danger btn-sm Supprimer btn-flat' data-id='".$row['id_election']."'><i class='fa fa-trash'></i>Supprimer</button>
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
        </section>   
      </div>
      <?php include 'includes/positions_modal.php'; ?>
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

});

function getRow(id){
  $.ajax({
    type: 'POST',
    url: 'positions_row.php',
    data: {id:id},
    dataType: 'json',
    success: function(response){
      $('.id').val(response.id_election);
      $('#edit_description').val(response.type_election);
      $('#edit_max_vote').val(response.max_vote);
      $('.description').html(response.type_election);
    }
  });
}
</script>
  </body>
  </html>
