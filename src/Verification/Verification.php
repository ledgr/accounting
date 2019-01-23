<?php
/**
 * This file is part of byrokrat/accounting.
 *
 * byrokrat/accounting is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * byrokrat/accounting is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016-18 Hannes Forsgård
 */

declare(strict_types = 1);

namespace byrokrat\accounting\Verification;

use byrokrat\accounting\AttributableTrait;
use byrokrat\accounting\Query;
use byrokrat\accounting\Summary;
use byrokrat\accounting\Transaction\TransactionInterface;
use byrokrat\amount\Amount;

/**
 * Simple verification value object wrapping a list of transactions
 */
class Verification implements VerificationInterface
{
    use AttributableTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     */
    private $transactionDate;

    /**
     * @var \DateTimeImmutable
     */
    private $registrationDate;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $signature;

    /**
     * @var Summary
     */
    private $summary;

    /**
     * @var TransactionInterface[]
     */
    private $transactions = [];

    public function __construct(
        int $id,
        \DateTimeImmutable $transactionDate,
        \DateTimeImmutable $registrationDate,
        string $description,
        string $signature,
        TransactionInterface ...$transactions
    ) {
        $this->id = $id;
        $this->transactionDate = $transactionDate;
        $this->registrationDate = $registrationDate;
        $this->description = $description;
        $this->signature = $signature;
        $this->transactions = $transactions;
        $this->summary = new Summary;

        foreach ($transactions as $transaction) {
            // Validate currency
            $this->summary->addAmount(
                $transaction->getAmount()->subtract($transaction->getAmount())
            );

            // Add amount to summary
            if (!$transaction->isDeleted()) {
                $this->summary->addAmount($transaction->getAmount());
            }
        }
    }

    public function getVerificationId(): int
    {
        return $this->id;
    }

    public function getTransactionDate(): \DateTimeImmutable
    {
        return $this->transactionDate;
    }

    public function getRegistrationDate(): \DateTimeImmutable
    {
        return $this->registrationDate;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getSignature(): string
    {
        return $this->signature;
    }

    public function getTransactions(): array
    {
        return $this->transactions;
    }

    public function isBalanced(): bool
    {
        return $this->summary->isBalanced();
    }

    public function getMagnitude(): Amount
    {
        return $this->summary->getMagnitude();
    }

    public function select(): Query
    {
        return new Query($this->getTransactions());
    }
}