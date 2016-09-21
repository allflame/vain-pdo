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

namespace Vain\Pdo\Cursor;

use Vain\Database\Cursor\CursorInterface;

/**
 * Class PdoCursor
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class PdoCursor implements CursorInterface
{
    private $pdoStatementInstance;

    private $mode;

    /**
     * VainDatabasePDOGenerator constructor.
     *
     * @param \PDOStatement $pdoStatementInstance
     * @param int           $mode
     */
    public function __construct(\PDOStatement $pdoStatementInstance, $mode = \PDO::FETCH_ASSOC)
    {
        $this->pdoStatementInstance = $pdoStatementInstance;
        $this->mode = $mode;
    }

    /**
     * @inheritDoc
     */
    public function current() : array
    {
        return $this->pdoStatementInstance->fetch($this->mode);
    }

    /**
     * @inheritDoc
     */
    public function next() : bool
    {
        return $this->pdoStatementInstance->nextRowset();
    }

    /**
     * @inheritDoc
     */
    public function valid() : bool
    {
        return ($this->pdoStatementInstance->errorCode() === '00000');
    }

    /**
     * @inheritDoc
     */
    public function close() : CursorInterface
    {
        $this->pdoStatementInstance->closeCursor();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function mode(int $mode) : CursorInterface
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function count() : int
    {
        return $this->pdoStatementInstance->rowCount();
    }

    /**
     * @inheritDoc
     */
    public function getSingle() : array
    {
        return $this->pdoStatementInstance->fetch($this->mode);
    }

    /**
     * @inheritDoc
     */
    public function getAll() : array
    {
        return $this->pdoStatementInstance->fetchAll($this->mode);
    }
}