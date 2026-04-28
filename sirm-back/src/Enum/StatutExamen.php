<?php
namespace App\Enum;

enum StatutExamen: string
{
    case PLANIFIE  = 'PLANIFIE';
    case EN_COURS  = 'EN_COURS';
    case RECU      = 'RECU';
    case ANNULE    = 'ANNULE';
    case EN_ATTENTE = 'EN_ATTENTE';
}