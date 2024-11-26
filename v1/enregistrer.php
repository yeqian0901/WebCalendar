<?php

$jsonFile = file_get_contents('data.json');
    $data = json_decode($jsonFile, true);


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
    
    echo "test";


    ?>