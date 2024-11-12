<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241112091616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pair DROP FOREIGN KEY FK_95A1E697EB8B2A3');
        $this->addSql('DROP INDEX IDX_95A1E697EB8B2A3 ON pair');
        $this->addSql('ALTER TABLE pair ADD user_b_id INT DEFAULT NULL, DROP user_a, DROP user_b, CHANGE pair_id user_a_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pair ADD CONSTRAINT FK_95A1E69415F1F91 FOREIGN KEY (user_a_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pair ADD CONSTRAINT FK_95A1E6953EAB07F FOREIGN KEY (user_b_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_95A1E69415F1F91 ON pair (user_a_id)');
        $this->addSql('CREATE INDEX IDX_95A1E6953EAB07F ON pair (user_b_id)');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A92DE302F1');
        $this->addSql('DROP INDEX IDX_DB59E9A92DE302F1 ON swipe');
        $this->addSql('ALTER TABLE swipe ADD user_b_id INT DEFAULT NULL, DROP user_a, DROP user_b, CHANGE swipe_id user_a_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A9415F1F91 FOREIGN KEY (user_a_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A953EAB07F FOREIGN KEY (user_b_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_DB59E9A9415F1F91 ON swipe (user_a_id)');
        $this->addSql('CREATE INDEX IDX_DB59E9A953EAB07F ON swipe (user_b_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pair DROP FOREIGN KEY FK_95A1E69415F1F91');
        $this->addSql('ALTER TABLE pair DROP FOREIGN KEY FK_95A1E6953EAB07F');
        $this->addSql('DROP INDEX IDX_95A1E69415F1F91 ON pair');
        $this->addSql('DROP INDEX IDX_95A1E6953EAB07F ON pair');
        $this->addSql('ALTER TABLE pair ADD pair_id INT DEFAULT NULL, ADD user_a VARCHAR(255) NOT NULL, ADD user_b VARCHAR(255) NOT NULL, DROP user_a_id, DROP user_b_id');
        $this->addSql('ALTER TABLE pair ADD CONSTRAINT FK_95A1E697EB8B2A3 FOREIGN KEY (pair_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_95A1E697EB8B2A3 ON pair (pair_id)');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A9415F1F91');
        $this->addSql('ALTER TABLE swipe DROP FOREIGN KEY FK_DB59E9A953EAB07F');
        $this->addSql('DROP INDEX IDX_DB59E9A9415F1F91 ON swipe');
        $this->addSql('DROP INDEX IDX_DB59E9A953EAB07F ON swipe');
        $this->addSql('ALTER TABLE swipe ADD swipe_id INT DEFAULT NULL, ADD user_a VARCHAR(255) NOT NULL, ADD user_b VARCHAR(255) NOT NULL, DROP user_a_id, DROP user_b_id');
        $this->addSql('ALTER TABLE swipe ADD CONSTRAINT FK_DB59E9A92DE302F1 FOREIGN KEY (swipe_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DB59E9A92DE302F1 ON swipe (swipe_id)');
    }
}
