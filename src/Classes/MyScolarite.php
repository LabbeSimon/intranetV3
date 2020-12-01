<?php
// Copyright (c) 2020. | David Annebicque | IUT de Troyes  - All Rights Reserved
// @file /Users/davidannebicque/htdocs/intranetV3/src/Classes/MyScolarite.php
// @author davidannebicque
// @project intranetV3
// @lastUpdate 01/12/2020 22:04

namespace App\Classes;


use App\Entity\AnneeUniversitaire;
use App\Entity\Constantes;
use App\Entity\Departement;
use App\Entity\Etudiant;
use App\Entity\Matiere;
use App\Entity\Parcour;
use App\Entity\Scolarite;
use App\Entity\ScolariteMoyenneUe;
use App\Entity\Semestre;
use App\Entity\Ue;
use App\Repository\ScolariteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class MyScolarite
{
    public MyUpload $myUpload;

    public EntityManagerInterface $entityManager;

    private ScolariteRepository $scolariteRepository;

    /**
     * MyPpn constructor.
     *
     * @param MyUpload $myUpload
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        MyUpload $myUpload,
        EntityManagerInterface $entityManager,
        ScolariteRepository $scolariteRepository
    ) {
        $this->myUpload = $myUpload;
        $this->entityManager = $entityManager;
        $this->scolariteRepository = $scolariteRepository;
    }

    /**
     * @param             $data
     * @param Departement $departement
     *
     * @return bool
     * @throws Exception
     */
    public function importCsv($data, Departement $departement): bool
    {
        $file = $this->myUpload->upload($data, 'temp');

        $ues = $this->entityManager->getRepository(Ue::class)->tableauUeApogee($departement);
        $semestres = $this->entityManager->getRepository(Semestre::class)->tableauSemestresApogee($departement);
        $etudiants = $this->entityManager->getRepository(Etudiant::class)->findByDepartementArray($departement);

        $handle = fopen($file, 'rb');

        /*Si on a réussi à ouvrir le fichier*/
        if ($handle) {
            /* supprime la première ligne */
            fgetcsv($handle, 1024, ';');
            /*Tant que l'on est pas à la fin du fichier*/
            while (!feof($handle)) {
                /*On lit la ligne courante*/
                $ligne = fgetcsv($handle, 1024, ';');

                if (array_key_exists($ligne[1], $semestres) && array_key_exists($ligne[0], $etudiants)) {
                    //numetudiant	codesemestre	semestre	ordre	moyenne	nbabsences	decision	suite ues
                    $scol = new Scolarite();
                    $scol->setAnneeUniversitaire(null);
                    $scol->setSemestre($semestres[$ligne[1]]);
                    $scol->setEtudiant($etudiants[$ligne[0]]);
                    $scol->setDecision($ligne[6]);
                    $scol->setMoyenne(Tools::convertToFloat($ligne[4]));
                    $scol->setOrdre($ligne[3]);
                    $scol->setNbAbsences($ligne[5]);
                    $scol->setProposition($ligne[7]);
                    $this->entityManager->persist($scol);
                    $tues = explode('!', $ligne[8]);
                    foreach ($tues as $tue) {
                        $ue = explode(':', $tue);
                        if (array_key_exists($ue[0], $ues)) {
                            $scolUe = new ScolariteMoyenneUe();
                            $scolUe->setScolarite($scol);
                            $scolUe->setMoyenne(Tools::convertToFloat($ue[1]));
                            $scolUe->setUe($ues[$ue[0]]);
                            $this->entityManager->persist($scolUe);
                        }
                    }
                }
            }
            $this->entityManager->flush();

            /*On ferme le fichier*/
            fclose($handle);
            unlink($file); //suppression du fichier

            return true;
        }

        return false;
    }

    public function initSemestre(Semestre $semestre, AnneeUniversitaire $anneeUniversitaire)
    {
        foreach ($semestre->getEtudiants() as $etudiant) {
            $parcour = $this->scolariteRepository->findBy([
                'semestre' => $semestre->getId(),
                'etudiant' => $etudiant->getId(),
                'decision' => Constantes::SEMESTRE_EN_COURS
            ]);
            if (count($parcour) === 0) {
                $maxOrdre = $this->scolariteRepository->findOrdreMax($etudiant);
                $scolarite = new Scolarite();
                $scolarite->setSemestre($semestre);
                $scolarite->setAnneeUniversitaire($anneeUniversitaire);
                $scolarite->setEtudiant($etudiant);
                $scolarite->setOrdre($maxOrdre + 1);
                $scolarite->setDecision(Constantes::SEMESTRE_EN_COURS);
                $this->entityManager->persist($scolarite);
            }
        }
        $this->entityManager->flush();
    }

}
