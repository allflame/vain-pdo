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

namespace Vain\Pdo\Database;

use Vain\Database\AbstractDatabase;
use Vain\Database\Exception\LevelIntegrityDatabaseException;
use Vain\Database\Mvcc\MvccDatabaseInterface;
use Vain\Pdo\Connection\PdoConnectionInterface;
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
class PdoDatabase extends AbstractDatabase implements MvccDatabaseInterface
{
    private $pdoConnection;

    private $level;

    /**
     * PDOAdapter constructor.
     *
     * @param GeneratorFactoryInterface $generatorFactory
     * @param PdoConnectionInterface    $pdoConnection
     */
    public function __construct(
        GeneratorFactoryInterface $generatorFactory,
        PdoConnectionInterface $pdoConnection
    ) {
        $this->pdoConnection = $pdoConnection;
        parent::__construct($generatorFactory);
    }

    /**
     * @return PdoConnectionInterface
     */
    public function getPdoConnection() : PdoConnectionInterface
    {
        return $this->pdoConnection;
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
            return $this->pdoConnection->establish()->beginTransaction();
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
            return $this->pdoConnection->establish()->commit();
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
            return $this->pdoConnection->establish()->rollBack();
        } catch (\PDOException $e) {
            throw new CommunicationPDODatabaseException($this, $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function runQuery($query, array $bindParams, array $bindParamTypes = []) : GeneratorInterface
    {
        $statement = $this->pdoConnection->establish()->prepare($query);
        if (false == $statement->execute($bindParams)) {
            throw new QueryPDODatabaseException($this, $statement->errorCode(), $statement->errorInfo());
        }

        return $this->getGeneratorFactory()->create($this, new PDOCursor($statement));
    }
}