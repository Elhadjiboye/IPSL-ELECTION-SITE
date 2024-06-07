<header class="main-header">
  <nav class="navbar navbar-static-top voter-navbar">
    <div class="container">
      <div class="navbar-header">
        <a href="#" class="navbar-brand"><b>Smart-Vote</b></a>
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <?php
            if(isset($_SESSION['student'])){
              echo "
                <li><a href='index.php'>Accueil</a></li>
                <li><a href='transaction.php'>Transaction</a></li>
              ";
            } 
          ?>
        </ul>
      </div>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="user user-menu">
            <a href="#">
              <img src="<?php echo (!empty($user_id['photo'])) ? 'images/'.$user_id['photo'] : 'images/profile.jpg' ?>" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php echo isset($user_id['prenom_electeur']) && isset($user_id['nom_electeur']) ? $user_id['prenom_electeur'].' '.$user_id['nom_electeur'] : ''; ?></span>
            </a>
          </li>
          <li><a href="logout.php"><i class="fa fa-sign-out"></i> Déconnexion</a></li>  
        </ul>
      </div>
    </div>
  </nav>
</header>
