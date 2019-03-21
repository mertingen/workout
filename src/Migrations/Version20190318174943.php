<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190318174943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exercise_instance (id INT AUTO_INCREMENT NOT NULL, exercise_id INT DEFAULT NULL, plan_day_id INT DEFAULT NULL, duration INT NOT NULL, instance_order INT NOT NULL, INDEX IDX_445C1136E934951A (exercise_id), INDEX IDX_445C1136BD9A5954 (plan_day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan_day (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, plan_day_order INT NOT NULL, INDEX IDX_E94192CDE899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, birth_day DATE NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_plan (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, plan_id INT DEFAULT NULL, is_confirmation TINYINT(1) NOT NULL, INDEX IDX_A7DB17B4A76ED395 (user_id), INDEX IDX_A7DB17B4E899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercise (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, difficulty INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exercise_instance ADD CONSTRAINT FK_445C1136E934951A FOREIGN KEY (exercise_id) REFERENCES exercise (id)');
        $this->addSql('ALTER TABLE exercise_instance ADD CONSTRAINT FK_445C1136BD9A5954 FOREIGN KEY (plan_day_id) REFERENCES plan_day (id)');
        $this->addSql('ALTER TABLE plan_day ADD CONSTRAINT FK_E94192CDE899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE user_plan ADD CONSTRAINT FK_A7DB17B4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_plan ADD CONSTRAINT FK_A7DB17B4E899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE exercise_instance DROP FOREIGN KEY FK_445C1136BD9A5954');
        $this->addSql('ALTER TABLE user_plan DROP FOREIGN KEY FK_A7DB17B4A76ED395');
        $this->addSql('ALTER TABLE exercise_instance DROP FOREIGN KEY FK_445C1136E934951A');
        $this->addSql('ALTER TABLE plan_day DROP FOREIGN KEY FK_E94192CDE899029B');
        $this->addSql('ALTER TABLE user_plan DROP FOREIGN KEY FK_A7DB17B4E899029B');
        $this->addSql('DROP TABLE exercise_instance');
        $this->addSql('DROP TABLE plan_day');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_plan');
        $this->addSql('DROP TABLE exercise');
        $this->addSql('DROP TABLE plan');
    }
}
