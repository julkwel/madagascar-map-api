<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CodePostaleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CodePostaleRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new GetCollection()
    ],
    normalizationContext: ['groups' => ['code:read']],
)]
#[ApiFilter(SearchFilter::class, properties: ['codePostal' => 'ipartial', 'ville' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['codePostal'], arguments: ['orderParameterName' => 'order'])]
class CodePostale
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['code:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 5)]
    #[Groups(['code:read'])]
    private ?string $codePostal = null;

    #[ORM\Column(length: 255)]
    #[Groups(['code:read'])]
    private ?string $ville = null;

    #[ORM\ManyToOne]
    #[Groups(['code:read'])]
    private ?Region $region = null;

    #[ORM\ManyToOne(inversedBy: 'codePostales')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['code:read'])]
    private ?Province $province = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

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

    public function getProvince(): ?Province
    {
        return $this->province;
    }

    public function setProvince(?Province $province): static
    {
        $this->province = $province;

        return $this;
    }
}
