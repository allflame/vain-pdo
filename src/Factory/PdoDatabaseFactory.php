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

namespace Vain\Pdo\Factory;

use Vain\Database\DatabaseInterface;
use Vain\Database\Factory\AbstractDatabaseFactory;
use Vain\Database\Generator\Factory\GeneratorFactoryInterface;
use Vain\Pdo\PdoAdapter;

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
     * @param GeneratorFactoryInterface $generatorFactory
     * @param string                    $name
     */
    public function __construct(
        GeneratorFactoryInterface $generatorFactory,
        $name
    ) {
        $this->generatorFactory = $generatorFactory;
        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    public function createDatabase(array $configData, $connection) : DatabaseInterface
    {
        return new PdoAdapter($this->generatorFactory, $connection);
    }
}