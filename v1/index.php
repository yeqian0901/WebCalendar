<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="script.js" defer></script>
    <title>Document</title>
</head>

<body>
     <?php

    $jsonFile = file_get_contents('data.json');
    $data = json_decode($jsonFile, true);

    ?> 

    <h1 id="titre">Emploi du temps de la semaine de
        <span id="semaine">
            <!--     <script>
                afficheSemaineActuel("semaine");
            </script> -->
        </span>
    </h1>

    <form id="formulaire" style="display:none" method="post" action ="index.php">
        <p>
            <label for="dateCours"> Date du cours : </label>
            <input type="date" id="dateCours" name="dateCours" required>
            <!--
            <input type="number" id="jourCours" name="jourCours" min="1" max="31" placeholder="jour">
            <input type="number" id="moisCours" name="moisCours" min="1" max="12" placeholder="mois">
            <input type="number" id="anneeCours" name="anneeCours" placeholder="année" min="2020" max="2100">
-->
            <br>

            <label for="horaireDebutCours"> Horaire du début de cours: </label>
            <input type="time" id="horaireDebutCours" name="horaireDebutCours" min="08:00" max="19:00" required>
            <!--
            <input type="number" id="heureDebutCours" name="heureDebutCours" placeholder="heure" min="8" max="19">
            <input type="number" id="minDebutCours" name="minDebutCours" placeholder="min" min="0" max="45" step="15">
-->
            <br>

            <label for="horaireFinCours"> Horaire de la fin du cours : </label>
            <input type="time" id="horaireFinCours" name="horaireFinCours" min="08:00" max="19:00" required>
            <!--
            <input type="number" id="heureFinCours" name="heureFinCours" placeholder="heure" min="8" max="19">
            <input type="number" id="minFinCours" name="minFinCours" placeholder="min" min="0" max="45" step="15">
-->
            <br>

            <label for="typeDeCour">Choisir un type de cours : </label>
            <select name="typeDeCour" id="typeDeCour" class="questionFormulaire">
                <option value="">--Choisir un type--</option>
                <option value="Cours">Cours</option>
                <option value="TD">TD</option>
                <option value="Examen">Examen</option>
                <option value="Soutenance">Soutenance</option>
                <option value="Amphi">Amphi</option>
            </select>
            <br>

            <label for="matiere">Choisir une matière : </label>
            <select name="matiere" id="matiere" class="questionFormulaire">
                <option value="">--Choisir une matière--</option>
                <option value="LF">LF</option>
                <option value="ProgWeb">ProgWeb</option>
                <option value="BD2">BD2</option>
                <option value="IAS">IAS</option>
            </select>
            <!--     <input type="text" id="matiere" name="matiere" class="questionFormulaire" required> -->
            <br>

            <label for="enseignant">Choisir un enseignant : </label>
            <select name="enseignant" id="enseignant" class="questionFormulaire">
                <option value="">--Choisir un prof--</option>
                <option value="Frédéric Vernier">Frédéric Vernier</option>
                <option value="Frédéric Gruau">Frédéric Gruau</option>
                <option value="Ouriel Grynszpan">Ouriel Grynszpan</option>
                <option value="François Landes">François Landes</option>
            </select>
            <br>

            <label for="salle">Choisir une salle : </label>
            <select name="salle" id="salle" class="questionFormulaire">
                <option value="">--Choisir une salle--</option>
                <option value="E203">E203</option>
                <option value="E204">E204</option>
                <option value="E205">E205</option>
                <option value="E206">E206</option>
            </select>
            <br>

            <label for="groupeDuCours">Choisir un groupe : </label>
            <select name="groupeDuCours" id="groupeDuCours" class="questionFormulaire">
                <option value="">--Choisir un groupe--</option>
                <option value="G1">G1</option>
                <option value="G2">G2</option>
                <option value="G3">G3</option>
                <option value="G4">G4</option>
            </select>
            <br>

            <button id="validerFormulaire">Valider</button>
        </p>
    </form>




    <div id="avantEtapres">
        <!--    <button id="avant" class="calendrierdBouton" onclick=" afficheSemaineAvant( 'semaine')">◄</button>
        <button id="apres" class="calendrierdBouton" onclick="afficheSemaineApres('semaine')">►</button>
-->
        <button id="avant" class="calendrierdBouton">◄</button>
        <button id="apres" class="calendrierdBouton">►</button>
    </div>


    <div id="emploiDuTemp"></div>



    <?php


    /*************************************************************************************/
    /*                      Calcul le lundi de la date donnée                            */
    /*************************************************************************************/

    /* Test si l'année est bissextile */
    function nbJourFevrier($annee)
    {
        if ($annee % 4 == 0)
            return 29;
        else
            return 28;
    }


    /**renvoie le nombre de jour total du mois
     * 0 -> janvier ... 11 -> décembre
     */
    function nbJourDuMois($annee, $mois)
    {
        switch ($mois) {
            case "01":
            case "03":
            case "05":
            case "07":
            case "08":
            case "10":
            case "12":
                return 31;
            case "04":
            case "06":
            case "09":
            case "11":
                return 30;
            case "02":
                return nbJourFevrier($annee);
        }
    }

    /* Test si c'est un lundi */
    function estLundi($jourSemaine)
    {
        if ($jourSemaine == 1)
            return true;
        else
            return false;
    }

    /* Calcul la date du lundi de la date donnée  */
    function calculSemaineActuel($date)
    {
        $annee = date('Y', strtotime($date));
        $mois = date('m', strtotime($date));
        $jour = date('d', strtotime($date));
        $jourSemaine = date("N", strtotime($date));



        if (estLundi($jourSemaine))
            return $date;
        else {
            if ($jour > ($jourSemaine - 1)) {
                $jour = $jour - ($jourSemaine - 1);
                return $annee . '-' . $mois . '-' . $jour;
            } else if ($mois != 1) {
                $mois = $mois - 1;
                $jour = nbJourDuMois($annee, $mois) + ($jour - ($jourSemaine - 1));
                return $annee . '-' . $mois . '-' . $jour;
            } else {
                $annee = $annee - 1;
                $mois = 12;
                $jour = nbJourDuMois($annee, 12) + ($jour - ($jourSemaine - 1));
                return $annee . '-' . $mois . '-' . $jour;
            }
        }
    }


    /*************************************************************************************/
    /*                               Stokage des données                                 */
    /*************************************************************************************/

    $champ = [
        'dateCours',
        'horaireDebutCours',
        'horaireFinCours',
        'typeDeCour',
        'matiere',
        'enseignant',
        'salle',
        'groupeDuCours'
    ];

    /**Test si le formulaire est remplit entièrement */
    function formulaireRemplit($liste)
    {
        for ($i = 0; $i < count($liste); $i++) {
            if (!array_key_exists($liste[$i], $_POST)) {
                return false;
            } else if (($_POST[$liste[$i]] == null)) {
                return false;
            }

        }
        return true;
    }



    if (formulaireRemplit($champ)) {
        $dateCours = $_POST["dateCours"];
        $horaireDebutCours = $_POST["horaireDebutCours"];
        $horaireFinCours = $_POST["horaireFinCours"];
        $typeDeCour = $_POST["typeDeCour"];
        $matiere = $_POST["matiere"];
        $enseignant = $_POST["enseignant"];
        $salle = $_POST["salle"];
        $groupeDuCours = $_POST["groupeDuCours"];


        //la date du cours donnée en format jj/mm/aaaa
        $date = new DateTime($dateCours);
        $dateText = $date->format('j/m/Y');
        $jourSemaine = date("N", strtotime($dateCours));

        //print($dateCours);
        //print(date("N", strtotime($dateCours)));
    
        //la date du premier jour de la semaine(lundi) de la date donnée en format jj/mm/aaaa
        $dateSemaine = calculSemaineActuel($dateCours);
        $dateSemaine2 = new DateTime($dateSemaine);
        $dateSemaineText = $dateSemaine2->format('j/m/Y');

        //convertie les horaire en format --h--
        $horaireDebutCoursText = date("G\hi", strtotime($horaireDebutCours));
        $horaireFinCoursText = date("G\hi", strtotime($horaireFinCours));


        $newData = array(
            'typeDeCour' => $typeDeCour,
            'matiere' => $matiere,
            'enseignant' => $enseignant,
            'salle' => $salle,
            'groupeDuCours' => $groupeDuCours,
            'horaireDebutCours' => $horaireDebutCoursText,
            'horaireFinCours' => $horaireFinCoursText,
            'date' => $dateText,
            'jourSemaine' => $jourSemaine,
        );



        if (isset($data[$dateSemaineText])) {
            $n = count($data[$dateSemaineText]);
            $data[$dateSemaineText]["cours" . ($n + 1)] = $newData;
        } else {
            $data[$dateSemaineText]["cours1"] = $newData;
        }
        $json_data = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents("data.json", $json_data);

    }



    // if (formulaireRemplit($champ)) {
    //     $jourCours = $_POST["jourCours"];
    //     $moisCours = $_POST["moisCours"];
    //     $anneeCours = $_POST["anneeCours"];
    
    //     $heureDebutCours = $_POST["heureDebutCours"];
    //     $minDebutCours = $_POST["minDebutCours"];
    
    //     $heureFinCours = $_POST["heureFinCours"];
    //     $minFinCours = $_POST["minFinCours"];
    
    //     $typeDeCour = $_POST["typeDeCour"];
    //     $matiere = $_POST["matiere"];
    //     $enseignant = $_POST["enseignant"];
    //     $salle = $_POST["salle"];
    //     $groupeDuCours = $_POST["groupeDuCours"];
    

    //     //la date du cours donnée en format jj/mm/aaaa
    //     $date = new DateTime($anneeCours . '-' . $moisCours . '-' . $jourCours);
    //     $dateText = $date->format('j/m/Y');
    

    //     //la date du premier jour de la semaine(lundi) de la date donnée en format jj/mm/aaaa
    //     $dateSemaine = calculSemaineActuel($anneeCours . '-' . $moisCours . '-' . $jourCours);
    //     $dateSemaine2 = new DateTime($dateSemaine);
    //     $dateSemaineText = $dateSemaine2->format('j/m/Y');
    
    //     //debut et fin de cours 
    //     $debutCours = concatHeureMin($heureDebutCours, $minDebutCours);
    //     $finCours = concatHeureMin($heureFinCours, $minFinCours);
    


    //     $newData = array(
    //         'typeDeCour' => $typeDeCour,
    //         'matiere' => $matiere,
    //         'enseignant' => $enseignant,
    //         'salle' => $salle,
    //         'groupeDuCours' => $groupeDuCours,
    //         'debutCours' => $debutCours,
    //         'finCours' => $finCours,
    //         'date' => $dateText
    //     );
    
    //     if (isset($data[$dateSemaineText])) {
    //         $n = count($data[$dateSemaineText]);
    //         $data[$dateSemaineText]["cours" . ($n + 1)] = $newData;
    //         //array_push($data[$dateSemaineText], $newData);
    //     } else {
    //         $data[$dateSemaineText]["cours1"] = $newData;
    //     }
    
    //     $json_data = json_encode($data, JSON_PRETTY_PRINT);
    //     file_put_contents("data.json", $json_data);
    

    // } else {
    //     print "formulaire non remplit!";
    // }
    


    ?>

</body>

</html>