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

use Vain\Core\Connection\AbstractConnection;

/**
 * Class PdoConnection
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class PdoConnection extends AbstractConnection
{
    /**
     * @param array $config
     *
     * @return array
     */
    protected function getCredentials(array $config): array
    {
        if (false === array_key_exists('sslmode', $config)) {
            $sslmode = '';
        } else {
            $sslmode = $config['sslmode'];
        }

        if (false === array_key_exists('timeout', $config)) {
            $timeout = 1;
        } else {
            $timeout = $config['timeout'];
        }

        return [
            $config['type'],
            $config['host'],
            $config['port'],
            $config['dbname'],
            $config['username'],
            $config['password'],
            $sslmode,
            $timeout
        ];
    }

    /**
     * @inheritDoc
     */
    public function doEstablish()
    {
        list ($type, $host, $port, $dbname, $username, $password, $sslmode, $timeout) = $this->getCredentials(
            $this->getConfigData()
        );

        $dsn = sprintf('%s:host=%s;port=%d;dbname=%s', $type, $host, $port, $dbname);

        if ('' !== $sslmode) {
            $dsn .= sprintf(';sslmode=%s', $sslmode);
        }

        $options = [
            \PDO::ATTR_TIMEOUT          => $timeout,
            \PDO::ATTR_ERRMODE          => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => true,
        ];
        $driverOptions = [];

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
