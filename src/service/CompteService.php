<?php

namespace App\Service;

use App\Core\App;
use App\Repository\CompteRepository;
class CompteService
{
    private CompteRepository $compteRepository;

    public function __construct()
    {
        $this->compteRepository = App::getDependency('compteRepository');
    }

    public function getSolde(int $userId): ?array
    {
        return $this->compteRepository->getSoldeByUserId($userId);
    }
}