<?php

namespace App\Controller;

use App\Dto\CalendarEventDto;
use App\Repository\LocationRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class CalendarController
{
    public function __construct(
        private Security $security,
        private LocationRepository $locationRepository
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        $user = $this->security->getUser();

        if (!$user) {
            throw new \Exception('Unauthorized');
        }

        $from = new \DateTime($request->query->get('from'));
        $to   = new \DateTime($request->query->get('to'));

        $locations = $this->locationRepository
            ->findBetweenForConciergerie($user, $from, $to);

        $events = [];

        foreach ($locations as $location) {

            $dto = new CalendarEventDto();

            $dto->id = $location->getId();
            $dto->start = $location->getDateDebut()->format('Y-m-d');
            $dto->end = $location->getDateFin()->format('Y-m-d');
            $dto->appartementNom = $location->getAppartement()->getNom();
            $dto->proprietaireNom =
                $location->getAppartement()
                    ->getProprietaire()
                    ->getUser()
                    ->getNom()." ".$location->getAppartement()
                    ->getProprietaire()
                    ->getUser()
                    ->getPrenom();

            $dto->proprietaireColor =
                $location->getAppartement()
                    ->getProprietaire()
                    ->getCouleur(); // supposé exister

            $events[] = $dto;
        }

        return new JsonResponse(
            array_values($events),
            200,
            ['groups' => ['calendar:read']]
        );
    }
}