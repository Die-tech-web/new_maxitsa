<?php
namespace App\Entity;

enum TypeUser: string
{
    case Client = 'client';
    case ServiceCommercial = 'serviceCommercial';
}