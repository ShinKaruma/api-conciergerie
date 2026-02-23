<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class PlanningDayDto
{
    #[Groups(['planning:read'])]
    public string $date;

    #[Groups(['planning:read'])]
    public array $events = [];
}