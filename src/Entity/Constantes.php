<?php
/**
 * Copyright (C) 8 / 2019 | David annebicque | IUT de Troyes - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetv3/src/Entity/Constantes.php
 * @author     David Annebicque
 * @project intranetv3
 * @date 21/08/2019 12:29
 * @lastUpdate 21/08/2019 12:10
 */

/**
 * Created by PhpStorm.
 * User: davidannebicque
 * Date: 28/04/2018
 * Time: 07:09
 */

namespace App\Entity;

/**
 * Class Constantes
 * @package App\Entity
 */
class Constantes
{
    public const ROLE_CDD = 'ROLE_CDD';
    public const ROLE_DDE = 'ROLE_DDE';
    public const ROLE_ASS = 'ROLE_ASS';
    public const ROLE_RP = 'ROLE_RP';
    public const ROLE_RDA = 'ROLE_RDA';
    public const ROLE_EDT = 'ROLE_EDT';
    public const ROLE_RDS = 'ROLE_RDS';
    public const ROLE_AUTEUR = 'ROLE_AUTEUR';
    public const ROLE_MAT = 'ROLE_MAT';
    public const ROLE_PERMANENT = 'ROLE_PERMANENT';


    public const TIMEBEFORE_NOW = 'now';
    public const TIMEBEFORE_MINUTE = '{num} minute ago';
    public const TIMEBEFORE_MINUTES = '{num} minutes ago';
    public const TIMEBEFORE_HOUR = '{num} hour ago';
    public const TIMEBEFORE_HOURS = '{num} hours ago';
    public const TIMEBEFORE_YESTERDAY = 'yesterday';
    public const TIMEBEFORE_FORMAT = '%e %b';
    public const TIMEBEFORE_FORMAT_YEAR = '%e %b, %Y';
    public const DUREE_SEMESTRE = 5;

    public const FLASHBAG_SUCCESS = 'success';
    public const FLASHBAG_INFO = 'info';
    public const FLASHBAG_NOTICE = 'warning';
    public const FLASHBAG_ERROR = 'danger';
    public const DUREE_COURS = 1.5;

    public const TYPEDOCUMENT_EMARGEMENT = 'emargement';
    public const TYPEDOCUMENT_EVALUATION = 'evaluation';
    public const TYPEDOCUMENT_LISTE = 'liste';

    public const CHAMPS_NOM_PRENOM = 'nom';
    public const CHAMPS_NUM_ETUDIANT = 'num';
    public const CHAMPS_BAC = 'bac';
    public const CHAMPS_MAIL_ETUDIANT = 'mail';

    public const FORMAT_CSV_POINT_VIRGULE = 'csv';
    public const FORMAT_CSV_VIRGULE = 'csv-v';
    public const FORMAT_EXCEL = 'xlsx';
    public const FORMAT_PDF = 'pdf';

    public const NB_RESULTS_PER_PAGE = 2;
    public const BASE_URL = '';

    public const CIVILITE_HOMME = 'M.';
    public const CIVILITE_FEMME = 'Mme';

    public const ROLE_LISTE = [
        self::ROLE_CDD,
        self::ROLE_PERMANENT,
        self::ROLE_DDE,
        self::ROLE_ASS,
        self::ROLE_RP,
        self::ROLE_RDA,
        self::ROLE_EDT,
        self::ROLE_RDS,
        self::ROLE_AUTEUR,
        self::ROLE_MAT
    ];

    public const TAB_HEURES = [
        '',
        '8:00',
        '8:30',
        '9:00',
        '9:30',
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
        '20:30'
    ];

    public const TAB_HEURES_INDEX = array(
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
        '12:30:00'  => 10,
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
        '20:30:00' => 26
    );

    public const MOYENNE_MODULES = 'moymodules';
    public const MOYENNE_UES = 'moyues';

    public const TAB_GROUPES_INDEX = array(
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


    );

//    /**
//     * @return array
//     */
//    public static function getRoleList(): array
//    {
//        return array(
//            self::ROLE_CDD,
//            self::ROLE_DDE,
//            self::ROLE_ASS,
//            self::ROLE_RP,
//            self::ROLE_RDA,
//            self::ROLE_EDT,
//            self::ROLE_RDS,
//            self::ROLE_AUTEUR,
//            self::ROLE_MAT
//        );
//    }
}
