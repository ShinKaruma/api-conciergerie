<?php

namespace App\Command;

use App\Entity\Conciergerie;
use App\Entity\User;
use App\Entity\Concierge;
use App\Entity\Proprietaire;
use App\Entity\Appartement;
use App\Entity\Location;
use App\Entity\Locataire;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:generate-dataset',
    description: 'Generate full dataset for development'
)]
class GenerateDatasetCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('conciergeries', null, InputOption::VALUE_OPTIONAL, 'Number of conciergeries', 2)
            ->addOption('owners', null, InputOption::VALUE_OPTIONAL, 'Owners per conciergerie', 4)
            ->addOption('apartments', null, InputOption::VALUE_OPTIONAL, 'Apartments per owner', 3);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $faker = Factory::create('fr_FR');

        $nbConciergeries = (int)$input->getOption('conciergeries');
        $nbOwners = (int)$input->getOption('owners');
        $nbApartments = (int)$input->getOption('apartments');

        for ($c = 0; $c < $nbConciergeries; $c++) {

            $conciergerie = new Conciergerie();
            $conciergerie->setNom($faker->company());
            $conciergerie->setCodeSynchro(strtoupper($faker->bothify('CO###')));
            $this->em->persist($conciergerie);

            // Concierge user
            $userConcierge = new User();
            $userConcierge->setEmail("concierge$c@test.com");
            $userConcierge->setNom($faker->lastName());
            $userConcierge->setPrenom($faker->firstName());
            $userConcierge->setTelephone($faker->phoneNumber());
            $userConcierge->setConciergerie($conciergerie);
            $userConcierge->setType("CONCIERGE");
            $userConcierge->setPassword(
                $this->hasher->hashPassword($userConcierge, 'password')
            );
            $this->em->persist($userConcierge);

            $concierge = new Concierge();
            $concierge->setUser($userConcierge);
            $this->em->persist($concierge);

            // Owners
            for ($o = 0; $o < $nbOwners; $o++) {

                $userOwner = new User();
                $userOwner->setEmail("owner_{$c}_{$o}@test.com");
                $userOwner->setNom($faker->lastName());
                $userOwner->setPrenom($faker->firstName());
                $userOwner->setConciergerie($conciergerie);
                $userOwner->setType("PROPRIETAIRE");
                $userOwner->setTelephone($faker->phoneNumber());
                $userOwner->setPassword(
                    $this->hasher->hashPassword($userOwner, 'password')
                );
                $this->em->persist($userOwner);

                $proprietaire = new Proprietaire();
                $proprietaire->setUser($userOwner);
                $proprietaire->setCouleur($faker->hexColor());
                $this->em->persist($proprietaire);

                // Apartments
                for ($a = 0; $a < $nbApartments; $a++) {

                    $appartement = new Appartement();
                    $appartement->setNom("Appartement ".$faker->streetName());
                    $appartement->setLieu($faker->address());
                    $appartement->setNumero((string)$faker->buildingNumber());
                    $appartement->setCodeCle((string)$faker->randomNumber(4));
                    $appartement->setCodePorte((string)$faker->randomNumber(4));
                    $appartement->setNbKitDispo($faker->numberBetween(0,5));
                    $appartement->setProprietaire($proprietaire);
                    $this->em->persist($appartement);

                    // Locations
                    $nbLocations = rand(0,6);

                    for ($l = 0; $l < $nbLocations; $l++) {

                        $location = new Location();

                        $start = $faker->dateTimeBetween('-3 months', '+3 months');
                        $end = (clone $start)->modify('+'.rand(2,14).' days');

                        $location->setDateDebut($start);
                        $location->setDateFin($end);
                        $location->setAppartement($appartement);

                        // 70% avec locataire
                        if (rand(0,100) < 70) {
                            $locataire = new Locataire();

                            $locataire->setNom($faker->lastName());
                            $locataire->setPrenom($faker->firstName());
                            $locataire->setTelephone($faker->phoneNumber());

                            $location->setLocataire($locataire);
                        }

                        $this->em->persist($location);
                    }
                }
            }
        }

        $this->em->flush();

        $output->writeln('<info>Dataset generated successfully.</info>');

        return Command::SUCCESS;
    }
}