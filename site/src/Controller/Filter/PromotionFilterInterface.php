<?php

namespace App\Controller\Filter;

use App\DTO\PromotionEnquiryInterface;
use App\Entity\Promotion;

interface PromotionFilterInterface
{
    public function apply(PromotionEnquiryInterface $promotionEnquiry, Promotion ... $promotion): PromotionEnquiryInterface;
}