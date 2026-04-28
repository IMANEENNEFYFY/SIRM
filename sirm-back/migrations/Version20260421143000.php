<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add the missing examen.date_modif_statut column expected by the current entity.
 */
final class Version20260421143000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add missing date_modif_statut column to examen';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE examen ADD date_modif_statut DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE examen DROP date_modif_statut');
    }
}