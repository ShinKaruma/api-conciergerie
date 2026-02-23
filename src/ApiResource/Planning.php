<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\PlanningController;
use App\Dto\PlanningDayDto;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/planning',
            controller: PlanningController::class,
            normalizationContext: ['groups' => ['planning:read']]
        )
    ],
    output: PlanningDayDto::class
)]
class Planning {}