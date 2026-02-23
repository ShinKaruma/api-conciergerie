<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Location;

class PlanningEventDto{
    
    #[Groups(['planning:read'])]
    public string $date;

    #[Groups(['planning:read'])]
    public string $type;

    #[Groups(['planning:read'])]
    public Location $location;

    
}