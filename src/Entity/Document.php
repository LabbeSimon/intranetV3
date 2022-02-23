<?php
/*
 * Copyright (c) 2021. | David Annebicque | IUT de Troyes  - All Rights Reserved
 * @file /Users/davidannebicque/htdocs/intranetV3/src/Entity/Document.php
 * @author davidannebicque
 * @project intranetV3
 * @lastUpdate 25/10/2021 12:02
 */

namespace App\Entity;

use App\Entity\Traits\LifeCycleTrait;
use App\Entity\Traits\TypeDestinataireTrait;
use App\Entity\Traits\UuidTrait;
use App\Repository\DocumentRepository;
use Carbon\Carbon;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Document extends BaseEntity
{
    use UuidTrait;
    use LifeCycleTrait;
    use TypeDestinataireTrait;

    public const TYPE_DOCUMENT = [
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'Prés. PPT',
        'application/pdf' => 'PDF',
        'image/jpeg' => 'Image (jpeg)',
        'application/vnd.ms-excel' => 'Tabl. Excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'Tabl. Excel',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'Doc. Word',
        'application/msword' => 'Doc. Word',
        'application/vnd.oasis.opendocument.text' => 'Doc. ODT',
    ];

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $taille = null;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $typeFichier = null;

    #[Groups(groups: ['document_administration'])]
    #[ORM\ManyToOne(targetEntity: TypeDocument::class, inversedBy: 'documents')]
    private ?TypeDocument $typeDocument = null;

    #[Groups(groups: ['document_administration'])]
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Groups(groups: ['document_administration'])]
    #[Assert\Length(min: 5, max: 100, minMessage: 'Le titre du document doit contenir au minimum {{ limit }} caractères', maxMessage: 'Le titre du document doit contenir au maximum {{ limit }} caractères')]
    #[ORM\Column(type: Types::STRING, length: 100)]
    private ?string $libelle = null;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private ?string $documentName = '';

    /**
     * @Vich\UploadableField(mapping="documentFile", fileNameProperty="documentName", size="taille",
     *                                               mimeType="typeFichier")
     */
    private ?File $documentFile;

    #[Groups(groups: ['document_administration'])]
    #[ORM\ManyToMany(targetEntity: Semestre::class, inversedBy: 'documents')]
    private Collection $semestres;

    /**
     * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\DocumentFavori>
     */
    #[ORM\OneToMany(mappedBy: 'document', targetEntity: DocumentFavori::class)]
    private Collection $documentsFavoris;

    /**
     * Document constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->documentsFavoris = new ArrayCollection();
        $this->setUuid(Uuid::uuid4());
        $this->semestres = new ArrayCollection();
    }

    public function __clone()
    {
        $this->setUuid(Uuid::uuid4());
        $this->setCreated(Carbon::now());
    }

    public function getTaille(): ?float
    {
        return $this->taille;
    }

    public function setTaille(?float $taille = 0.0): self
    {
        $this->taille = $taille;

        return $this;
    }

    public function getTypeFichier(): ?string
    {
        return $this->typeFichier;
    }

    public function setTypeFichier(?string $typeFichier): self
    {
        $this->typeFichier = $typeFichier;

        return $this;
    }

    public function getTypeDocument(): ?TypeDocument
    {
        return $this->typeDocument;
    }

    public function setTypeDocument(?TypeDocument $typeDocument): self
    {
        $this->typeDocument = $typeDocument;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }

    public function setDocumentFile(?File $documentFile = null): void
    {
        $this->documentFile = $documentFile;
        if (null !== $documentFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->setUpdatedValue();
        }
    }

    public function getDocumentName(): ?string
    {
        return $this->documentName;
    }

    public function setDocumentName(?string $documentName): void
    {
        $this->documentName = $documentName;
    }

    /**
     * @return Collection|Semestre[]
     */
    public function getSemestres(): Collection
    {
        return $this->semestres;
    }

    public function addSemestre(Semestre $semestre): self
    {
        if (!$this->semestres->contains($semestre)) {
            $this->semestres[] = $semestre;
        }

        return $this;
    }

    public function removeSemestre(Semestre $semestre): self
    {
        if ($this->semestres->contains($semestre)) {
            $this->semestres->removeElement($semestre);
        }

        return $this;
    }

    public function typeFichierTraduit(): ?string
    {
        if (array_key_exists($this->typeFichier, self::TYPE_DOCUMENT)) {
            return self::TYPE_DOCUMENT[$this->typeFichier];
        }

        return $this->typeFichier;
    }

    public function tailleKo(): float
    {
        return $this->taille / 1024;
    }
}
