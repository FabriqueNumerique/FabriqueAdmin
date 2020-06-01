<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200531120252 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contacts (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, INDEX IDX_33401573A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE apprenant (id INT NOT NULL, offres_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, adresse VARCHAR(255) NOT NULL, ville VARCHAR(255) NOT NULL, git VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_C4EB462E6C83CD9F (offres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reseau (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, INDEX IDX_CDE52CB8C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, duree VARCHAR(255) NOT NULL, nombre_heure INT NOT NULL, diplome VARCHAR(255) NOT NULL, modalites VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promo_appre (id INT AUTO_INCREMENT NOT NULL, promotion_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_9446D319139DF194 (promotion_id), INDEX IDX_9446D319C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retard (id INT AUTO_INCREMENT NOT NULL, promo_appre_id INT NOT NULL, date DATE NOT NULL, nombre_heure INT NOT NULL, justifie VARCHAR(255) NOT NULL, INDEX IDX_5C64DDBD401C2F22 (promo_appre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal INT NOT NULL, ville VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, mobile VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation_competence (evaluation_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_7ED32F8D456C5646 (evaluation_id), INDEX IDX_7ED32F8D15761DAB (competence_id), PRIMARY KEY(evaluation_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offres (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, intitule VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, cahier_des_charges VARCHAR(255) DEFAULT NULL, INDEX IDX_C6AC3544A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, formation_id INT DEFAULT NULL, annee INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, commentaires VARCHAR(255) DEFAULT NULL, INDEX IDX_C11D7DD15200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, intitule VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, sous_categorie VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE absence (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, promotion_id INT DEFAULT NULL, promo_appre_id INT NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, justifie VARCHAR(255) NOT NULL, INDEX IDX_765AE0C9C5697D6D (apprenant_id), INDEX IDX_765AE0C9139DF194 (promotion_id), INDEX IDX_765AE0C9401C2F22 (promo_appre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contacts ADD CONSTRAINT FK_33401573A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462E6C83CD9F FOREIGN KEY (offres_id) REFERENCES offres (id)');
        $this->addSql('ALTER TABLE apprenant ADD CONSTRAINT FK_C4EB462EBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reseau ADD CONSTRAINT FK_CDE52CB8C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE promo_appre ADD CONSTRAINT FK_9446D319139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE promo_appre ADD CONSTRAINT FK_9446D319C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE retard ADD CONSTRAINT FK_5C64DDBD401C2F22 FOREIGN KEY (promo_appre_id) REFERENCES promo_appre (id)');
        $this->addSql('ALTER TABLE evaluation_competence ADD CONSTRAINT FK_7ED32F8D456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluation_competence ADD CONSTRAINT FK_7ED32F8D15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offres ADD CONSTRAINT FK_C6AC3544A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE promotion ADD CONSTRAINT FK_C11D7DD15200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9C5697D6D FOREIGN KEY (apprenant_id) REFERENCES apprenant (id)');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('ALTER TABLE absence ADD CONSTRAINT FK_765AE0C9401C2F22 FOREIGN KEY (promo_appre_id) REFERENCES promo_appre (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE reseau DROP FOREIGN KEY FK_CDE52CB8C5697D6D');
        $this->addSql('ALTER TABLE promo_appre DROP FOREIGN KEY FK_9446D319C5697D6D');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9C5697D6D');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462EBF396750');
        $this->addSql('ALTER TABLE promotion DROP FOREIGN KEY FK_C11D7DD15200282E');
        $this->addSql('ALTER TABLE retard DROP FOREIGN KEY FK_5C64DDBD401C2F22');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9401C2F22');
        $this->addSql('ALTER TABLE contacts DROP FOREIGN KEY FK_33401573A4AEAFEA');
        $this->addSql('ALTER TABLE offres DROP FOREIGN KEY FK_C6AC3544A4AEAFEA');
        $this->addSql('ALTER TABLE evaluation_competence DROP FOREIGN KEY FK_7ED32F8D456C5646');
        $this->addSql('ALTER TABLE apprenant DROP FOREIGN KEY FK_C4EB462E6C83CD9F');
        $this->addSql('ALTER TABLE promo_appre DROP FOREIGN KEY FK_9446D319139DF194');
        $this->addSql('ALTER TABLE absence DROP FOREIGN KEY FK_765AE0C9139DF194');
        $this->addSql('ALTER TABLE evaluation_competence DROP FOREIGN KEY FK_7ED32F8D15761DAB');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE apprenant');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE reseau');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE promo_appre');
        $this->addSql('DROP TABLE retard');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE evaluation');
        $this->addSql('DROP TABLE evaluation_competence');
        $this->addSql('DROP TABLE offres');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE absence');
    }
}
