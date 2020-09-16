<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200916102239 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE magic_login_token ADD expires_at_timestamp INT UNSIGNED NOT NULL, DROP expires_at');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B3F87899692E25D ON magic_login_token (selector)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_B3F87899692E25D ON magic_login_token');
        $this->addSql('ALTER TABLE magic_login_token ADD expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', DROP expires_at_timestamp');
    }
}
