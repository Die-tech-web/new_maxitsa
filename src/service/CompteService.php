<?php

namespace App\Service;

use App\Repository\CompteRepository;
class CompteService
{
    private CompteRepository $compteRepository;

    public function __construct()
    {
        $this->compteRepository = new CompteRepository();
    }

    public function getSolde(int $userId): ?array
    {
        return $this->compteRepository->getSoldeByUserId($userId);
    }
}