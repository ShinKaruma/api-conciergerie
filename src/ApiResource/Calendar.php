<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\CalendarController;
use App\Dto\CalendarEventDto;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/calendar',
            controller: CalendarController::class,
            read: false,
            normalizationContext: ['groups' => ['calendar:read']]
        )
    ],
    output: CalendarEventDto::class
)]
class Calendar
{
}