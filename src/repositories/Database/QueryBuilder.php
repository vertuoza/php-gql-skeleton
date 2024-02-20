<?php

namespace Vertuoza\Repositories\Database;

use Illuminate\Database\Capsule\Manager as CapsuleManager;


class QueryBuilder extends CapsuleManager
{
    public function __construct($charset = null)
    {
        parent::__construct();

        $connexionArray = [
            'driver' => 'mysql',
            'host' => $_ENV['DB_HOST'],
            'database' => $_ENV['DB_DATABASE'],
            'username' => $_ENV['DB_USERNAME'],
            'password' => $_ENV['DB_PASSWORD'],
            'port' => isset($_ENV['DB_PORT']) ? $_ENV['DB_PORT'] : null,
            'unix_socket' => isset($_ENV['DB_SOCKET']) ? $_ENV['DB_SOCKET'] : null,
        ];


        if ($charset) {
            $connexionArray['charset'] = $charset;
        }
        $this->addConnection($connexionArray);
    }
}
