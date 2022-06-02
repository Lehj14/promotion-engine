<?php

namespace App\Filter;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

class LowestPriceFilter implements PromotionFilterInterface
{
    public function apply(PromotionEnquiryInterface $promotionEnquiry, Promotion ... $promotion): PromotionEnquiryInterface
    {
        $promotionEnquiry->setDiscountedPrice(50);
        $promotionEnquiry->setPrice(100);
        $promotionEnquiry->setPromotionId(3);
        $promotionEnquiry->setPromotionName('Black Friday half price');

        return $promotionEnquiry;
    }
}
