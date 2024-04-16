<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329233452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY fk_service_id');
        $this->addSql('DROP TABLE borne');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE reservation_b');
        $this->addSql('DROP TABLE reservation_e');
        $this->addSql('DROP TABLE reservation_voiture');
        $this->addSql('DROP TABLE service_apreslocation');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY admin_ibfk_1');
        $this->addSql('ALTER TABLE admin ADD cin INT NOT NULL, ADD nom VARCHAR(100) NOT NULL, ADD prenom VARCHAR(100) NOT NULL, ADD email VARCHAR(100) NOT NULL, ADD mot_de_passe VARCHAR(100) NOT NULL, ADD date_inscription DATETIME NOT NULL, ADD image VARCHAR(100) NOT NULL, CHANGE id_u id_u INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D7635F8C041 FOREIGN KEY (id_u) REFERENCES utilisateur (id_u)');
        $this->addSql('ALTER TABLE utilisateur CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE prenom prenom VARCHAR(100) NOT NULL, CHANGE date_inscription date_inscription DATETIME NOT NULL, CHANGE image image VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE borne (id INT AUTO_INCREMENT NOT NULL, emplacement VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, capacite INT NOT NULL, etat VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_inst DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evenement (id_event INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, adresseEvenement VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, dateEvenement DATE NOT NULL, placesDispo INT NOT NULL, PRIMARY KEY(id_event)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participation (idp INT AUTO_INCREMENT NOT NULL, ids INT NOT NULL, nbr_de_participant INT NOT NULL, INDEX fk_service_id (ids), PRIMARY KEY(idp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_b (id_r INT AUTO_INCREMENT NOT NULL, id_b INT NOT NULL, id_u INT NOT NULL, date_d DATE DEFAULT NULL, heure_d TIME DEFAULT NULL, date_f DATE DEFAULT NULL, heure_f TIME DEFAULT NULL, INDEX fk_borne_id (id_b), INDEX fk_utilisateur_id (id_u), PRIMARY KEY(id_r)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_e (id_e INT AUTO_INCREMENT NOT NULL, id_event INT NOT NULL, id_u INT NOT NULL, nbrPersonne INT NOT NULL, Commentaire VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fk_event_id (id_event), INDEX fk_evenement_id_event (id_u), PRIMARY KEY(id_e)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation_voiture (id_r INT AUTO_INCREMENT NOT NULL, id_v INT NOT NULL, id_u INT NOT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, INDEX fk_voiture_id (id_v), INDEX fk_user_id (id_u), PRIMARY KEY(id_r)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE service_apreslocation (idservice INT AUTO_INCREMENT NOT NULL, type INT NOT NULL, technicien VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, statut VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, cout DOUBLE PRECISION NOT NULL, INDEX fk_type (type), PRIMARY KEY(idservice)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, typeName VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE voiture (id_v INT AUTO_INCREMENT NOT NULL, marque VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, modele VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, etat VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix_location DOUBLE PRECISION NOT NULL, kilometrage DOUBLE PRECISION NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_v)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT fk_service_id FOREIGN KEY (ids) REFERENCES service_apreslocation (idservice)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D7635F8C041');
        $this->addSql('ALTER TABLE admin DROP cin, DROP nom, DROP prenom, DROP email, DROP mot_de_passe, DROP date_inscription, DROP image, CHANGE id_u id_u INT NOT NULL');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT admin_ibfk_1 FOREIGN KEY (id_u) REFERENCES utilisateur (id_u) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE utilisateur CHANGE nom nom VARCHAR(50) NOT NULL, CHANGE prenom prenom VARCHAR(50) NOT NULL, CHANGE date_inscription date_inscription DATE NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL');
    }
}
