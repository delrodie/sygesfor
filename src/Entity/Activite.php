<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActiviteRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Activite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $debutActivite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $finActivite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $debutPeriode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $finPeriode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDebutActivite(): ?string
    {
        return $this->debutActivite;
    }

    public function setDebutActivite(?string $debutActivite): self
    {
        $this->debutActivite = $debutActivite;

        return $this;
    }

    public function getFinActivite(): ?string
    {
        return $this->finActivite;
    }

    public function setFinActivite(?string $finActivite): self
    {
        $this->finActivite = $finActivite;

        return $this;
    }

    public function getDebutPeriode(): ?string
    {
        return $this->debutPeriode;
    }

    public function setDebutPeriode(?string $debutPeriode): self
    {
        $this->debutPeriode = $debutPeriode;

        return $this;
    }

    public function getFinPeriode(): ?string
    {
        return $this->finPeriode;
    }

    public function setFinPeriode(?string $finPeriode): self
    {
        $this->finPeriode = $finPeriode;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
	
	/**
	 * @ORM\PrePersist
	 */
	public function setCreatedAtValue()
	{
		$this->createdAt = new \DateTime();
	}
}
