<?php
// Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/MyExportListing.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 25/01/2021 09:14

/**
 * Created by PhpStorm.
 * User: davidannebicque
 * Date: 12/07/2018
 * Time: 13:10
 */

namespace App\Classes;

use App\Entity\Constantes;
use App\Entity\Etudiant;
use App\Entity\Groupe;
use App\Entity\Matiere;
use App\Entity\Personnel;
use App\Entity\TypeGroupe;
use App\Classes\Excel\MyExcelWriter;
use App\Classes\Pdf\MyPDF;
use App\Repository\GroupeRepository;
use App\Repository\TypeGroupeRepository;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class MyExportListing
{
    private $colonnesEnTete = [];

    /** @var TypeGroupeRepository */
    protected $typeGroupeRepository;

    /** @var GroupeRepository */
    protected $groupeRepository;

    protected $groupes;
    private $name = '';
    private $ligne = 1;

    private $colonne = 1;

    private $exportTypeDocument;
    private $exportChamps;
    /** @var Matiere */
    private $matiere;

    /** @var DataUserSession */
    private $dataUserSession;
    private $base;

    /** @var TypeGroupe */
    private $typeGroupe;

    /** @var MyExcelWriter */
    private $myExcelWriter;

    /** @var MyPDF */
    private $myPdf;
    private $titre = '';
    /**
     * @var Personnel|null
     */
    private ?Personnel $personnel;

    /**
     * MyExport constructor.
     *
     * @param TypeGroupeRepository $typeGroupeRepository
     * @param GroupeRepository     $groupeRepository
     * @param DataUserSession      $dataUserSession
     * @param KernelInterface      $kernel
     * @param MyExcelWriter        $myExcelWriter
     * @param MyPDF                $myPdf
     */
    public function __construct(
        TypeGroupeRepository $typeGroupeRepository,
        GroupeRepository $groupeRepository,
        DataUserSession $dataUserSession,
        KernelInterface $kernel,
        MyExcelWriter $myExcelWriter,
        MyPDF $myPdf
    ) {
        $this->typeGroupeRepository = $typeGroupeRepository;
        $this->groupeRepository = $groupeRepository;
        $this->dataUserSession = $dataUserSession;
        $this->myExcelWriter = $myExcelWriter;
        $this->myPdf = $myPdf;
        $this->base = $kernel->getProjectDir() . '/';
    }

    /**
     * @param                $exportTypeDocument
     * @param                $exportFormat
     * @param                $exportChamps
     * @param                $exportFiltre
     * @param Matiere|null   $matiere
     *
     * @param Personnel|null $personnel
     *
     * @return null|StreamedResponse
     * @throws Exception
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function genereFichier(
        $exportTypeDocument,
        $exportFormat,
        $exportChamps,
        $exportFiltre,
        ?Matiere $matiere = null,
        ?Personnel $personnel = null
    ): ?StreamedResponse {
        $this->exportTypeDocument = $exportTypeDocument;
        $this->exportChamps = $exportChamps;
        $this->matiere = $matiere;
        $this->personnel = $personnel;

        if ($exportFiltre instanceof Groupe) {
            $this->typeGroupe = $exportFiltre->getTypeGroupe();
            $this->groupes = [$exportFiltre];
        } else {
            $this->typeGroupe = $this->typeGroupeRepository->find($exportFiltre);
            if ($this->typeGroupe !== null) {
                $this->groupes = $this->typeGroupe->getGroupes();
            }
        }

        $this->prepareColonnes();

        switch ($exportFormat) {
            case Constantes::FORMAT_CSV_POINT_VIRGULE:
                return $this->exportCsv(';');
            case Constantes::FORMAT_CSV_VIRGULE:
                return $this->exportCsv(',');
            case Constantes::FORMAT_EXCEL:
                return $this->exportExcel();
                case Constantes::FORMAT_PDF:
                    return $this->exportPdf();
            }


        return null;
    }

    private function prepareColonnes(): void
    {
        $this->colonnesEnTete[] = 'Nom';
        $this->colonnesEnTete[] = 'Prénom';

        foreach ($this->exportChamps as $ec) {
            $this->colonnesEnTete[] = $ec;
        }

        $semestre = $this->typeGroupe->getSemestre() ? $this->typeGroupe->getSemestre()->getLibelle() : 'sans-semestre';

        switch ($this->exportTypeDocument) {
            case Constantes::TYPEDOCUMENT_EMARGEMENT:
                $this->name = 'emargement-' . $semestre;
                $this->titre = 'FEUILLE D\'EMARGEMENT - Semestre ' . $semestre;
                for ($i = 0; $i < 5; $i++) {
                    $this->colonnesEnTete[] = '';
                }
                break;
            case Constantes::TYPEDOCUMENT_EVALUATION:
                $this->titre = 'FEUILLE D\'EVALUATION - Semestre ' . $semestre;
                $this->name = 'evaluation-' . $semestre;
                $this->colonnesEnTete[] = 'Place';
                $this->colonnesEnTete[] = 'Présence';
                $this->colonnesEnTete[] = 'Note';
                $this->colonnesEnTete[] = 'Remise Copie';
                break;
        }
    }

    private function exportCsv($separateur): StreamedResponse
    {
        $this->myExcelWriter->createSheet('export');
        if (count($this->colonnesEnTete) > 1) {
            foreach ($this->colonnesEnTete as $col) {
                $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, $col);
                $this->newColonne();
            }
        } else {
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, 'Nom');
            $this->newColonne();
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, 'Num Etudiant');
            $this->newColonne();
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, 'Prénom');
            $this->newColonne();
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, 'Groupe');
        }
        $this->newLine();

        /** @var Groupe $groupe */
        foreach ($this->groupes as $groupe) {
            /** @var Etudiant $etudiant */
            foreach ($groupe->getEtudiants() as $etudiant) {
                $this->writeLine($etudiant, $groupe);
            }
        }


        $writer = new Csv($this->myExcelWriter->getSpreadsheet());
        $writer->setDelimiter($separateur);

        return new StreamedResponse(
            static function() use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type'        => 'application/csv',
                'Content-Disposition' => 'attachment;filename="' . $this->name . '.csv"'
            ]
        );
    }

    /**
     * @return StreamedResponse
     * @throws Exception
     */
    private function exportExcel(): StreamedResponse
    {
        /** @var Groupe $groupe */
        foreach ($this->groupes as $groupe) {
            $this->myExcelWriter->createSheet($groupe->getLibelle());
            $this->writeSpecialHeader($groupe);
            $this->myExcelWriter->writeHeader($this->colonnesEnTete, 1, 17);
            $this->newLine();

            $id = 0;
            /** @var Etudiant $etudiant */
            foreach ($groupe->getEtudiants() as $etudiant) {
                $this->writeLine($etudiant, $groupe);
                $id++;
            }

            $this->myExcelWriter->writeCellName('A16', $id);

            $this->myExcelWriter->borderCells('A17:J' . $this->ligne);

            $this->myExcelWriter->getColumnAutoSize('A');
            $this->myExcelWriter->getColumnAutoSize('B');
            $this->myExcelWriter->getColumnAutoSize('C');
            $this->myExcelWriter->getColumnAutoSize('D');
            $this->myExcelWriter->getColumnDimension('E', 8);
            $this->myExcelWriter->getColumnDimension('F', 8);
            $this->myExcelWriter->getColumnDimension('G', 8);
            $this->myExcelWriter->getColumnDimension('H', 8);
            $this->myExcelWriter->getColumnDimension('I', 8);
            $this->myExcelWriter->getColumnDimension('J', 8);

            $this->setMiseEnPage();
        }

        $writer = new Xlsx($this->myExcelWriter->getSpreadsheet());

        return new StreamedResponse(
            static function() use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="' . $this->name . '.xlsx"'
            ]
        );
    }

    /**
     * @param Groupe $groupe
     *
     * @throws Exception
     */
    private function writeSpecialHeader(Groupe $groupe): void
    {
        //gérer les infos par le diplôme
        $anneeU = $groupe->getTypeGroupe()->getSemestre()->getAnneeUniversitaire()->displayAnneeUniversitaire();

        $this->myExcelWriter->writeCellName('J1', 'Année Universitaire - ' . $anneeU,
            ['style' => 'HORIZONTAL_RIGHT']);
        $this->myExcelWriter->writeCellName('J4',
            'IUT de Troyes - ' . $this->dataUserSession->getDepartement()->getLibelle(),
            ['style' => 'HORIZONTAL_RIGHT']);
        $this->myExcelWriter->writeCellName('J3', $this->titre, ['style' => 'HORIZONTAL_RIGHT']);

        $base = $this->base . 'public/upload/';
        $objDrawing = new Drawing();
        $objDrawing->setName('Logo Departement');
        $objDrawing->setDescription('Logo Departement');
        $objDrawing->setPath($base . 'logo/' . $this->dataUserSession->getDepartement()->getLogoName());
        $objDrawing->setHeight(120);
        $objDrawing->setCoordinates('A1');
        $objDrawing->setWorksheet($this->myExcelWriter->getSheet());

        switch ($this->exportTypeDocument) {
            case Constantes::TYPEDOCUMENT_EMARGEMENT:
                if ($this->matiere !== null) {
                    $this->myExcelWriter->writeCellName(
                        'A10',
                        'MATIERE ENSEIGNEE :' . $this->matiere->getDisplay()
                    );
                } else {
                    $this->myExcelWriter->writeCellName(
                        'A10',
                        'MATIERE ENSEIGNEE :'
                    );
                }

                if ($this->personnel !== null) {
                    $this->myExcelWriter->writeCellName(
                        'A8',
                        'NOM DE L\'ENSEIGNANT :' . $this->personnel->getDisplayPr()
                    );
                } else {
                    $this->myExcelWriter->writeCellName(
                        'A8',
                        'NOM DE L\'ENSEIGNANT :'
                    );
                }

                $this->myExcelWriter->writeCellName(
                    'J5',
                    'Période du 1er ' . Constantes::TAB_MOIS[date('n')] . ' au ' . date('t') . ' ' . Constantes::TAB_MOIS[date('n')] . ' ' . date('Y'),
                    ['style' => 'HORIZONTAL_RIGHT']
                );
                $this->myExcelWriter->writeCellName(
                    'G12',
                    '*Toutes les cases grisées sont à remplir par l\'enseignant. Merci !',
                    ['style' => 'HORIZONTAL_RIGHT']
                );

                $this->myExcelWriter->writeCellName(
                    'H7',
                    'Total des heures',
                    ['style' => 'HORIZONTAL_CENTER']
                );
                $this->myExcelWriter->writeCellName(
                    'H8',
                    'faites sur la période',
                    ['style' => 'HORIZONTAL_CENTER']
                );

                $this->myExcelWriter->mergeCells('A14:C14');
                $this->myExcelWriter->mergeCells('A15:C15');
                $this->myExcelWriter->mergeCells('A16:C16');

                $this->myExcelWriter->writeCellName(
                    'A14',
                    'Date de la séance',
                    ['style' => 'HORIZONTAL_CENTER']
                );

                $this->myExcelWriter->writeCellName(
                    'A15',
                    'Nombre d\'heures	',
                    ['style' => 'HORIZONTAL_CENTER']
                );

                $this->myExcelWriter->writeCellName(
                    'A16',
                    'Nom-Prénom / Horaire',
                    ['style' => 'HORIZONTAL_CENTER']
                );

                $this->myExcelWriter->mergeCells('H7:J7');
                $this->myExcelWriter->mergeCells('H8:J8');
                $this->myExcelWriter->mergeCells('H9:J12');
                $this->myExcelWriter->borderCellsRange(8, 7, 10, 12);
                $this->myExcelWriter->colorCells('H7:J12', 'ffC0C0C0');
                $this->myExcelWriter->colorCells('A14:J16', 'ffC0C0C0');
                $this->ligne = 17;
                break;
            case Constantes::TYPEDOCUMENT_EVALUATION:
                $this->myExcelWriter->writeCellName(
                    'H5',
                    'Date de l\'évaluation : .......................................',
                    ['style' => ['HORIZONTAL_RIGHT']]
                );
                $this->myExcelWriter->writeCellName('A8', 'NOM DE L\'ENSEIGNANT :');
                $this->myExcelWriter->mergeCells('A8:C8');

                $this->myExcelWriter->writeCellName('A9', 'SURVEILLANT :');
                $this->myExcelWriter->mergeCells('A9:C9');

                $this->myExcelWriter->writeCellName('A10', 'MATIERE EVALUEE :');
                $this->myExcelWriter->writeCellName('D10', $this->matiere->getLibelle());

                $this->myExcelWriter->mergeCells('A10:C10');

                $this->myExcelWriter->mergeCells('B14:C14');
                $this->myExcelWriter->writeCellName('B14', 'Groupe ' . $groupe->getLibelle(),
                    ['style' => ['HORIZONTAL_RIGHT']]);
                $this->ligne = 17;
                break;
        }
    }

    private function newLine(): void
    {
        $this->ligne++;
        $this->colonne = 1;
    }

    private function newColonne(): void
    {
        $this->colonne++;
    }

    /**
     *
     */
    private function setMiseEnPage(): void
    {
        $this->myExcelWriter->getSheet()->getHeaderFooter()->setOddHeader('&C&H' . $this->titre);
        $this->myExcelWriter->getSheet()->getHeaderFooter()->setOddFooter('&L&BDépartement | ' . $this->name . ' | Généré depuis l\'intranet le ' . date('d/m/Y') . '&RPage &P sur &N');
        $this->myExcelWriter->getSheet()->getPageSetup()->setFitToPage(true);
    }

    /**
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    private function exportPdf()
    {
        return $this->myPdf::generePdf('pdf/listing.html.twig',
            [
                'typeGroupe' => $this->typeGroupe,
                'matiere'    => $this->matiere,
                'personnel'  => $this->personnel,
                'groupes'    => $this->groupes
            ],
            $this->name,
            $this->dataUserSession->getDepartement()->getLibelle());
    }

    /**
     * @param Etudiant $etudiant
     * @param Groupe   $groupe
     */
    private function writeLine(Etudiant $etudiant, Groupe $groupe): void
    {
        if (count($this->colonnesEnTete) > 1) {
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, strtoupper($etudiant->getNom()));
            $this->newColonne();
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, mb_strtoupper($etudiant->getPrenom()));
            $this->newColonne();
            foreach ($this->colonnesEnTete as $col) {
                switch ($col) {
                    case Constantes::CHAMPS_NUM_ETUDIANT:
                        $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne,
                            mb_strtoupper($etudiant->getNumEtudiant()));
                        $this->newColonne();
                        break;
                    case Constantes::CHAMPS_BAC:
                        $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne,
                            mb_strtoupper($etudiant->getBac() !== null ? $etudiant->getBac()->getLibelle() : 'Non défini'));
                        $this->newColonne();
                        break;
                    case Constantes::CHAMPS_GROUPE:
                        $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, $groupe->getLibelle());
                        $this->newColonne();
                        break;
                    case Constantes::CHAMPS_MAIL_ETUDIANT:
                        $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, $etudiant->getMailUniv());
                        $this->newColonne();
                        break;
                }
            }
        } else {
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, $etudiant->getNumEtudiant());
            $this->newColonne();
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, mb_strtoupper($etudiant->getNom()));
            $this->newColonne();
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, mb_strtoupper($etudiant->getPrenom()));
            $this->newColonne();
            $this->myExcelWriter->writeCellXY($this->colonne, $this->ligne, $groupe->getLibelle());
        }
        $this->newLine();
    }
}
