<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220917141805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE verification_request CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE created_at created_at DATE DEFAULT NULL, CHANGE updated_at updated_at DATE DEFAULT NULL, CHANGE image decision_reason VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE verification_request CHANGE status status VARCHAR(255) DEFAULT \'Verification Requested\' NOT NULL, CHANGE created_at created_at DATE DEFAULT \'CURRENT_TIMESTAMP\', CHANGE updated_at updated_at DATE DEFAULT \'CURRENT_TIMESTAMP\', CHANGE decision_reason image VARCHAR(255) DEFAULT NULL');
    }
}
