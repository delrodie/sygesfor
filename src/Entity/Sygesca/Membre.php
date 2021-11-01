<?php

namespace App\Entity\Sygesca;

use Doctrine\ORM\Mapping as ORM;

/**
 * Membre
 *
 * @ORM\Table(name="Membre", indexes={@ORM\Index(name="IDX_F118FE1F7A45358C", columns={"groupe_id"}), @ORM\Index(name="IDX_F118FE1FF6203804", columns={"statut_id"})})
 * @ORM\Entity
 */
class Membre
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="matricule", type="string", length=255, nullable=true)
     */
    private $matricule;

    /**
     * @var string|null
     *
     * @ORM\Column(name="carte", type="string", length=255, nullable=true)
     */
    private $carte;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="prenoms", type="string", length=255, nullable=true)
     */
    private $prenoms;

    /**
     * @var string|null
     *
     * @ORM\Column(name="dateNaissance", type="string", length=255, nullable=true)
     */
    private $datenaissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lieuNaissance", type="string", length=255, nullable=true)
     */
    private $lieunaissance;

    /**
     * @var string|null
     *
     * @ORM\Column(name="sexe", type="string", length=255, nullable=true)
     */
    private $sexe;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contact", type="string", length=255, nullable=true)
     */
    private $contact;

    /**
     * @var string|null
     *
     * @ORM\Column(name="urgence", type="string", length=255, nullable=true)
     */
    private $urgence;

    /**
     * @var string|null
     *
     * @ORM\Column(name="contactUrgence", type="string", length=255, nullable=true)
     */
    private $contacturgence;

    /**
     * @var string|null
     *
     * @ORM\Column(name="branche", type="string", length=255, nullable=true)
     */
    private $branche;

    /**
     * @var string|null
     *
     * @ORM\Column(name="fonction", type="string", length=255, nullable=true)
     */
    private $fonction;

    /**
     * @var string|null
     *
     * @ORM\Column(name="cotisation", type="string", length=255, nullable=true)
     */
    private $cotisation;

    /**
     * @var string|null
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var \Groupe
     *
     * @ORM\ManyToOne(targetEntity="Groupe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="groupe_id", referencedColumnName="id")
     * })
     */
    private $groupe;

    /**
     * @var \Statut
     *
     * @ORM\ManyToOne(targetEntity="Statut")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="statut_id", referencedColumnName="id")
     * })
     */
    private $statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(?string $matricule): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getCarte(): ?string
    {
        return $this->carte;
    }

    public function setCarte(?string $carte): self
    {
        $this->carte = $carte;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(?string $prenoms): self
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getDatenaissance(): ?string
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?string $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getLieunaissance(): ?string
    {
        return $this->lieunaissance;
    }

    public function setLieunaissance(?string $lieunaissance): self
    {
        $this->lieunaissance = $lieunaissance;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getUrgence(): ?string
    {
        return $this->urgence;
    }

    public function setUrgence(?string $urgence): self
    {
        $this->urgence = $urgence;

        return $this;
    }

    public function getContacturgence(): ?string
    {
        return $this->contacturgence;
    }

    public function setContacturgence(?string $contacturgence): self
    {
        $this->contacturgence = $contacturgence;

        return $this;
    }

    public function getBranche(): ?string
    {
        return $this->branche;
    }

    public function setBranche(?string $branche): self
    {
        $this->branche = $branche;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): self
    {
        $this->fonction = $fonction;

        return $this;
    }

    public function getCotisation(): ?string
    {
        return $this->cotisation;
    }

    public function setCotisation(?string $cotisation): self
    {
        $this->cotisation = $cotisation;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getStatut(): ?Statut
    {
        return $this->statut;
    }

    public function setStatut(?Statut $statut): self
    {
        $this->statut = $statut;

        return $this;
    }


}
