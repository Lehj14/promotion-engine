<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiryDTO;
use App\Entity\Promotion;
use App\Filter\LowestPriceFilter;
use App\Tests\ServiceTestCase;

class LowestPriceFilterTest extends ServiceTestCase
{
    /**
     * @test
     * @return void
     */
    public function lowest_price_promotion_filtering_is_applied_correctly(): void
    {
        //Give
        $enquiry = new LowestPriceEnquiryDTO();
        $promotions = $this->promotionsDataProvider();
        $lowestPriceFilter = self::getContainer()->get(LowestPriceFilter::class);

        //WHen
        /** @var LowestPriceEnquiryDTO $filteredEnquiry */
        $filteredEnquiry = $lowestPriceFilter->apply($enquiry, ... $promotions);

        //Then
        $this->assertSame(100, $filteredEnquiry->getPrice());
        $this->assertSame(50, $filteredEnquiry->getDiscountedPrice());
        $this->assertSame('Black Friday half price', $filteredEnquiry->getPromotionName());
    }

    /**
     * @return Promotion[]
     */
    public function promotionsDataProvider(): array
    {
        $promotionOne = new Promotion();
        $promotionOne->setName('Black Friday half price sale');
        $promotionOne->setAdjustment(0.5);
        $promotionOne->setCriteria(["from" => "2022-11-25", "to" => "2022-11-28"]);
        $promotionOne->setType('date_range_multiplier');

        $promotionTwo = new Promotion();
        $promotionTwo->setName('Voucher OU812');
        $promotionTwo->setAdjustment(100);
        $promotionTwo->setCriteria(["code" => "OU812"]);
        $promotionTwo->setType('fixed_price_voucher');

        $promotionThree = new Promotion();
        $promotionThree->setName('Buy one get one free');
        $promotionThree->setAdjustment(0.5);
        $promotionThree->setCriteria(["minimum_quantity" => 2]);
        $promotionThree->setType('even_items_multiplier');

        return [$promotionOne, $promotionTwo, $promotionThree];
    }
}