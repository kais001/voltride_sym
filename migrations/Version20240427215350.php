<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240427215350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY participation_ibfk_1');
        $this->addSql('DROP INDEX ids ON participation');
        $this->addSql('ALTER TABLE participation ADD id INT AUTO_INCREMENT NOT NULL, CHANGE idp idp INT NOT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE service_apreslocation DROP FOREIGN KEY fk_type');
        $this->addSql('ALTER TABLE service_apreslocation CHANGE type type INT DEFAULT NULL');
        $this->addSql('DROP INDEX fk_type ON service_apreslocation');
        $this->addSql('CREATE INDEX IDX_4BF6F39B8CDE5729 ON service_apreslocation (type)');
        $this->addSql('ALTER TABLE service_apreslocation ADD CONSTRAINT fk_type FOREIGN KEY (type) REFERENCES type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE participation MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON participation');
        $this->addSql('ALTER TABLE participation DROP id, CHANGE idp idp INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT participation_ibfk_1 FOREIGN KEY (ids) REFERENCES service_apreslocation (idservice)');
        $this->addSql('CREATE INDEX ids ON participation (ids)');
        $this->addSql('ALTER TABLE participation ADD PRIMARY KEY (idp)');
        $this->addSql('ALTER TABLE service_apreslocation DROP FOREIGN KEY FK_4BF6F39B8CDE5729');
        $this->addSql('ALTER TABLE service_apreslocation CHANGE type type INT NOT NULL');
        $this->addSql('DROP INDEX idx_4bf6f39b8cde5729 ON service_apreslocation');
        $this->addSql('CREATE INDEX fk_type ON service_apreslocation (type)');
        $this->addSql('ALTER TABLE service_apreslocation ADD CONSTRAINT FK_4BF6F39B8CDE5729 FOREIGN KEY (type) REFERENCES type (id)');
    }
}
