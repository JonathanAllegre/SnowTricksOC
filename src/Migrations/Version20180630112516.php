<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180630112516 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trick ADD listing_picture_id INT DEFAULT NULL, DROP listing_picture');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E9829CACB FOREIGN KEY (listing_picture_id) REFERENCES picture (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_D8F0A91E9829CACB ON trick (listing_picture_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9829CACB');
        $this->addSql('DROP INDEX IDX_D8F0A91E9829CACB ON trick');
        $this->addSql('ALTER TABLE trick ADD listing_picture VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP listing_picture_id');
    }
}
