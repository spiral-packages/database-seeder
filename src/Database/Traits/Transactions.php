<?php

declare(strict_types=1);

namespace Spiral\DatabaseSeeder\Database\Traits;

use Spiral\DatabaseSeeder\Database\Strategy\TransactionStrategy;

trait Transactions
{
    private ?TransactionStrategy $transactionStrategy = null;


    public function beginTransaction(): void
    {
        $this->beforeBeginTransaction();

        $this->getTransactionStrategy()->begin();

        $this->afterBeginTransaction();
    }

    public function rollbackTransaction(): void
    {
        $this->beforeRollbackTransaction();

        $this->getTransactionStrategy()->rollback();

        $this->afterRollbackTransaction();
    }

    protected function setUpTransactions(): void
    {
        $this->beginTransaction();
    }

    protected function tearDownTransactions(): void
    {
        $this->rollbackTransaction();
    }

    protected function getTransactionStrategy(): TransactionStrategy
    {
        if ($this->transactionStrategy === null) {
            $this->transactionStrategy = new TransactionStrategy(testCase: $this);
        }

        return $this->transactionStrategy;
    }

    /**
     * Perform any work before the database transaction has started
     */
    protected function beforeBeginTransaction(): void
    {
        // ...
    }

    /**
     * Perform any work after the database transaction has started
     */
    protected function afterBeginTransaction(): void
    {
        // ...
    }

    /**
     * Perform any work before rolling back the transaction
     */
    protected function beforeRollbackTransaction(): void
    {
        // ...
    }

    /**
     * Perform any work after rolling back the transaction
     */
    protected function afterRollbackTransaction(): void
    {
        // ...
    }
}
