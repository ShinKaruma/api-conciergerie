<?php

namespace App\Controller;

use App\Dto\PlanningDayDto;
use App\Dto\PlanningEventDto;
use App\Repository\LocationRepository;
use App\Repository\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class PlanningController extends AbstractController
{
    public function __construct(
        private LocationRepository $locationRepository,
    ) {}

    public function __invoke(): JsonResponse
    {
        $user = $this->getUser();
        $today = new \DateTimeImmutable('today');

        $locations = $this->locationRepository
            ->findUpcomingForConciergerie($user);

        $days = [];

        foreach ($locations as $location) {

            // ARRIVEE
            if ($location->getDateDebut() >= $today) {
                $this->addEvent(
                    $days,
                    $location->getDateDebut(),
                    $location,
                    'ARRIVEE'
                );
            }

            // DEPART
            if ($location->getDateFin() >= $today) {
                $this->addEvent(
                    $days,
                    $location->getDateFin(),
                    $location,
                    'DEPART'
                );
            }
        }

        ksort($days);

        return $this->json(
            array_values($days),
            200,
            [],
            ['groups' => ['planning:read']]
        );
    }

    private function addEvent(
        array &$days,
        \DateTimeInterface $date,
        $location,
        string $type
    ): void {
        $key = $date->format('Y-m-d');

        if (!isset($days[$key])) {
            $dayDto = new PlanningDayDto();
            $dayDto->date = $key;
            $days[$key] = $dayDto;
        }

        $event = new PlanningEventDto();
        $event->type = $type;
        $event->location = $location;

        $days[$key]->events[] = $event;
    }
}