<?php
session_start();
include("../inc/function.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST['nom'];
    $date_naissance = $_POST['date_naissance'];
    $genre = $_POST['genre'];
    $email = $_POST['email'];
    $mdp = $_POST['mdp'];

   
   $result= registerUser($nom, $date_naissance, $genre, $email ,$mdp);

    if ($result) {
        header('Location: login.php');
        exit;
    }
    
   
}
?>
