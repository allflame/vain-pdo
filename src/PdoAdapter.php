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

namespace Vain\Pdo;

use Vain\Database\AbstractDatabase;
use Vain\Database\Exception\LevelIntegrityDatabaseException;
use Vain\Pdo\Exception\CommunicationPdoDatabaseException;
use Vain\Pdo\Exception\QueryPdoDatabaseException;
use Vain\Database\Generator\Factory\GeneratorFactoryInterface;
use Vain\Database\Generator\GeneratorInterface;
use Vain\Pdo\Cursor\PdoCursor;

/**
 * Class PDOAdapter
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class PdoAdapter extends AbstractDatabase
{
    private $pdoInstance;

    private $level;

    /**
     * PDOAdapter constructor.
     *
     * @param GeneratorFactoryInterface $generatorFactory
     * @param \PDO                      $pdoInstance
     */
    public function __construct(
        GeneratorFactoryInterface $generatorFactory,
        \PDO $pdoInstance
    ) {
        $this->pdoInstance = $pdoInstance;
        parent::__construct($generatorFactory);
    }

    /**
     * @return \PDO
     */
    public function getPdoInstance() : \PDO
    {
        return $this->pdoInstance;
    }

    /**
     * @return int
     */
    public function getLevel() : int
    {
        return $this->level;
    }

    /**
     * @inheritDoc
     */
    public function startTransaction() : bool
    {
        if (0 < $this->level) {
            $this->level++;

            return true;
        }
        if (0 > $this->level) {
            throw new LevelIntegrityDatabaseException($this, $this->level);
        }
        try {
            return $this->pdoInstance->beginTransaction();
        } catch (\PDOException $e) {
            throw new CommunicationPDODatabaseException($this, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function commitTransaction() : bool
    {
        if (0 < $this->level) {
            $this->level--;

            return true;
        }
        if (0 < $this->level) {
            throw new LevelIntegrityDatabaseException($this, $this->level);
        }
        try {
            return $this->pdoInstance->commit();
        } catch (\PDOException $e) {
            throw new CommunicationPDODatabaseException($this, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function rollbackTransaction() : bool
    {
        if (0 < $this->level) {
            $this->level--;

            return true;
        }
        if (0 < $this->level) {
            throw new LevelIntegrityDatabaseException($this, $this->level);
        }
        try {
            return $this->pdoInstance->rollBack();
        } catch (\PDOException $e) {
            throw new CommunicationPDODatabaseException($this, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function runQuery($query, array $bindParams) : GeneratorInterface
    {
        $statement = $this->pdoInstance->prepare($query);
        if (false == $statement->execute($bindParams)) {
            throw new QueryPDODatabaseException($this, $statement->errorCode(), $statement->errorInfo());
        }

        return $this->getGeneratorFactory()->create($this, new PDOCursor($statement));
    }
}