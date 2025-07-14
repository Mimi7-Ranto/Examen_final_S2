<?php
session_start();
include("../inc/function.php");

if (isset($_POST['nom']) && isset($_POST['date_naissance']) && isset($_POST['genre']) && isset($_POST['email']) && isset($_POST['mdp'])) {
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

if(isset($_POST['email']) && isset($_POST['mdp'])){
    echo 'mety';
    $mdp = $_POST['mdp'];
    $email = $_POST['email'];
    if(loginUser($email,$mdp)){
        $_SESSION['email'] = $email;
        header('Location:liste.php');
    }else{
        header('Location:login.php?error=1');
    }
}
?>
