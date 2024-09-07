<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240907100950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, assigned_to_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, duration INT NOT NULL, frequency DOUBLE PRECISION NOT NULL, INDEX IDX_527EDB25F4BD7827 (assigned_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_day (task_id INT NOT NULL, day_id INT NOT NULL, INDEX IDX_8A22D1178DB60186 (task_id), INDEX IDX_8A22D1179C24126 (day_id), PRIMARY KEY(task_id, day_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE worker (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES worker (id)');
        $this->addSql('ALTER TABLE task_day ADD CONSTRAINT FK_8A22D1178DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_day ADD CONSTRAINT FK_8A22D1179C24126 FOREIGN KEY (day_id) REFERENCES day (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25F4BD7827');
        $this->addSql('ALTER TABLE task_day DROP FOREIGN KEY FK_8A22D1178DB60186');
        $this->addSql('ALTER TABLE task_day DROP FOREIGN KEY FK_8A22D1179C24126');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_day');
        $this->addSql('DROP TABLE worker');
    }
}
