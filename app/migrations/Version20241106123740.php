<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106123740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pair (id INT AUTO_INCREMENT NOT NULL, pair_id INT DEFAULT NULL, user_a VARCHAR(255) NOT NULL, user_b VARCHAR(255) NOT NULL, INDEX IDX_95A1E697EB8B2A3 (pair_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE swipe (id INT AUTO_INCREMENT NOT NULL, swipe_id INT DEFAULT NULL, user_a VARCHAR(255) NOT NULL, user_b VARCHAR(255) NOT NULL, action TINYINT(1) NOT NULL, INDEX IDX_DB59E9A92DE302F1 (swipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(50) NOT NULL, age INT NOT NULL, gender VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, image_path VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pair ADD CONSTRAINT FK_95A1E697EB8B2A3 FOREIGN KEY (pair_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A92DE302F1 FOREIGN KEY (swipe_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pair DROP FOREIGN KEY FK_95A1E697EB8B2A3');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A92DE302F1');
        $this->addSql('DROP TABLE pair');
        $this->addSql('DROP TABLE swipe');
        $this->addSql('DROP TABLE user');
    }
}
