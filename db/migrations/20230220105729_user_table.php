<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserTable extends AbstractMigration
{
    public function change(): void
    {
        $sql = '
            CREATE TABLE `user` (
              `id` int(11) AUTO_INCREMENT PRIMARY KEY,
              `name` varchar(255) NOT NULL,
              `lastName` varchar(255) NOT NULL,
              `from` varchar(255),
              `age` TINYINT,
              `key` char(64) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

        $this->execute($sql);
    }
}
