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

use Vain\Pdo\PdoAdapter;

/**
 * Class QueryPdoDatabaseException
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class QueryPdoDatabaseException extends PdoDatabaseException
{
    /**
     * VainDatabasePDOAdapterQueryException constructor.
     *
     * @param PdoAdapter $database
     * @param string     $errorCode
     * @param array      $errorInfo
     */
    public function __construct(PdoAdapter $database, string $errorCode, array $errorInfo)
    {
        parent::__construct($database, $errorCode, implode(', ', $errorInfo));
    }
}