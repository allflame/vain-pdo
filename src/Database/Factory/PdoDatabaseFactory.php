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
declare(strict_types = 1);

namespace Vain\Pdo\Database\Factory;

use Vain\Core\Connection\ConnectionInterface;
use Vain\Core\Database\DatabaseInterface;
use Vain\Database\Factory\AbstractDatabaseFactory;
use Vain\Core\Database\Generator\Factory\DatabaseGeneratorFactoryInterface;
use Vain\Pdo\Connection\PdoConnection;
use Vain\Pdo\Database\PdoDatabase;

/**
 * Class PdoDatabaseFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class PdoDatabaseFactory extends AbstractDatabaseFactory
{
    private $generatorFactory;

    /**
     * PDODatabaseFactory constructor.
     *
     * @param DatabaseGeneratorFactoryInterface $generatorFactory
     * @param string                    $name
     */
    public function __construct(
        DatabaseGeneratorFactoryInterface $generatorFactory,
        $name
    ) {
        $this->generatorFactory = $generatorFactory;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    public function createDatabase(array $configData, ConnectionInterface $connection) : DatabaseInterface
    {
        /**
         * @var PdoConnection $connection
         */
        return new PdoDatabase($this->generatorFactory, $connection);
    }
}
