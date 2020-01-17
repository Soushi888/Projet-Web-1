<?php
  
require_once("paramConnectDB.php");

// error_reporting(0); // pour gérer les messages d'erreurs dans le code

$conn = mysqli_connect(HOST, USER, PASSWORD, DBNAME);

if (!$conn) {
?>
   <p>Erreur de connexion :
      <?php echo mysqli_connect_errno()." – ".utf8_encode(mysqli_connect_error()) ?>
   </p> 
<?php 
   exit;
}

mysqli_set_charset($conn, "utf8");