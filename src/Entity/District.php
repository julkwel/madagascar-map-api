<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\DistrictRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DistrictRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['district:read', 'commune:read', 'fokontany:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['name'], arguments: ['orderParameterName' => 'order'])]
class District
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['district:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['district:read', 'commune:read', 'fokontany:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'districts')]
    #[Groups(['district:read'])]
    private ?Region $region = null;

    #[ORM\OneToMany(mappedBy: 'district', targetEntity: Commune::class)]
    private Collection $communes;

    #[ORM\OneToMany(mappedBy: 'district', targetEntity: Fokontany::class)]
    private Collection $fokontanies;

    public function __construct()
    {
        $this->communes = new ArrayCollection();
        $this->fokontanies = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Commune>
     */
    public function getCommunes(): Collection
    {
        return $this->communes;
    }

    public function addCommune(Commune $commune): static
    {
        if (!$this->communes->contains($commune)) {
            $this->communes->add($commune);
            $commune->setDistrict($this);
        }

        return $this;
    }

    public function removeCommune(Commune $commune): static
    {
        if ($this->communes->removeElement($commune)) {
            // set the owning side to null (unless already changed)
            if ($commune->getDistrict() === $this) {
                $commune->setDistrict(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fokontany>
     */
    public function getFokontanies(): Collection
    {
        return $this->fokontanies;
    }

    public function addFokontany(Fokontany $fokontany): static
    {
        if (!$this->fokontanies->contains($fokontany)) {
            $this->fokontanies->add($fokontany);
            $fokontany->setDistrict($this);
        }

        return $this;
    }

    public function removeFokontany(Fokontany $fokontany): static
    {
        if ($this->fokontanies->removeElement($fokontany)) {
            // set the owning side to null (unless already changed)
            if ($fokontany->getDistrict() === $this) {
                $fokontany->setDistrict(null);
            }
        }

        return $this;
    }
}
