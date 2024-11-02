<?php
namespace App\System\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class PriceRequestDto
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Choice(['common', 'luxury'])]
        public readonly ?string $vehicleType,

        #[Assert\NotBlank]
        #[Assert\GreaterThan(0)]
        public readonly ?float $price,
    ) {
    }
}