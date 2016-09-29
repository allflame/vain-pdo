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

namespace Vain\Pdo\Exception;

use Vain\Database\Exception\DatabaseException;
use Vain\Pdo\Database\PdoDatabase;

/**
 * Class PdoDatabaseException
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class PdoDatabaseException extends DatabaseException
{
    /**
     * PDODatabaseException constructor.
     *
     * @param PdoDatabase   $database
     * @param string        $errorCode
     * @param string        $errorMessage
     * @param \PDOException $e
     */
    public function __construct(PdoDatabase $database, string $errorCode, string $errorMessage, \PDOException $e = null)
    {
        parent::__construct(
            $database,
            sprintf('Unable to communicate to the database: %d - %s', $errorCode, $errorMessage),
            0,
            $e
        );
    }
}