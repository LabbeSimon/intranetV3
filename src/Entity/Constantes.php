<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/Constantes.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 09/10/2021 10:33
 */

namespace App\Entity;

/**
 * Class Constantes.
 */
class Constantes
{
    public const TYPE_DESTINATAIRE_PERMANENT = 'PERM';
    public const TYPE_DESTINATAIRE_PERSONNEL = 'PERS';
    public const TYPE_DESTINATAIRE_ETUDIANT = 'ETU';
    public const TYPE_DESTINATAIRE_TOUS = 'ALL';
    public const TYPE_DESTINATAIRE_LISTE =
        [
            self::TYPE_DESTINATAIRE_PERMANENT => self::TYPE_DESTINATAIRE_PERMANENT,
            self::TYPE_DESTINATAIRE_ETUDIANT => self::TYPE_DESTINATAIRE_ETUDIANT,
            self::TYPE_DESTINATAIRE_TOUS => self::TYPE_DESTINATAIRE_TOUS,
        ];

    public const ROLE_CDD = 'ROLE_CDD';
    public const ROLE_DDE = 'ROLE_DDE';
    public const ROLE_ASS = 'ROLE_ASS';
    public const ROLE_PRJ = 'ROLE_PRJ';
    public const ROLE_RP = 'ROLE_RP';
    public const ROLE_ABS = 'ROLE_ABS';
    public const ROLE_EDT = 'ROLE_EDT';
    public const ROLE_STA = 'ROLE_STA';
    public const ROLE_NOTES = 'ROLE_NOTES';
    public const ROLE_AUTEUR = 'ROLE_AUTEUR';
    public const ROLE_MAT = 'ROLE_MAT';
    public const ROLE_PERMANENT = 'ROLE_PERMANENT';

    public const DUREE_SEMESTRE = 5;

    public const FLASHBAG_SUCCESS = 'success';
    public const FLASHBAG_INFO = 'info';
    public const FLASHBAG_NOTICE = 'warning';
    public const FLASHBAG_ERROR = 'error';
    public const DUREE_COURS = 1.5;

    public const TYPEDOCUMENT_EMARGEMENT = 'emargement';
    public const TYPEDOCUMENT_EVALUATION = 'evaluation';
    public const TYPEDOCUMENT_LISTE = 'liste';

    public const CHAMPS_NOM_PRENOM = 'nom';
    public const CHAMPS_GROUPE = 'groupe';
    public const CHAMPS_NUM_ETUDIANT = 'num';
    public const CHAMPS_BAC = 'bac';
    public const CHAMPS_MAIL_ETUDIANT = 'mail';

    public const FORMAT_CSV_POINT_VIRGULE = 'csv';
    public const FORMAT_CSV_VIRGULE = 'csv-v';
    public const FORMAT_EXCEL = 'xlsx';
    public const FORMAT_PDF = 'pdf';

    public const NB_RESULTS_PER_PAGE = 2;

    public const CIVILITE_HOMME = 'M.';
    public const CIVILITE_FEMME = 'Mme';

    public const ROLE_LISTE = [
        self::ROLE_CDD,
        self::ROLE_PERMANENT,
        self::ROLE_DDE,
        self::ROLE_ASS,
        self::ROLE_RP,
        self::ROLE_ABS,
        self::ROLE_EDT,
        self::ROLE_STA,
        self::ROLE_NOTES,
        self::ROLE_AUTEUR,
        self::ROLE_MAT,
        self::ROLE_PRJ,
    ];

    public const TAB_HEURES = [
        '',
        '08:00',
        '08:30',
        '09:00',
        '09:30',
        '10:00',
        '10:30',
        '11:00',
        '11:30',
        '12:00',
        '12:30',
        '13:00',
        '13:30',
        '14:00',
        '14:30',
        '15:00',
        '15:30',
        '16:00',
        '16:30',
        '17:00',
        '17:30',
        '18:00',
        '18:30',
        '19:00',
        '19:30',
        '20:00',
        '20:30',
    ];

    public const TAB_HEURES_INDEX = [
        '' => 0,
        '08:00:00' => 1,
        '08:30:00' => 2,
        '09:00:00' => 3,
        '09:30:00' => 4,
        '10:00:00' => 5,
        '10:30:00' => 6,
        '11:00:00' => 7,
        '11:30:00' => 8,
        '12:00:00' => 9,
        '12:30:00' => 10,
        '13:00:00' => 11,
        '13:30:00' => 12,
        '14:00:00' => 13,
        '14:30:00' => 14,
        '15:00:00' => 15,
        '15:30:00' => 16,
        '16:00:00' => 17,
        '16:30:00' => 18,
        '17:00:00' => 19,
        '17:30:00' => 20,
        '18:00:00' => 21,
        '18:30:00' => 22,
        '19:00:00' => 23,
        '19:30:00' => 24,
        '20:00:00' => 25,
        '20:30:00' => 26,
    ];

    public const METHODE_CALCUL_MOY_MODULE = 'moymodules';
    public const METHODE_CALCUL_MOY_UE = 'moyues';

    public const TAB_METHODE_CALCUL = [
        'choice.moymodules' => Constantes::METHODE_CALCUL_MOY_MODULE,
        'choice.moyues' => Constantes::METHODE_CALCUL_MOY_UE,
    ];

    public const TAB_GROUPES_INDEX = [
        'S1 DEC TP11' => 1,
        'S1 DEC TP12' => 2,
        'S1 DEC TD1' => 1,
        'S2 CM' => 1,
        'S2 TD1' => 1,
        'S2 TD2' => 2,
        'S2 TD3' => 3,
        'S2 TP11' => 1,
        'S2 TP12' => 2,
        'S2 TP21' => 3,
        'S2 TP22' => 4,
        'S2 TP31' => 5,
        'S2 TP32' => 6,
    ];

    public const TAB_JOURS = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'];
    public const TAB_MOIS = [
        '',
        'Janvier',
        'Février',
        'Mars',
        'Avril',
        'Mai',
        'Juin',
        'Juillet',
        'Août',
        'Septembre',
        'Octobre',
        'Novembre',
        'Décembre',
    ];

    public const TAB_CRENEAUX = [
        1 => ['8h00', '9h30'],
        4 => ['9h30', '11h00'],
        7 => ['11h00', '12h30'],
        10 => ['12h30', '14h00'],
        13 => ['14h00', '15h30'],
        16 => ['15h30', '17h00'],
        19 => ['17h00', '18h30'],
    ];

    //Sous Commission
    public const PAS_OPTION = 'pasoption';

    public const UE_VALIDE = 'V';
    public const UE_NON_VALIDE = 'NV';

    public const SEUIL_UE = 8;
    public const SEUIL_MOYENNE = 10;
    public const SEMESTRE_VALIDE = 'V';
    public const SEMESTRE_NON_VALIDE = 'NV';
    public const SEMESTRE_VCA = 'VCA';
    public const SEMESTRE_VCJ = 'VCJ';
    public const PROPOSITION_INDEFINIE = '?';
    public const SEMESTRE_EN_COURS = 'E.C.';
    public const SEMESTRE_DEMISSIONNAIRE = 'DEM';
    public const SEMESTRE_REORIENTE = 'REO';
    public const SEMESTRE_BLANCHIMENT = 'BL';
    public const SUPPRIMER_FORMATION = 'SUPPRIMER_FORMATION';
    public const SCOLARITE_DIPLOME = 'DIPLOME';

    public const SS_COMM_DECISION_COULEUR = [
        self::SEMESTRE_VALIDE => 'ff00cc00',
        self::SEMESTRE_NON_VALIDE => 'ffff0000',
        self::SEMESTRE_VCA => 'fff0a300',
        self::SEMESTRE_VCJ => 'fff30900',
        '' => 'ffffff',
        'EC' => 'ffff0000',
        'E.C.' => 'ffff0000',
    ];
    public const NB_MESSAGE_PAR_PAGE = 10;
    public const TAB_HEURES_EDT = [
        1 => ['8h00', '9h30'],
        2 => ['9h30', '11h00'],
        3 => ['11h00', '12h30'],
        4 => ['12h30', '14h00'],
        5 => ['14h00', '15h30'],
        6 => ['15h30', '17h00'],
        7 => ['17h00', '18h30'],
    ];

    public const TAB_HEURES_EDT_2 = [
        0 => ['0800', '8h00'],
        1 => ['0830', '8h30'],
        2 => ['0900', '9h00'],
        3 => ['0930', '9h30'],
        4 => ['1000', '10h00'],
        5 => ['1030', '10h30'],
        6 => ['1100', '11h00'],
        7 => ['1130', '11h30'],
        8 => ['1200', '12h00'],
        9 => ['1230', '12h30'],
        10 => ['1300', '13h00'],
        11 => ['1330', '13h30'],
        12 => ['1400', '14h00'],
        13 => ['1430', '14h30'],
        14 => ['1500', '15h00'],
        15 => ['1530', '15h30'],
        16 => ['1600', '16h00'],
        17 => ['1630', '16h30'],
        18 => ['1700', '17h00'],
        19 => ['1730', '17h30'],
        20 => ['1800', '18h00'],
        21 => ['1830', '18h30'],
        22 => ['1900', '19h00'],
        23 => ['1930', '19h30'],
        24 => ['2000', '20h00'],
    ];

    public const TAB_HEURES_EDT_LIGNE_2 = [
        '0800' => 0,
        '0830' => 1,
        '0900' => 2,
        '0930' => 3,
        '1000' => 4,
        '1030' => 5,
        '1100' => 6,
        '1130' => 7,
        '1200' => 8,
        '1230' => 9,
        '1300' => 10,
        '1330' => 11,
        '1400' => 12,
        '1430' => 13,
        '1500' => 14,
        '1530' => 15,
        '1600' => 16,
        '1630' => 17,
        '1700' => 18,
        '1730' => 19,
        '1800' => 20,
        '1830' => 21,
        '1900' => 22,
        '1930' => 23,
        '2000' => 24,
    ];

    public const COULEURS = [
        'Bleu' => 'blue',
        'Indigo' => 'Indigo',
        'Mauve/pourpre' => 'purple',
        'Rose' => 'pink',
        'Rouge' => 'red',
        'Orange' => 'orange',
        'Jaune' => 'yellow',
        'Vert' => 'green',
        'Turquoise' => 'teal',
        'Cyan' => 'cyan',
    ];
    public const PAS_DE_SOUS_COMM = 'PAS_DE_SOUS_COMM';
    public const MAJORATION_CM = 1.5;

    public const FILTRE_EDT_PROMO = 'promo';
    public const FILTRE_EDT_PROF = 'prof';
    public const FILTRE_EDT_MODULE = 'module';
    public const FILTRE_EDT_SALLE = 'salle';
    public const FILTRE_EDT_ETUDIANT = 'etudiant';
    public const FILTRE_EDT_JOUR = 'jour';
}
