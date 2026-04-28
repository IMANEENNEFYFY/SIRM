<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260410100820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dicom_non_reconcilie ADD orthanc_instance_id VARCHAR(255) NOT NULL, ADD study_uid VARCHAR(255) NOT NULL, ADD reconcilied_by_patient_id INT DEFAULT NULL, DROP orthanc_study_id, DROP modalite, CHANGE patient_id_dicom patient_id_dicom VARCHAR(100) NOT NULL, CHANGE statut modality VARCHAR(20) NOT NULL, CHANGE date_reception received_at DATETIME NOT NULL');
        $this->addSql('DROP INDEX UNIQ_3B86243FF6058FB5 ON resultat_dicom');
        $this->addSql('ALTER TABLE resultat_dicom ADD study_uid VARCHAR(255) NOT NULL, ADD modality VARCHAR(20) NOT NULL, ADD created_at DATETIME NOT NULL, DROP orthanc_study_id, DROP modalite, CHANGE orthanc_instance_id orthanc_instance_id VARCHAR(255) NOT NULL, CHANGE examen_id examen_id INT DEFAULT NULL, CHANGE date_reception study_date DATETIME NOT NULL, CHANGE nb_images patient_id INT NOT NULL');
        $this->addSql('ALTER TABLE resultat_dicom ADD CONSTRAINT FK_3B86243F6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_3B86243F6B899279 ON resultat_dicom (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dicom_non_reconcilie ADD orthanc_study_id VARCHAR(64) NOT NULL, ADD modalite VARCHAR(10) NOT NULL, DROP orthanc_instance_id, DROP study_uid, DROP reconcilied_by_patient_id, CHANGE patient_id_dicom patient_id_dicom VARCHAR(64) NOT NULL, CHANGE received_at date_reception DATETIME NOT NULL, CHANGE modality statut VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE resultat_dicom DROP FOREIGN KEY FK_3B86243F6B899279');
        $this->addSql('DROP INDEX IDX_3B86243F6B899279 ON resultat_dicom');
        $this->addSql('ALTER TABLE resultat_dicom ADD orthanc_study_id VARCHAR(64) NOT NULL, ADD modalite VARCHAR(10) NOT NULL, ADD date_reception DATETIME NOT NULL, DROP study_uid, DROP modality, DROP study_date, DROP created_at, CHANGE orthanc_instance_id orthanc_instance_id VARCHAR(64) NOT NULL, CHANGE examen_id examen_id INT NOT NULL, CHANGE patient_id nb_images INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B86243FF6058FB5 ON resultat_dicom (orthanc_study_id)');
    }
}
