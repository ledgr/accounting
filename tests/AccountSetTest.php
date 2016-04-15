<?php
declare(strict_types=1);

namespace byrokrat\accounting;

class AccountSetTest extends BaseTestCase
{
    public function testIteration()
    {
        $accounts = [
            0 => $this->getAccountMock(0),
            1 => $this->getAccountMock(1),
            1234 => $this->getAccountMock(1234),
        ];

        $this->assertEquals(
            $accounts,
            iterator_to_array(new AccountSet(...$accounts))
        );
    }

    public function testContainsAccount()
    {
        $set = new AccountSet($this->getAccountMock(1234));

        $this->assertTrue(
            $set->contains(1234),
            'Set should contain account 1234'
        );

        $this->assertFalse(
            $set->contains(9999),
            'Set should NOT contain account 9999'
        );
    }

    public function testRemoveAccountFromNumber()
    {
        $set = new AccountSet($this->getAccountMock(1234));

        $this->assertTrue(
            $set->contains(1234),
            'Set should contain account 1234'
        );

        $set->removeAccountFromNumber(1234);

        $this->assertFalse(
            $set->contains(1234),
            'Set should NOT contain account 1234 as it has been removed'
        );

        $this->assertNull(
            $set->removeAccountFromNumber(1234),
            'Removing unexisting accounts should do no harm'
        );
    }

    public function testGetAccountFromNumber()
    {
        $account = $this->getAccountMock(1234, 'foobar');
        $this->assertEquals(
            $account,
            (new AccountSet($account))->getAccountFromNumber(1234)
        );
    }

    public function testExceptionOnUnknownAccount()
    {
        $this->setExpectedException(Exception\OutOfBoundsException::CLASS);
        (new AccountSet)->getAccountFromNumber(1234);
    }

    public function testGetAccountFromName()
    {
        $account = $this->getAccountMock(1234, 'foobar');
        $this->assertEquals(
            $account,
            (new AccountSet($account))->getAccountFromName('foobar')
        );
    }

    public function testExceptionOnUnknownAccountName()
    {
        $this->setExpectedException(Exception\OutOfBoundsException::CLASS);
        (new AccountSet)->getAccountFromName('foobar');
    }

    public function testAlterAccount()
    {
        $set = new AccountSet($this->getAccountMock(1234, 'foobar'));
        $set->addAccount($this->getAccountMock(1234, 'altered'));

        $this->assertSame(
            'altered',
            $set->getAccountFromNumber(1234)->getName(),
            'Adding multiple accounts with the same number should overwrite previous values'
        );
    }
}
