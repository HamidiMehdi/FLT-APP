<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200120122224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annual_interviews DROP deleted_at');
        $this->addSql('ALTER TABLE bilans_annual_interview DROP deleted_at');
        $this->addSql('ALTER TABLE evaluation_formation_annual_interview DROP deleted_at');
        $this->addSql('ALTER TABLE formation_annual_interview DROP deleted_at');
        $this->addSql('ALTER TABLE formation_desired_annual_interview DROP deleted_at');
        $this->addSql('ALTER TABLE goal_annual_interview DROP deleted_at');
        $this->addSql('ALTER TABLE know_how_annual_interview DROP deleted_at');
        $this->addSql('ALTER TABLE know_make_annual_interview DROP deleted_at');
        $this->addSql('ALTER TABLE pro_interviews DROP deleted_at');
        $this->addSql('DROP INDEX IDX_1483A5E94AF38FD1 ON users');
        $this->addSql('ALTER TABLE users DROP deleted_at');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE annual_interviews ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE bilans_annual_interview ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE evaluation_formation_annual_interview ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE formation_annual_interview ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE formation_desired_annual_interview ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE goal_annual_interview ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE know_how_annual_interview ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE know_make_annual_interview ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE pro_interviews ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_1483A5E94AF38FD1 ON users (deleted_at)');
    }
}
