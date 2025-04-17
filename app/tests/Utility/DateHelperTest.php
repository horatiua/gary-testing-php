<?php

namespace App\Tests\Utility;

use App\Utility\DateHelper;
use Cassandra\Date;
use PHPUnit\Framework\TestCase;

class DateHelperTest extends TestCase
{
    /**
     * Static method "weeksBetweenDates" cannot be invoked on mock object
     * Solution: make method not static
     *
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    // public function testItWorks()
    // {
    //     $dateHelper = $this->createMock(DateHelper::class);
    //     $dateHelper->method('weeksBetweenDates')->willReturn(52);
    //
    //     $numOfWeeks = $dateHelper::weeksBetweenDates(
    //         date_create('2024-01-01'),
    //         date_create('2025-01-01')
    //     );
    //     $this->assertEquals(52, $numOfWeeks);
    // }

    /** @test */
    public function weeks_between_dates_is_calculated_correctly()
    {
        // Setup
        $date1 = date_create('2024-01-01');
        $date2 = date_create('2025-01-01');
        $dateHelper = new DateHelper();

        // Do something
        $weeksBetweenDates = $dateHelper->weeksBetweenDates($date1, $date2);

        // Make assertions
        $this->assertSame(52, $weeksBetweenDates);
    }
}