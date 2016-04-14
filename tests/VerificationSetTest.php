<?php
declare(strict_types=1);

namespace byrokrat\accounting;

class VerificationSetTest extends BaseTestCase
{
    public function testExceptionOnUnbalancedVerification()
    {
        $this->setExpectedException(Exception\UnexpectedValueException::CLASS);
        $ver = $this->prophesize(Verification::CLASS);
        $ver->isBalanced()->willReturn(false);
        new VerificationSet($ver->reveal());
    }

    public function testGetVerifications()
    {
        $verifications = [
            0 => $this->getVerificationMock(),
            1 => $this->getVerificationMock(),
        ];

        $this->assertEquals(
            $verifications,
            (new VerificationSet(...$verifications))->getVerifications()
        );

        $this->assertEquals(
            $verifications,
            iterator_to_array(new VerificationSet(...$verifications))
        );
    }

    public function testGetAccounts()
    {
        $accounts = [
            1234 => $this->getAccountMock(1234),
            9999 => $this->getAccountMock(9999),
        ];

        $verifications = [
            0 => $this->getVerificationMock($accounts),
            1 => $this->getVerificationMock($accounts),
        ];

        $this->assertEquals(
            $accounts,
            iterator_to_array((new VerificationSet(...$verifications))->getAccounts())
        );
    }
}