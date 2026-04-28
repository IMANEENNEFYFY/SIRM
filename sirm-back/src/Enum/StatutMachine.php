<?php
namespace App\Enum;

enum StatutMachine: string
{
    case DISPONIBLE = 'DISPONIBLE'; // Vert
    case EN_COURS = 'EN_COURS';     // Orange
    case FAIT = 'FAIT';              // Rouge (test complété)
    case EN_MAINTENANCE = 'EN_MAINTENANCE';
    case HORS_SERVICE = 'HORS_SERVICE';
}
