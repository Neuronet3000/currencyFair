<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150316000644 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE currencyPairCounters 
                (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL,
                currencyFrom VARCHAR(3) NOT NULL,
                currencyTo VARCHAR(3) NOT NULL,
                originatingCountry VARCHAR(2) NOT NULL,
                totalSell DOUBLE PRECISION NOT NULL,
                totalBuy DOUBLE PRECISION NOT NULL,
                transactionsCnt BIGINT UNSIGNED NOT NULL,
                PRIMARY KEY(id),
                INDEX curr_pair_country_date (currencyFrom, currencyTo, originatingCountry))
                DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE currencyPairCounters');
    }
}
