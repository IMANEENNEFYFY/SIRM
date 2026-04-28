<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add machine status management columns (enum-compatible)
 */
final class Version20260422140000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add machine status tracking columns';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE machine MODIFY statut VARCHAR(50) NOT NULL DEFAULT "DISPONIBLE"');
        $this->addSql('ALTER TABLE machine ADD date_debut DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE machine ADD date_fin DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE machine ADD description VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE machine DROP date_debut');
        $this->addSql('ALTER TABLE machine DROP date_fin');
        $this->addSql('ALTER TABLE machine DROP description');
        $this->addSql('ALTER TABLE machine MODIFY statut VARCHAR(20) NOT NULL DEFAULT "DISPONIBLE"');
    }
}
