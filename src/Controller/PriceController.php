<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use App\System\Dto\PriceRequestDto;
use App\System\Service\Pricing;

class PriceController extends AbstractController
{
    #[Route('/price', name: 'post_price', methods: ['POST'])]
    public function price(
        #[MapRequestPayload] PriceRequestDto $priceRequestDto,
        Pricing $pricing
    ): JsonResponse
    {
      try {
        $result = $pricing->setFees($priceRequestDto);
      } catch (\Throwable $th) {
        return $this->json(['error' =>$th->getMessage()], $th->getCode());
      }
      
      return $this->json(['result' =>$result]);
    }
}