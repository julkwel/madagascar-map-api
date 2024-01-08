<?php
/**
 * @author julienrajerison5@gmail.com jul
 *
 * Date : 08/01/2024
 */

namespace App\Manager;

use App\Entity\Commune;
use App\Entity\District;
use App\Entity\Fokontany;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImportDataManager
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly ParameterBagInterface $parameterBag)
    {
    }

    /**
     * @param SymfonyStyle $style
     *
     * @return void
     */
    public function importData(SymfonyStyle $style): void
    {
        $progressBar = $style->createProgressBar();
        $payloadFile = $this->parameterBag->get('kernel.project_dir').'/in/mada_fokontany_payload.csv';
        $ressource = fopen($payloadFile, 'r+');

        $i = 0;
        while (false !== ($line = fgetcsv($ressource, 1000, "\t"))) {
            $progressBar->advance();
            if ($i === 0) {
                $i++;
                continue;
            }

            $region = $this->entityManager->getRepository(Region::class)->findOneBy(['name' => $line[0]]);
            if (!$region) {
                $region = new Region();
                $region->setName($line[0]);

                $this->entityManager->persist($region);
                $this->entityManager->flush();
            }

            $district = $this->entityManager->getRepository(District::class)->findOneBy(['name' => $line[1], 'region' => $region]);
            if (!$district) {
                $district = new District();
                $district->setName($line[1]);
                $district->setRegion($region);

                $this->entityManager->persist($district);
                $this->entityManager->flush();
            }

            $commune = $this->entityManager->getRepository(Commune::class)->findOneBy(['name' => $line[2], 'district' => $district]);
            if (!$commune) {
                $commune = new Commune();
                $commune->setName($line[2]);
                $commune->setDistrict($district);
                $commune->setRegion($region);

                $this->entityManager->persist($commune);
                $this->entityManager->flush();
            }

            $fokontany = $this->entityManager->getRepository(Fokontany::class)->findOneBy(['name' => $line[3], 'commune' => $commune]);
            if (!$fokontany) {
                $fokontany = new Fokontany();
                $fokontany->setCommune($commune);
                $fokontany->setRegion($region);
                $fokontany->setDistrict($district);
                $fokontany->setName($line[3]);

                $this->entityManager->persist($fokontany);
                $this->entityManager->flush();
            }
        }

        $progressBar->finish();
    }
}