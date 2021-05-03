<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200218105110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE workings (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, decided_by INT DEFAULT NULL, start_at DATE NOT NULL, period_start_at LONGTEXT NOT NULL, end_at DATE NOT NULL, period_end_at LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, decision DATETIME DEFAULT NULL, is_accepted TINYINT(1) DEFAULT NULL, description_working_reject LONGTEXT DEFAULT NULL, report_request TINYINT(1) DEFAULT \'0\' NOT NULL, report LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, INDEX IDX_F196123F8C03F15C (employee_id), INDEX IDX_F196123F239DAEC (decided_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE workings ADD CONSTRAINT FK_F196123F8C03F15C FOREIGN KEY (employee_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE workings ADD CONSTRAINT FK_F196123F239DAEC FOREIGN KEY (decided_by) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE workings');
    }
}
