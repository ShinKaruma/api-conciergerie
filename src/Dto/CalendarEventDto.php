<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;

class CalendarEventDto
{
    #[Groups(['calendar:read'])]
    public int $id;

    #[Groups(['calendar:read'])]
    public string $start;

    #[Groups(['calendar:read'])]
    public string $end;

    #[Groups(['calendar:read'])]
    public string $appartementNom;

    #[Groups(['calendar:read'])]
    public string $proprietaireNom;

    #[Groups(['calendar:read'])]
    public string $proprietaireColor;
}