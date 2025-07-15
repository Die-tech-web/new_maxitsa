<?php

namespace App\Entity;

enum TypeTransaction: string
{
    case Paiement = 'paiement';
    case Depot = 'depot';
    case Retrait = 'retrait';
}