<?php
namespace App\Tests\Unit\System\DataObject;

use PHPUnit\Framework\TestCase;
use App\System\DataObject\{FeeType, TierCharge};

class FeeTypeTest extends TestCase
{
    public function testCalculateFeeBase(): void
    {
        $feeType = new FeeType(
            "base",
            "common",
            20,
            10,
            100,
            null,
            null
        );

        self::assertEquals(100, $feeType->calculateFee(600));
    }

    public function testCalculateFeeSpecial(): void
    {
        $feeType = new FeeType(
            "base",
            "common",
            40,
            null,
            null,
            null,
            null
        );

        self::assertEquals(40, $feeType->calculateFee(100));
    }

    public function testCalculateFeeStorage(): void
    {
        $fee = 100;
        $feeType = new FeeType(
            "base",
            "common",
            null,
            null,
            null,
            $fee,
            null
        );

        self::assertEquals($fee, $feeType->calculateFee(300));
    }

    public function testCalculateFeeAssociation(): void
    {
        $fee = 40;
        $feeType = new FeeType(
            "base",
            "common",
            null,
            null,
            null,
            null,
            [
                new TierCharge(100, 500, $fee)
            ]
        );

        self::assertEquals($fee, $feeType->calculateFee(200));
    }
}