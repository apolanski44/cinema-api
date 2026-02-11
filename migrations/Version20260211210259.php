<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Platforms\MySQLPlatform;

final class Version20260211210259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration to create initial database schema for cinema reservation system, including tables for movies, rooms, screenings, reservations, and users.';
    }

    public function up(Schema $schema): void
    {
        if (!$this->connection->getDatabasePlatform() instanceof MySQLPlatform) {
            $this->abortIf(true, 'Migration can only be executed safely on MySQL.');
        }

        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, `row_number` INT NOT NULL, seat_number INT NOT NULL, customer_email VARCHAR(255) NOT NULL, screening_id INT NOT NULL, INDEX IDX_42C8495570F5295D (screening_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, number_of_rows INT NOT NULL, seats_per_row INT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE screening (id INT AUTO_INCREMENT NOT NULL, start_time DATETIME NOT NULL, room_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_B708297D54177093 (room_id), INDEX IDX_B708297D8F93B6FC (movie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495570F5295D FOREIGN KEY (screening_id) REFERENCES screening (id)');
        $this->addSql('ALTER TABLE screening ADD CONSTRAINT FK_B708297D54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE screening ADD CONSTRAINT FK_B708297D8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
    }

    public function down(Schema $schema): void
    {
        if (!$this->connection->getDatabasePlatform() instanceof MySQLPlatform) {
            $this->abortIf(true, 'Migration can only be executed safely on MySQL.');
        }
        
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495570F5295D');
        $this->addSql('ALTER TABLE screening DROP FOREIGN KEY FK_B708297D54177093');
        $this->addSql('ALTER TABLE screening DROP FOREIGN KEY FK_B708297D8F93B6FC');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE screening');
        $this->addSql('DROP TABLE user');
    }
}
