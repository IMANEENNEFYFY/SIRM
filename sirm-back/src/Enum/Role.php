<?php
namespace App\Enum;

enum Role: string
{
    case SECRETAIRE = 'ROLE_SECRETAIRE';
    case MEDECIN    = 'ROLE_MEDECIN';
    case TECHNICIEN = 'ROLE_TECHNICIEN';
    case ADMIN      = 'ROLE_ADMIN';
}