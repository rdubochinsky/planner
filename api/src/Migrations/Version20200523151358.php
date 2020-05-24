<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200523151358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE task_label (task_label_id SMALLINT AUTO_INCREMENT NOT NULL, task_label_name VARCHAR(24) NOT NULL, task_label_handle VARCHAR(24) NOT NULL, display_order SMALLINT UNSIGNED NOT NULL, UNIQUE INDEX uk_task_label_handle (task_label_handle), PRIMARY KEY(task_label_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (task_id SMALLINT UNSIGNED AUTO_INCREMENT NOT NULL, task_name VARCHAR(128) NOT NULL, is_completed TINYINT(1) NOT NULL, task_date DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, INDEX k_task_name (task_name), PRIMARY KEY(task_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_label_map (task_id SMALLINT UNSIGNED NOT NULL, task_label_id SMALLINT NOT NULL, INDEX IDX_D8653E5E8DB60186 (task_id), INDEX IDX_D8653E5E7379C575 (task_label_id), PRIMARY KEY(task_id, task_label_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task_label_map ADD CONSTRAINT FK_D8653E5E8DB60186 FOREIGN KEY (task_id) REFERENCES task (task_id)');
        $this->addSql('ALTER TABLE task_label_map ADD CONSTRAINT FK_D8653E5E7379C575 FOREIGN KEY (task_label_id) REFERENCES task_label (task_label_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE task_label_map DROP FOREIGN KEY FK_D8653E5E7379C575');
        $this->addSql('ALTER TABLE task_label_map DROP FOREIGN KEY FK_D8653E5E8DB60186');
        $this->addSql('DROP TABLE task_label');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_label_map');
    }
}
