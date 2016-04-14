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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat/accounting. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016 Hannes Forsgård
 */

declare(strict_types=1);

namespace byrokrat\accounting;

/**
 * Account value object containing a number and a name
 */
abstract class Account
{
    /**
     * @var int 4 digit account number
     */
    private $number;

    /**
     * @var string Free text name of account
     */
    private $name;

    /**
     * Set account values
     *
     * @param  int    $number 4 digit number identifying account
     * @param  string $name   Free text name of account
     *
     * @throws Exception\InvalidArgumentException If $number is < 1000 or > 9999
     */
    public function __construct(int $number, string $name)
    {
        if ($number < 1000 || $number > 9999) {
            throw new Exception\InvalidArgumentException(
                'Account number must be greater than 999 and lesser than 10000'
            );
        }
        $this->number = $number;
        $this->name = $name;
    }

    /**
     * Get 4 digit account number
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * Get name of account
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Check if $account is equal to this instance
     *
     * @param Account $account Account to compare with current instance
     */
    public function equals(Account $account): bool
    {
        return (
            get_class($this) == get_class($account)
            && $this->getNumber() == $account->getNumber()
            && $this->getName() == $account->getName()
        );
    }

    /**
     * Check if object represents an asset account
     */
    public function isAsset(): bool
    {
        return false;
    }

    /**
     * Check if object represents a cost account
     */
    public function isCost(): bool
    {
        return false;
    }

    /**
     * Check if object represents a debt account
     */
    public function isDebt(): bool
    {
        return false;
    }

    /**
     * Check if object represents an earnings account
     */
    public function isEarning(): bool
    {
        return false;
    }
}