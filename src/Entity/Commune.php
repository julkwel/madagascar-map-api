<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CommuneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CommuneRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    normalizationContext: ['groups' => ['commune:read', 'fokontany:read']]
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['name'], arguments: ['orderParameterName' => 'order'])]
class Commune
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['commune:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['commune:read', 'fokontany:read'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'communes')]
    #[Groups(['commune:read'])]
    private ?Region $region = null;

    #[ORM\ManyToOne(inversedBy: 'communes')]
    #[Groups(['commune:read'])]
    private ?District $district = null;

    #[ORM\OneToMany(mappedBy: 'commune', targetEntity: Fokontany::class)]
    private Collection $fokontanies;

    public function __construct()
    {
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): static
    {
        $this->district = $district;

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
            $fokontany->setCommune($this);
        }

        return $this;
    }

    public function removeFokontany(Fokontany $fokontany): static
    {
        if ($this->fokontanies->removeElement($fokontany)) {
            // set the owning side to null (unless already changed)
            if ($fokontany->getCommune() === $this) {
                $fokontany->setCommune(null);
            }
        }

        return $this;
    }
}
