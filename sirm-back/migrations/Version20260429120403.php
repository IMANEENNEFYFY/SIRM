<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260429120403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Align DICOM result and reconciliation tables with current entities';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE resultat_dicom DROP FOREIGN KEY FK_3B86243F5C8659A');
        $this->addSql('ALTER TABLE resultat_dicom DROP FOREIGN KEY FK_3B86243F6B899279');
        $this->addSql('DROP INDEX UNIQ_3B86243F5C8659A ON resultat_dicom');
        $this->addSql('DROP INDEX IDX_3B86243F6B899279 ON resultat_dicom');
        $this->addSql('ALTER TABLE resultat_dicom ADD series_instance_uid VARCHAR(255) DEFAULT NULL, ADD orthanc_url VARCHAR(255) DEFAULT NULL, DROP study_date, DROP patient_id, CHANGE study_uid study_instance_uid VARCHAR(255) NOT NULL, CHANGE created_at received_at DATETIME NOT NULL, CHANGE modality modality VARCHAR(255) DEFAULT NULL, CHANGE examen_id examen_id INT NOT NULL');
        $this->addSql('CREATE INDEX IDX_3B86243F5C8659A ON resultat_dicom (examen_id)');
        $this->addSql('ALTER TABLE resultat_dicom ADD CONSTRAINT FK_3B86243F5C8659A FOREIGN KEY (examen_id) REFERENCES examen (id)');

        $this->addSql('ALTER TABLE dicom_non_reconcilie ADD patient_nom_dicom VARCHAR(255) DEFAULT NULL, ADD metadonnees_brutes JSON DEFAULT NULL, ADD statut VARCHAR(50) NOT NULL DEFAULT "EN_ATTENTE", CHANGE study_uid study_instance_uid VARCHAR(255) DEFAULT NULL, CHANGE patient_id_dicom patient_id_dicom VARCHAR(255) DEFAULT NULL, CHANGE modality modality VARCHAR(255) DEFAULT NULL, CHANGE reconcilied_by_patient_id examen_reconcilie_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_6899FBDFA7B8B7B8 ON dicom_non_reconcilie (examen_reconcilie_id)');
        $this->addSql('ALTER TABLE dicom_non_reconcilie ADD CONSTRAINT FK_6899FBDFA7B8B7B8 FOREIGN KEY (examen_reconcilie_id) REFERENCES examen (id)');

        $this->addSql('ALTER TABLE machine CHANGE statut statut VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE machine CHANGE statut statut VARCHAR(50) NOT NULL DEFAULT "DISPONIBLE"');

        $this->addSql('ALTER TABLE dicom_non_reconcilie DROP FOREIGN KEY FK_6899FBDFA7B8B7B8');
        $this->addSql('DROP INDEX IDX_6899FBDFA7B8B7B8 ON dicom_non_reconcilie');
        $this->addSql('ALTER TABLE dicom_non_reconcilie ADD study_uid VARCHAR(255) NOT NULL, DROP patient_nom_dicom, DROP study_instance_uid, DROP metadonnees_brutes, DROP statut, CHANGE patient_id_dicom patient_id_dicom VARCHAR(100) NOT NULL, CHANGE modality modality VARCHAR(20) NOT NULL, CHANGE examen_reconcilie_id reconcilied_by_patient_id INT DEFAULT NULL');

        $this->addSql('ALTER TABLE resultat_dicom DROP FOREIGN KEY FK_3B86243F5C8659A');
        $this->addSql('DROP INDEX IDX_3B86243F5C8659A ON resultat_dicom');
        $this->addSql('ALTER TABLE resultat_dicom ADD study_date DATETIME NOT NULL, ADD patient_id INT NOT NULL, ADD created_at DATETIME NOT NULL, DROP series_instance_uid, DROP orthanc_url, CHANGE study_instance_uid study_uid VARCHAR(255) NOT NULL, CHANGE received_at created_at DATETIME NOT NULL, CHANGE modality modality VARCHAR(20) NOT NULL, CHANGE examen_id examen_id INT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B86243F5C8659A ON resultat_dicom (examen_id)');
        $this->addSql('CREATE INDEX IDX_3B86243F6B899279 ON resultat_dicom (patient_id)');
        $this->addSql('ALTER TABLE resultat_dicom ADD CONSTRAINT FK_3B86243F5C8659A FOREIGN KEY (examen_id) REFERENCES examen (id)');
        $this->addSql('ALTER TABLE resultat_dicom ADD CONSTRAINT FK_3B86243F6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
    }
}
