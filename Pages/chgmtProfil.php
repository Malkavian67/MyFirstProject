<?php
/**
 * Created by IntelliJ IDEA.
 * User: Samanta
 * Date: 21/02/2017
 * Time: 00:15
 */
session_start ();
// $_SESSION['login']
//$_SESSION['status']

$raisonSociale=$_POST['raisSoc'];
$rue=$_POST['rue'];
$codePostal=$_POST['cp'];
$ville=$_POST['ville'];
$numHab=$_POST['numHab'];

echo $raisonSociale.$rue.$codePostal.$ville.$numHab ;

$bdd = new PDO('mysql:host=localhost;dbname=lacriee;charset=utf8','root','');


try
{
    if($bdd!=null) {
        if($_SESSION['status']=='acheteur'){

        }
        elseif ($_SESSION['status']=='crieur') {

        }
        else{
            header('Location: login.html?connection=0');
            exit();
        }

    }
}
catch(PDOException $e)
{
    die('Erreur: '.$e->getMessage());
}
?>