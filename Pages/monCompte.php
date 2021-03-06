<html>
<head>
    <meta charset="utf-8"/>
    <link rel="stylesheet" href="style.css"/>
    <!--[if lt IE 9]>
    <script src="http://github.com/aFarkas/html5shiv/blob/master/dist/html5shiv.js"></script>
    <![endif]-->
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=10">
    <![endif]-->
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <![endif]-->
    <title>Mon compte</title>
    <link rel="stylesheet" href="../Libs/jquery-ui.css">
    <link rel="stylesheet" href="../Libs/bootstrap.min.css">
    <script src="../Libs/jquery-3.1.1.js"></script>
    <script src="../Libs/jquery-ui.js"></script>
    <script>
        $(function () {
            $(document).tooltip();
        });

        $(function () {
            $("#tabs").tabs();
        });

        function showForm() {

            var showMe = document.getElementById("hiddenForm");
            showMe.style.visibility = "visible";
            var hideMe = document.getElementById("btnModif");
            hideMe.style.visibility = "hidden";

        }
        function hideForm() {

            var showMe = document.getElementById("btnModif");
            showMe.style.visibility = "visible";
            var hideMe = document.getElementById("hiddenForm");
            hideMe.style.visibility = "hidden";

        }

    </script>
</head>
<body class="img-criee">

<a class="button" href="logout.php">Déconnection</a>

<h1>Mon compte</h1>


<?php
session_start();

$_SESSION['previous_location']='monCompte';

$bdd = new PDO('mysql:host=localhost;dbname=lacriee;charset=utf8', 'root', '');

$aPartirDe=date("Y-m-d");


try {
    if ($bdd != null) {

        if ($_SESSION['status'] == 'acheteur') {

            $requete = "Select login, raisonSocEnt, adresse, ville, cp, numHabillitation from acheteur where login='" . $_SESSION['login'] . "'";

            $resultat = $bdd->prepare($requete);

            $resultat->execute();

            while ($donnees = $resultat->fetch()) {
                $acheteurLogin = $donnees['login'];
                $acheteurRS = $donnees['raisonSocEnt'];
                $acheteurAdr = $donnees['adresse'];
                $acheteurVille = $donnees['ville'];
                $acheteurCp = $donnees['cp'];
                $acheteurNumHab = $donnees['numHabillitation'];
            }

            echo '<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Profil</a></li>
        <li><a href="#tabs-2">Achat(s) effectué(s)</a></li>
        <li><a href="#tabs-3">Enchère(s) du jour</a></li>
    </ul>
    <div id="tabs-1">
     <p class="col-xs-5 .col-sm-6 .col-lg-4">
     </br>
     </br>
      <table classe="table-profil">
     <tr class="darkGrey">
        <td ><b>Login: </b></td>	<td>' . $acheteurLogin . '</td></tr>
        <tr class="lightgrey"><td> <b>Raison sociale: </b></td><td> ' . $acheteurRS . ' </td></tr>
        <tr class="darkGrey"> <td><b>Adresse: </b></td><td>' . $acheteurAdr . '</td></tr>
        <tr class="darkGrey"><td> </td><td> ' . $acheteurCp . '</td></tr>
       <tr class="darkGrey"><td> </td><td>  ' . $acheteurVille . '</td></tr> 
       <tr class="lightgrey"><td> <b>Numéro d\'habilitation: </b> </td><td> ' . $acheteurNumHab . ' </td></tr>
        </table>
 
     </p>
     <p class="col-xs-7 .col-sm-6 .col-lg-8">
     <div class="button" id="btnModif" onClick="showForm()" >Modifier</div>
     <div class="col-xs-7 .col-sm-6 .col-lg-8" id="hiddenForm">
     
     <div onClick="hideForm()" class="button">Annuler</div>
     <form method="post" action="chgmtProfil.php">
     <table>
     <tr class="darkGrey">
        <td ><b>Login: </b></td>	<td> <span class="warning-msg"> &#9888; Seul l\' administrateur peut changer le login &#9888;</span></td></tr>
       <tr class="lightgrey"><td> <b>Raison sociale: </b></td><td> <input class="input-width" type="text" name="raisSoc"  /> </td></tr>
       <tr class="darkGrey"> <td><b>Rue: </b></td><td> <input class="input-width" type="text" name="rue"  /></td></tr>
        <tr class="darkGrey"><td><b>Code postal: </b></td><td>  <input class="input-width" type="text" name="cp"  /></td></tr>
       <tr class="darkGrey"><td> <b>Ville: </b> </td><td> <input class="input-width" type="text" name="ville"  /></td></tr> 
       <tr class="lightgrey"><td> <b>Numéro d\'habilitation: </b> </td><td> <span class="warning-msg">&#9888; Seul l\' administrateur peut changer le numéro d\'habilitation &#9888;</span></td></tr>
        </table>
        <input type="submit" value="Valider" />  
         </form>
         </div>
     </p>
 
    </div>
    <div id="tabs-2">';

            $requete = "Select idAcheteur from acheteur where login='" . $_SESSION['login'] . "'";
            $resultat = $bdd->prepare($requete);

            $resultat->execute();

            while ($data = $resultat->fetch()) {
                $idAcheteur = $data['idAcheteur'];
            }

            $requete2 = "Select l.datePeche,specification,libelleQual,tare, poidsBrutLot, dateEnchere, libellePres, nomComm, l.prixEnchere from lot l,espece,bac,qualite,taille, presentation where l.idEsp=espece.idEsp and l.idTaille=taille.idTaille and l.idPres=presentation.idpres and l.idQual=qualite.idQual and l.idBac=bac.idBac and l.idAcheteur=" . $idAcheteur . " order by dateEnchere desc;";
            $resultat2 = $bdd->prepare($requete2);

            $resultat2->execute();

            $tab = array();

            $resultat2->setFetchMode(PDO::FETCH_ASSOC);

            echo '<table class="table-list-enchere">
            <tr class="table-header"><td>Date de l\'enchère</td><td>Espèce</td><td>Poids Brut</td><td>Specification</td><td>Prix</td><td>Presentation</td><td>Qualité</td><td>Date de pêche</td></tr>';

            foreach ($resultat2 as $donnees) {
                echo '<tr><td>' . $donnees['dateEnchere'] . '</td><td>' . $donnees['nomComm'] . '</td><td>' . $donnees['poidsBrutLot'] . '</td><td>' . $donnees['specification'] . '</td><td>' . $donnees['prixEnchere'] . '</td><td>' . $donnees['libellePres'] . '</td><td>' . $donnees['libelleQual'] . '</td><td>' . $donnees['datePeche'] . '</td></tr>';
            }
            echo '</table>

    </div>
    <div id="tabs-3">';

            $requete3 = "Select prixPlancher, prixDepart, l.datePeche,specification, libelleQual,tare, l.idBateau, poidsBrutLot, dateEnchere, libellePres, nomComm, idLot,nomBateau, idAcheteur from lot l,espece,bac,qualite,taille, presentation, peche, bateau where l.idEsp=espece.idEsp and l.datePeche=peche.datePeche and l.idBateau=peche.idBateau and peche.idBateau=bateau.idBateau and l.idTaille=taille.idTaille and l.idPres=presentation.idpres and l.idQual=qualite.idQual and l.idBac=bac.idBac and l.dateEnchere >='". $aPartirDe ."' and idAcheteur IS NULL order by dateEnchere desc";


            $resultat3 = $bdd->prepare($requete3);

            $resultat3->execute();

            $tab = array();

            $resultat3->setFetchMode(PDO::FETCH_ASSOC);

            echo '<table class="table-list-enchere">
            <tr class="table-header"><td> </td><td>Espèce</td><td>Date et heure de l\'enchère</td><td>Date de pêche</td><td>Informations supplémentaires</td></tr>';


            echo '<form method="post" action="encherir.php">';

            foreach ($resultat3 as $donnees) {
                // $infoSup='Prix de départ: '.$donnees['prixDepart'].'€ Prix Plancher: '.$donnees['prixPlancher'].'€ '.$donnees['specification'].' Qualité:'.$donnees['libelleQual'].' Tare: '.$donnees['tare'].' Poids brut: '.$donnees['poidsBrutLot'].' Presentations: '.$donnees['libellePres'];

                $prixDep = 'Prix de départ: '.$donnees['prixDepart'].'€';
                $prixPlancher = 'Prix Plancher: '.$donnees['prixPlancher'].'€';
                $taille = $donnees['specification'];
                $qualite = 'Qualité: '.$donnees['libelleQual'];
                $tare = 'Tare: '.$donnees['tare'].'kg';
                $poidsBrut = 'Poids brut: '.$donnees['poidsBrutLot'].'kg';
                $presentation = 'Présentation: '.$donnees['libellePres'];

                $infoSup=$prixDep.' '.$prixPlancher.' '.$taille.' '.$qualite.' '.$presentation.' '.$tare.' '.$poidsBrut;



                echo '<tr><td> <input type="radio" name="enchereSelectionnee" value="' . $donnees['idLot'] . '?' . $donnees['datePeche'] . '?' . $donnees['idBateau'] . '" checked></td><td>' . $donnees['nomComm'] . '</td><td>' . $donnees['datePeche'] . '</td><td>' . $donnees['dateEnchere'] .'</td><td><p><label for="details">Detail:</label><img src="../Images/Information_icon.png" class="icones" id="details" title="'.$infoSup.'"></p></td></tr>';
            }
            echo '</table>



 <input type="submit" value="Valider" />  

</form>

    </div>
</div>';


        } elseif ($_SESSION['status'] == 'crieur') {

            $requete2 = "Select login, nom, prenom, ville, cp, adresse from crieur where login='" . $_SESSION['login'] . "'";
            $resultat2 = $bdd->prepare($requete2);

            $resultat2->execute();

            while ($donnees = $resultat2->fetch()) {
                $crieurLogin = $donnees['login'];
                $crieurNom = $donnees['nom'];
                $crieurAdr = $donnees['adresse'];
                $crieurVille = $donnees['ville'];
                $crieurCp = $donnees['cp'];
                $crieurPrenom = $donnees['prenom'];
            }

            echo '<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Profil</a></li>
        <li><a href="#tabs-2">Enchère(s) du jour</a></li>

    </ul>
     <div id="tabs-1">
     <p class="col-xs-5 .col-sm-6 .col-lg-4">
     </br>
     </br>
      <table classe="table-profil">
     <tr class="darkgrey">
        <td><b>Login: </b></td>	<td>' . $crieurLogin . '</td></tr>
        <tr class="lightgrey"><td> <b>Nom: </b></td><td> ' . $crieurNom . ' </td></tr>
        <tr class="darkGrey"><td> <b>Prénom: </b> </td><td> ' . $crieurPrenom . ' </td></tr>
        <tr class="lightgrey"> <td><b>Adresse: </b></td><td>' . $crieurAdr . '</td></tr>
        <tr class="lightgrey"><td> </td><td> ' . $crieurCp . '</td></tr>
       <tr class="lightgrey"><td> </td><td>  ' . $crieurVille . '</td></tr> 
        </table>
 
     </p>
     <p class="col-xs-7 .col-sm-6 .col-lg-8">
     <div class="button" id="btnModif" onClick="showForm()" >Modifier</div>
     <div class="col-xs-7 .col-sm-6 .col-lg-8" id="hiddenForm">
     
     <div onClick="hideForm()" class="button">Annuler</div>
     <form method="post" action="chgmtProfil.php">
     <table>
     <tr class="darkGrey">
        <td ><b>Login: </b></td>	<td> <span class="warning-msg"> &#9888; Seul l\' administrateur peut changer le login &#9888;</span></td></tr>
       <tr class="lightgrey"><td> <b>Nom: </b></td><td> <input class="input-width" type="text" name="nom"  /> </td></tr>
       <tr class="darkGrey"><td> <b>Prénom: </b> </td><td><input class="input-width" type="text" name="prenom"  /> </td></tr>
       <tr class="lightgrey"> <td><b>Rue: </b></td><td> <input class="input-width" type="text" name="rue"  /></td></tr>
        <tr class="lightgrey"><td><b>Code postal: </b></td><td>  <input class="input-width" type="text" name="cp"  /></td></tr>
       <tr class="lightgrey"><td> <b>Ville: </b> </td><td> <input class="input-width" type="text" name="ville"  /></td></tr> 
        </table>
        <input type="submit" value="Valider" />  
         </form>
         </div>
     </p>
     </div>  
      <div id="tabs-2">';

            $requete = "Select idCrieur from crieur where login='" . $_SESSION['login'] . "'";
            $resultat = $bdd->prepare($requete);

            $resultat->execute();

            while ($data = $resultat->fetch()) {
                $idCrieur = $data['idCrieur'];
            }
      


            $requete3 = "Select prixPlancher, prixDepart, l.datePeche,specification, libelleQual,tare, l.idBateau, poidsBrutLot, dateEnchere, libellePres, nomComm, idLot,nomBateau, idAcheteur from lot l,espece,bac,qualite,taille, presentation, peche, bateau where l.idEsp=espece.idEsp and l.datePeche=peche.datePeche and l.idBateau=peche.idBateau and peche.idBateau=bateau.idBateau and l.idTaille=taille.idTaille and l.idPres=presentation.idpres and l.idQual=qualite.idQual and l.idBac=bac.idBac and l.dateEnchere >='". $aPartirDe ."' and idAcheteur IS NULL and idCrieur=". $idCrieur ." order by dateEnchere desc";


            $resultat3 = $bdd->prepare($requete3);

            $resultat3->execute();



            $resultat3->setFetchMode(PDO::FETCH_ASSOC);

            echo '<table class="table-list-enchere">
            <tr class="table-header"><td> </td><td>Espèce</td><td>Date et heure de l\'enchère</td><td>Date de pêche</td><td>Informations supplémentaires</td></tr>';


            echo '<form method="post" action="encherir.php">';

            foreach ($resultat3 as $donnees) {

                $prixDep = 'Prix de départ: '.$donnees['prixDepart'].'€';
                $prixPlancher = 'Prix Plancher: '.$donnees['prixPlancher'].'€';
                $taille = $donnees['specification'];
                $qualite = 'Qualité: '.$donnees['libelleQual'];
                $tare = 'Tare: '.$donnees['tare'];
                $poidsBrut = 'Poids brut: '.$donnees['poidsBrutLot'];
                $presentation = 'Présentation: '.$donnees['libellePres'];

                $infoSup=$prixDep.' '.$prixPlancher.' '.$taille.' '.$qualite.' '.$presentation.' '.$tare.' '.$poidsBrut;



                echo '<tr><td> <input type="radio" name="enchereSelectionnee" value="' . $donnees['idLot'] . '?' . $donnees['datePeche'] . '?' . $donnees['idBateau'] . '" checked></td><td>' . $donnees['nomComm'] . '</td><td>' . $donnees['datePeche'] . '</td><td>' . $donnees['dateEnchere'] .'</td><td><p><label for="details">Detail:</label><img src="../Images/Information_icon.png" class="icones" id="details" title="'.$infoSup.'"></p></td></tr>';
            }
            echo '</table>



 <input type="submit" value="Valider" />  

</form>
<a class="button" href="gestion.php">Gestion des lots</a>
    </div>
</div>';

        } else {
            header('Location: login.html?connection=0');
            exit();
        }

    }

} catch (PDOException $e) {
    die('Erreur: ' . $e->getMessage());
}


?>
</body>
<footer>
    Criée Poulgoazec, 29780 Plouhinec - +33 (0)2 98 70 77 19
</footer>
</html>
