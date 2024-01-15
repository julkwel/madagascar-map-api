<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ProvinceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProvinceRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['province:read', 'code:read']],
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['name'], arguments: ['orderParameterName' => 'order'])]
class Province
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['province:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['province:read', 'code:read'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'province', targetEntity: CodePostale::class)]
    private Collection $codePostales;

    public function __construct()
    {
        $this->codePostales = new ArrayCollection();
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
     * @return Collection<int, CodePostale>
     */
    public function getCodePostales(): Collection
    {
        return $this->codePostales;
    }

    public function addCodePostale(CodePostale $codePostale): static
    {
        if (!$this->codePostales->contains($codePostale)) {
            $this->codePostales->add($codePostale);
            $codePostale->setProvince($this);
        }

        return $this;
    }

    public function removeCodePostale(CodePostale $codePostale): static
    {
        if ($this->codePostales->removeElement($codePostale)) {
            // set the owning side to null (unless already changed)
            if ($codePostale->getProvince() === $this) {
                $codePostale->setProvince(null);
            }
        }

        return $this;
    }
}
