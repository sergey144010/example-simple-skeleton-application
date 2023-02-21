<?php

namespace App;

class Connection
{
    private \PDO $dbh;

    public function __construct(private Config $config)
    {
    }

    public function connect(): self
    {
        $config = $this->config->get();

        $dsn = $config['app']['db']['driver'] .
            ':' . 'dbname=' . $config['app']['db']['database'] .
            ';' . 'host=' .  $config['app']['db']['host'] .
            ';' . 'port=' .  $config['app']['db']['port']
        ;

        $this->dbh = new \PDO(
            $dsn,
            $config['app']['db']['user'],
            $config['app']['db']['pass']
        );

        return $this;
    }

    public function connection(): \PDO
    {
        if (! isset($this->dbh)) {
            $this->connect();
        }

        return $this->dbh;
    }
}
