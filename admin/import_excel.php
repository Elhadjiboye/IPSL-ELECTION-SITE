<?php
require 'C:/xampp/htdocs/Election1/vendor/autoload.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/conn.php';

// Vérifie si le formulaire a été soumis
if(isset($_POST['import'])){
    // Assurez-vous que le fichier a été envoyé
    if(isset($_FILES['excel_file'])){
        $excelFile = $_FILES['excel_file']['tmp_name'];
        $data = [];
        if($excelFile){
            // Lecture du fichier Excel avec la bibliothèque PHP native
            $excelData = [];
            $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $spreadsheet = $objPHPExcel->load($excelFile);
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            // Récupération des données de chaque ligne
            foreach ($sheetData as $row) {
                $rowData = [];
                foreach ($row as $cell) {
                    $rowData[] = $cell;
                }
                $data[] = $rowData;
            }
        }

        // Insertion des données dans la base de données
        foreach ($data as $row) {
            // Génération de l'ID électeur
            $voter = substr(md5(uniqid(mt_rand(), true)), 0, 15);

            // Mot de passe par défaut
            $password = 'password';

            // Photo par défaut (à adapter en fonction de votre application)
            $photo = 'default_photo.jpg';

            // Insertion des données dans la base de données
            $sql = "INSERT INTO electeur (id_electeur, nom_electeur, prenom_electeur, mail_electeur, mot_de_passe_electeur, photo) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssss', $voter, $row[1], $row[0], $row[2], $password, $photo);
            if($stmt->execute()){
                $_SESSION['success'] = 'Électeur ajouté avec succès.';
            }
            else{
                $_SESSION['error'] = $conn->error;
            }
        }

        // Redirection vers la page des électeurs
        header('location: voters.php');
    }
}
?>
