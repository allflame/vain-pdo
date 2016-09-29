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

use Vain\Connection\ConnectionInterface;

/**
 * Interface PdoConnectionInterface
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 *
 * @method \PDO establish
 */
interface PdoConnectionInterface extends ConnectionInterface
{
}