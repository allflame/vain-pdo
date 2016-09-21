<?php
/**
 * Vain Framework
 *
 * PHP Version 7
 *
 * @package   vain-database
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/allflame/vain-database
 */
declare(strict_types = 1);

namespace Vain\Pdo\Factory;

use Vain\Connection\Exception\NoRequiredFieldException;
use Vain\Connection\Factory\AbstractConnectionFactory;

/**
 * Class PdoConnectionFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class PdoConnectionFactory extends AbstractConnectionFactory
{
    /**
     * @param array $config
     *
     * @return array
     *
     * @throws NoRequiredFieldException
     */
    protected function getCredentials(array $config) : array
    {
        foreach (['driver', 'host', 'port', 'dbname', 'username', 'password'] as $requiredField) {
            if (false === array_key_exists($requiredField, $config)) {
                throw new NoRequiredFieldException($this, $requiredField);
            }
        }

        if (false === array_key_exists('sslmode', $config)) {
            $sslmode = '';
        } else {
            $sslmode = $config['sslmode'];
        }

        return [
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['username'],
            $config['password'],
            $sslmode,
        ];
    }

    /**
     * @inheritDoc
     */
    public function createConnection(array $config)
    {
        list ($driver, $host, $port, $dbname, $username, $password, $sslmode) = $this->getCredentials($config);

        $dsn = sprintf('%s:host=%s;port=%d;dbname=%s', $driver, $host, $port, $dbname);

        if ('' !== $sslmode) {
            $dsn .= sprintf(';sslmode=%s', $sslmode);
        }

        $options = [\PDO::ATTR_EMULATE_PREPARES => true];
        $pdo = new \PDO($dsn, $username, $password, $options);
        if (defined('PDO::PGSQL_ATTR_DISABLE_PREPARES')
            && (!isset($driverOptions[\PDO::PGSQL_ATTR_DISABLE_PREPARES])
                || true === $driverOptions[\PDO::PGSQL_ATTR_DISABLE_PREPARES]
            )
        ) {
            $pdo->setAttribute(\PDO::PGSQL_ATTR_DISABLE_PREPARES, true);
        }

        return $pdo;
    }
}