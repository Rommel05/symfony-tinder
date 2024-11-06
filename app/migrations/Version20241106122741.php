<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241106122741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pair ADD pair_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pair ADD CONSTRAINT FK_95A1E697EB8B2A3 FOREIGN KEY (pair_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_95A1E697EB8B2A3 ON pair (pair_id)');
        $this->addSql('ALTER TABLE swipe ADD swipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A92DE302F1 FOREIGN KEY (swipe_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DB59E9A92DE302F1 ON swipe (swipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pair DROP FOREIGN KEY FK_95A1E697EB8B2A3');
        $this->addSql('DROP INDEX IDX_95A1E697EB8B2A3 ON pair');
        $this->addSql('ALTER TABLE pair DROP pair_id');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A92DE302F1');
        $this->addSql('DROP INDEX IDX_DB59E9A92DE302F1 ON swipe');
        $this->addSql('ALTER TABLE swipe DROP swipe_id');
    }
}
