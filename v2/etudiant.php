<?php session_start(); ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="calculSemaine.js" defer></script>
    <script src="creerTableau.js" defer></script>
    <script src="afficheSlot.js" defer></script>
    <script src="scriptEtudiant.js" defer></script>
    <title>Etudiant</title>
</head>



<body>
    <button class="border"><a href="login.html">Deconnecter</a></button>

    <h1 id="titre">Emploi du temps de la semaine de
        <span id="semaine">
        </span>
    </h1>



    <div id="avantEtapres">
        <button id="avant" class="calendrierdBouton btn-primary btn-lg">◄</button>
        <button id="apres" class="calendrierdBouton btn-primary btn-lg">►</button>
    </div>
    <div id="emploiDuTemp"></div>

</body>

</html>