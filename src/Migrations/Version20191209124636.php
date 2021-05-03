<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191209124636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE annual_interviews (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, lead_by INT DEFAULT NULL, manager_id INT DEFAULT NULL, job_title LONGTEXT DEFAULT NULL, current_function LONGTEXT DEFAULT NULL, function_seniority LONGTEXT DEFAULT NULL, affectation VARCHAR(255) DEFAULT NULL, date_last_annual_interview DATE DEFAULT NULL, last_annual_interview_lead_by VARCHAR(255) DEFAULT NULL, previous_evaluation LONGTEXT DEFAULT NULL, interview_date DATE DEFAULT NULL, bilan_avg LONGTEXT DEFAULT NULL, comment_collab_working_env LONGTEXT DEFAULT NULL, comment_manager_working_env VARCHAR(255) DEFAULT NULL, comment_manager_strong_points LONGTEXT DEFAULT NULL, comment_collab_improvement LONGTEXT DEFAULT NULL, comment_manager_improvement LONGTEXT DEFAULT NULL, know_how_avg LONGTEXT DEFAULT NULL, know_make_avg LONGTEXT DEFAULT NULL, know_make_comment LONGTEXT DEFAULT NULL, manager_opigion LONGTEXT DEFAULT NULL, manager_date_signature DATETIME DEFAULT NULL, manager_signature LONGTEXT DEFAULT NULL, is_validated LONGTEXT DEFAULT NULL, employee_date_signature DATETIME DEFAULT NULL, employee_signature LONGTEXT DEFAULT NULL, refuse_signature TINYINT(1) DEFAULT NULL, own_interview_validated DATETIME DEFAULT NULL, interview_validated DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_296AD3268C03F15C (employee_id), INDEX IDX_296AD32680A7F09F (lead_by), INDEX IDX_296AD326783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bilans_annual_interview (id INT AUTO_INCREMENT NOT NULL, annual_interview_id INT NOT NULL, achievement LONGTEXT DEFAULT NULL, grade LONGTEXT DEFAULT NULL, comment_collab LONGTEXT DEFAULT NULL, comment_manager LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_6AA330DB526AF1F4 (annual_interview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluation_formation_annual_interview (id INT AUTO_INCREMENT NOT NULL, annual_interview_id INT NOT NULL, formation LONGTEXT DEFAULT NULL, employee_appreciation LONGTEXT DEFAULT NULL, employee_comment LONGTEXT DEFAULT NULL, manager_appreciation LONGTEXT DEFAULT NULL, manager_comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_C692B204526AF1F4 (annual_interview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_annual_interview (id INT AUTO_INCREMENT NOT NULL, annual_interview_id INT NOT NULL, year LONGTEXT DEFAULT NULL, duratrion LONGTEXT DEFAULT NULL, start LONGTEXT DEFAULT NULL, end LONGTEXT DEFAULT NULL, duration LONGTEXT DEFAULT NULL, organisme LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_62588E42526AF1F4 (annual_interview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_desired_annual_interview (id INT AUTO_INCREMENT NOT NULL, annual_interview_id INT NOT NULL, formation_type LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, applicant LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_B9F55449526AF1F4 (annual_interview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE goal_annual_interview (id INT AUTO_INCREMENT NOT NULL, annual_interview_id INT NOT NULL, wording_goal LONGTEXT DEFAULT NULL, goal_achieve LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_83534ADD526AF1F4 (annual_interview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE know_how_annual_interview (id INT AUTO_INCREMENT NOT NULL, annual_interview_id INT NOT NULL, wording_skill LONGTEXT DEFAULT NULL, grade VARCHAR(255) DEFAULT NULL, collab_comment LONGTEXT DEFAULT NULL, manager_comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_2304B12D526AF1F4 (annual_interview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE know_make_annual_interview (id INT AUTO_INCREMENT NOT NULL, annual_interview_id INT NOT NULL, wording_skill LONGTEXT DEFAULT NULL, grade VARCHAR(255) DEFAULT NULL, collab_comment LONGTEXT DEFAULT NULL, manager_comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_641939F9526AF1F4 (annual_interview_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE managers (id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, manager_id INT DEFAULT NULL, rank INT NOT NULL, INDEX IDX_A949E0068C03F15C (employee_id), INDEX IDX_A949E006783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pro_interviews (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, lead_by INT DEFAULT NULL, manager_id INT DEFAULT NULL, second_manager_id INT DEFAULT NULL, job_title VARCHAR(255) DEFAULT NULL, current_function VARCHAR(255) DEFAULT NULL, function_seniority VARCHAR(255) DEFAULT NULL, affectation VARCHAR(255) DEFAULT NULL, interview_date DATE DEFAULT NULL, evolution_curent_position_desc LONGTEXT DEFAULT NULL, evolution_current_position_expected_time LONGTEXT DEFAULT NULL, change_position_desc LONGTEXT DEFAULT NULL, change_position_expected_time LONGTEXT DEFAULT NULL, skills LONGTEXT DEFAULT NULL, action_envisaged LONGTEXT DEFAULT NULL, formation_wishes TINYINT(1) DEFAULT NULL, formation_wishes_type LONGTEXT DEFAULT NULL, formation_wishes_desc LONGTEXT DEFAULT NULL, formation_wishes_expected_time LONGTEXT DEFAULT NULL, geographic_mobility TINYINT(1) DEFAULT NULL, geographic_mobility_location LONGTEXT DEFAULT NULL, geographic_mobility_expected_time LONGTEXT DEFAULT NULL, group_mobility_location LONGTEXT DEFAULT NULL, group_mobility_expected_time LONGTEXT DEFAULT NULL, manager_opinion VARCHAR(255) DEFAULT NULL, manager_date_signature DATE DEFAULT NULL, manager_signature LONGTEXT DEFAULT NULL, employee_opinion VARCHAR(255) DEFAULT NULL, employee_date_signature DATE DEFAULT NULL, employee_signature LONGTEXT DEFAULT NULL, second_manager_date_signature DATE DEFAULT NULL, second_manager_signature LONGTEXT DEFAULT NULL, own_interview_validated DATETIME DEFAULT NULL, interview_validated DATETIME DEFAULT NULL, accept_second_manager TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_890397228C03F15C (employee_id), INDEX IDX_8903972280A7F09F (lead_by), INDEX IDX_89039722783E3463 (manager_id), INDEX IDX_890397229B60D4F0 (second_manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, roles TINYTEXT DEFAULT NULL COMMENT \'(DC2Type:simple_array)\', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birthday DATE DEFAULT NULL, date_entered DATE DEFAULT NULL, created_at DATETIME NOT NULL, update_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E94AF38FD1 (deleted_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annual_interviews ADD CONSTRAINT FK_296AD3268C03F15C FOREIGN KEY (employee_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE annual_interviews ADD CONSTRAINT FK_296AD32680A7F09F FOREIGN KEY (lead_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE annual_interviews ADD CONSTRAINT FK_296AD326783E3463 FOREIGN KEY (manager_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE bilans_annual_interview ADD CONSTRAINT FK_6AA330DB526AF1F4 FOREIGN KEY (annual_interview_id) REFERENCES annual_interviews (id)');
        $this->addSql('ALTER TABLE evaluation_formation_annual_interview ADD CONSTRAINT FK_C692B204526AF1F4 FOREIGN KEY (annual_interview_id) REFERENCES annual_interviews (id)');
        $this->addSql('ALTER TABLE formation_annual_interview ADD CONSTRAINT FK_62588E42526AF1F4 FOREIGN KEY (annual_interview_id) REFERENCES annual_interviews (id)');
        $this->addSql('ALTER TABLE formation_desired_annual_interview ADD CONSTRAINT FK_B9F55449526AF1F4 FOREIGN KEY (annual_interview_id) REFERENCES annual_interviews (id)');
        $this->addSql('ALTER TABLE goal_annual_interview ADD CONSTRAINT FK_83534ADD526AF1F4 FOREIGN KEY (annual_interview_id) REFERENCES annual_interviews (id)');
        $this->addSql('ALTER TABLE know_how_annual_interview ADD CONSTRAINT FK_2304B12D526AF1F4 FOREIGN KEY (annual_interview_id) REFERENCES annual_interviews (id)');
        $this->addSql('ALTER TABLE know_make_annual_interview ADD CONSTRAINT FK_641939F9526AF1F4 FOREIGN KEY (annual_interview_id) REFERENCES annual_interviews (id)');
        $this->addSql('ALTER TABLE managers ADD CONSTRAINT FK_A949E0068C03F15C FOREIGN KEY (employee_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE managers ADD CONSTRAINT FK_A949E006783E3463 FOREIGN KEY (manager_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pro_interviews ADD CONSTRAINT FK_890397228C03F15C FOREIGN KEY (employee_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pro_interviews ADD CONSTRAINT FK_8903972280A7F09F FOREIGN KEY (lead_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pro_interviews ADD CONSTRAINT FK_89039722783E3463 FOREIGN KEY (manager_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE pro_interviews ADD CONSTRAINT FK_890397229B60D4F0 FOREIGN KEY (second_manager_id) REFERENCES users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE bilans_annual_interview DROP FOREIGN KEY FK_6AA330DB526AF1F4');
        $this->addSql('ALTER TABLE evaluation_formation_annual_interview DROP FOREIGN KEY FK_C692B204526AF1F4');
        $this->addSql('ALTER TABLE formation_annual_interview DROP FOREIGN KEY FK_62588E42526AF1F4');
        $this->addSql('ALTER TABLE formation_desired_annual_interview DROP FOREIGN KEY FK_B9F55449526AF1F4');
        $this->addSql('ALTER TABLE goal_annual_interview DROP FOREIGN KEY FK_83534ADD526AF1F4');
        $this->addSql('ALTER TABLE know_how_annual_interview DROP FOREIGN KEY FK_2304B12D526AF1F4');
        $this->addSql('ALTER TABLE know_make_annual_interview DROP FOREIGN KEY FK_641939F9526AF1F4');
        $this->addSql('ALTER TABLE annual_interviews DROP FOREIGN KEY FK_296AD3268C03F15C');
        $this->addSql('ALTER TABLE annual_interviews DROP FOREIGN KEY FK_296AD32680A7F09F');
        $this->addSql('ALTER TABLE annual_interviews DROP FOREIGN KEY FK_296AD326783E3463');
        $this->addSql('ALTER TABLE managers DROP FOREIGN KEY FK_A949E0068C03F15C');
        $this->addSql('ALTER TABLE managers DROP FOREIGN KEY FK_A949E006783E3463');
        $this->addSql('ALTER TABLE pro_interviews DROP FOREIGN KEY FK_890397228C03F15C');
        $this->addSql('ALTER TABLE pro_interviews DROP FOREIGN KEY FK_8903972280A7F09F');
        $this->addSql('ALTER TABLE pro_interviews DROP FOREIGN KEY FK_89039722783E3463');
        $this->addSql('ALTER TABLE pro_interviews DROP FOREIGN KEY FK_890397229B60D4F0');
        $this->addSql('DROP TABLE annual_interviews');
        $this->addSql('DROP TABLE bilans_annual_interview');
        $this->addSql('DROP TABLE evaluation_formation_annual_interview');
        $this->addSql('DROP TABLE formation_annual_interview');
        $this->addSql('DROP TABLE formation_desired_annual_interview');
        $this->addSql('DROP TABLE goal_annual_interview');
        $this->addSql('DROP TABLE know_how_annual_interview');
        $this->addSql('DROP TABLE know_make_annual_interview');
        $this->addSql('DROP TABLE managers');
        $this->addSql('DROP TABLE pro_interviews');
        $this->addSql('DROP TABLE users');
    }
}
