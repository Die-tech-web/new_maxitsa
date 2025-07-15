<?php

namespace App\Entity;

enum TypeCompte: string
{
    case Principal = 'principal';
    case Secondaire = 'secondaire';
}