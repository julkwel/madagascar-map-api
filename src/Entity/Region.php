<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['region:read', 'commune:read', 'district:read', 'fokontany:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['id' => 'exact', 'name' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['name'], arguments: ['orderParameterName' => 'order'])]
class Region
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['region:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['region:read', 'commune:read', 'district:read', 'fokontany:read'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: District::class)]
    private Collection $districts;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Commune::class)]
    private Collection $communes;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: Fokontany::class)]
    private Collection $fokontanies;

    public function __construct()
    {
        $this->districts = new ArrayCollection();
        $this->communes = new ArrayCollection();
        $this->fokontanies = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
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
     * @return Collection<int, District>
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    public function addDistrict(District $district): static
    {
        if (!$this->districts->contains($district)) {
            $this->districts->add($district);
            $district->setRegion($this);
        }

        return $this;
    }

    public function removeDistrict(District $district): static
    {
        if ($this->districts->removeElement($district)) {
            // set the owning side to null (unless already changed)
            if ($district->getRegion() === $this) {
                $district->setRegion(null);
            }
        }

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
            $commune->setRegion($this);
        }

        return $this;
    }

    public function removeCommune(Commune $commune): static
    {
        if ($this->communes->removeElement($commune)) {
            // set the owning side to null (unless already changed)
            if ($commune->getRegion() === $this) {
                $commune->setRegion(null);
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
            $fokontany->setRegion($this);
        }

        return $this;
    }

    public function removeFokontany(Fokontany $fokontany): static
    {
        if ($this->fokontanies->removeElement($fokontany)) {
            // set the owning side to null (unless already changed)
            if ($fokontany->getRegion() === $this) {
                $fokontany->setRegion(null);
            }
        }

        return $this;
    }
}
