<?php

namespace App\Controller;

use App\Controller\Filter\PromotionFilterInterface;
use App\DTO\LowestPriceEnquiryDTO;
use App\Repository\ProductRepository;
use App\Repository\PromotionRepository;
use App\Service\Serializer\DTOSerializer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private PromotionRepository $promotionRepository
    )
    {
    }

    #[Route('/products/{id}/lowest-price', name: 'lowest-price', methods: 'POST')]
    public function lowestPrice(
        Request $request,
        int $id,
        DTOSerializer $serializer,
        PromotionFilterInterface $promotionFilter
    ): JsonResponse|Response {
        if ($request->headers->has('force-fail')) {
            return new JsonResponse(
                ['error' => 'Promotions engine failure message'],
                $request->headers->get('force-fail')
            );
        }

        /** @var LowestPriceEnquiryDTO $lowestPrinceEnquiry */
        $lowestPrinceEnquiry = $serializer->deserialize($request->getContent(), LowestPriceEnquiryDTO::class, 'json');

        $products = $this->productRepository->find(['id' => $id]);
        $lowestPrinceEnquiry->setProduct($products);

        $promotions = $this->promotionRepository->findValidForProducts(
            $products,
            date_create_immutable($lowestPrinceEnquiry->getRequestDate())
        );

        /** @var PromotionFilterInterface $modifyEnquiry */
        $modifyEnquiry = $promotionFilter->apply($lowestPrinceEnquiry, ... $promotions);

        $responseContent = $serializer->serialize($modifyEnquiry, 'json');

        return new Response($responseContent, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    #[Route('/products/{id}/promotions', name: 'promotions', methods: 'GET')]
    public function promotions()
    {
    }
}
