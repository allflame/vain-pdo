<?php
/**
 * Vain Framework
 *
 * PHP Version 7
 *
 * @package   vain-pdo
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://github.com/allflame/vain-pdo
 */

namespace Vain\Pdo\Connection;

use Vain\Connection\AbstractConnection;

/**
 * Class PdoConnection
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class PdoConnection extends AbstractConnection implements PdoConnectionInterface
{
    /**
     * @param array $config
     *
     * @return array
     */
    protected function getCredentials(array $config) : array
    {
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
    public function doConnect(array $configData)
    {
        list ($driver, $host, $port, $dbname, $username, $password, $sslmode) = $this->getCredentials($configData);

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