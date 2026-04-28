<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402082853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE audit_log (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(100) NOT NULL, date_heure DATETIME NOT NULL, adresse_ip VARCHAR(45) DEFAULT NULL, details LONGTEXT DEFAULT NULL, utilisateur_id INT DEFAULT NULL, INDEX IDX_F6E1C0F5FB88E14F (utilisateur_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE dicom_non_reconcilie (id INT AUTO_INCREMENT NOT NULL, orthanc_study_id VARCHAR(64) NOT NULL, patient_id_dicom VARCHAR(64) NOT NULL, modalite VARCHAR(10) NOT NULL, date_reception DATETIME NOT NULL, statut VARCHAR(20) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE examen (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, type VARCHAR(10) NOT NULL, statut VARCHAR(255) NOT NULL, description VARCHAR(500) DEFAULT NULL, date_modif_statut DATETIME DEFAULT NULL, patient_id INT NOT NULL, machine_id INT NOT NULL, medecin_id INT DEFAULT NULL, INDEX IDX_514C8FEC6B899279 (patient_id), INDEX IDX_514C8FECF6B75B26 (machine_id), INDEX IDX_514C8FEC4F31A84 (medecin_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE machine (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, modalite VARCHAR(10) NOT NULL, ae_title VARCHAR(64) NOT NULL, adresse_ip VARCHAR(45) NOT NULL, statut VARCHAR(20) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, patient_id VARCHAR(20) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, cin VARCHAR(20) NOT NULL, date_naissance DATE NOT NULL, sexe VARCHAR(10) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, actif TINYINT NOT NULL, UNIQUE INDEX UNIQ_1ADAD7EB6B899279 (patient_id), UNIQUE INDEX UNIQ_1ADAD7EBABE530DA (cin), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE resultat_dicom (id INT AUTO_INCREMENT NOT NULL, orthanc_study_id VARCHAR(64) NOT NULL, orthanc_instance_id VARCHAR(64) NOT NULL, modalite VARCHAR(10) NOT NULL, date_reception DATETIME NOT NULL, nb_images INT NOT NULL, examen_id INT NOT NULL, UNIQUE INDEX UNIQ_3B86243FF6058FB5 (orthanc_study_id), UNIQUE INDEX UNIQ_3B86243F5C8659A (examen_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(100) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, actif TINYINT NOT NULL, UNIQUE INDEX UNIQ_1D1C63B3AA08CB10 (login), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE audit_log ADD CONSTRAINT FK_F6E1C0F5FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FEC6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FECF6B75B26 FOREIGN KEY (machine_id) REFERENCES machine (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FEC4F31A84 FOREIGN KEY (medecin_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE resultat_dicom ADD CONSTRAINT FK_3B86243F5C8659A FOREIGN KEY (examen_id) REFERENCES examen (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE audit_log DROP FOREIGN KEY FK_F6E1C0F5FB88E14F');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FEC6B899279');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FECF6B75B26');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FEC4F31A84');
        $this->addSql('ALTER TABLE resultat_dicom DROP FOREIGN KEY FK_3B86243F5C8659A');
        $this->addSql('DROP TABLE audit_log');
        $this->addSql('DROP TABLE dicom_non_reconcilie');
        $this->addSql('DROP TABLE examen');
        $this->addSql('DROP TABLE machine');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE resultat_dicom');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
