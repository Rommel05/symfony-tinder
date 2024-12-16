<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216084122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F6C69D673');
        $this->addSql('DROP INDEX IDX_B6BD307F6C69D673 ON message');
        $this->addSql('ALTER TABLE message ADD sender_id INT NOT NULL, DROP sennder_id, CHANGE receiver_id receiver_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FF624B39D ON message (sender_id)');
        $this->addSql('ALTER TABLE user ADD sended TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('DROP INDEX IDX_B6BD307FF624B39D ON message');
        $this->addSql('ALTER TABLE message ADD sennder_id INT DEFAULT NULL, DROP sender_id, CHANGE receiver_id receiver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F6C69D673 FOREIGN KEY (sennder_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B6BD307F6C69D673 ON message (sennder_id)');
        $this->addSql('ALTER TABLE user DROP sended');
    }
}
