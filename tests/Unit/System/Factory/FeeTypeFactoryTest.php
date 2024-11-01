<?php
namespace App\Tests\Unit\System\DataObject;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use App\System\DataObject\FeeType;
use App\System\Factory\FeeTypeFactory;

class FeeTypeFactoryTest extends TestCase
{
    private array $schedule = [
        [
            "name" => "base",
            "vehicle_type" => "common",
            "percentage" => -13,
            "min"=> 100,
            "max"=> 200,
            "fixed"=> null,
            "tier_charges" => null
        ]
    ];

    public function testMakeListSuccess(): void
    {
        $errors = $this->createStub(ConstraintViolationListInterface::class);
        $errors->method('count')->willReturn(0);
        $validatorMock = $this->createStub(ValidatorInterface::class);
        $validatorMock->method('validate')->willReturn($errors);

        $feeTypes = (new FeeTypeFactory($validatorMock))->makeList($this->schedule);
        $this->assertInstanceOf(FeeType::class, $feeTypes[0]);
    }
    
    public function testMakeListFail(): void
    {
        $this->expectException(\Exception::class);

        $errors = $this->createStub(ConstraintViolationListInterface::class);
        $errors->method('count')->willReturn(3);
        $validatorMock = $this->createStub(ValidatorInterface::class);
        $validatorMock->method('validate')->willReturn($errors);

        (new FeeTypeFactory($validatorMock))->makeList($this->schedule);
    }
}