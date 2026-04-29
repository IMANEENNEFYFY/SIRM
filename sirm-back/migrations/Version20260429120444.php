<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429120444 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove DICOM reconciliation status database default';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dicom_non_reconcilie CHANGE statut statut VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE dicom_non_reconcilie CHANGE statut statut VARCHAR(50) NOT NULL DEFAULT "EN_ATTENTE"');
    }
}
