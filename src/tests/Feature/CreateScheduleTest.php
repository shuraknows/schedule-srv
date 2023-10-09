<?php

namespace Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateScheduleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_creates_schedule(): void
    {
        $amount = 1000000;
        $term = 12;
        $interestRate = 400;
        $euriborRate = 394;

        $response = $this->post('/api/schedule', [
            'amount' => $amount,
            'term' => $term,
            'interestRate' => $interestRate,
            'euriborRate' => $euriborRate,
        ]);

        // response
        $response->assertStatus(201);
        $scheduleId = $response->json('id');
        $this->assertEquals($amount, $response->json('amount'));
        $this->assertEquals($term, $response->json('term'));
        $this->assertEquals($interestRate, $response->json('interestRate'));
        $this->assertEquals($this->expectedSegments(), $response->json('segments'));

        // DB
        $this->assertDatabaseHas('schedules', ['amount' => $amount, 'term' => $term, 'interest_rate' => $interestRate]);

        foreach ($this->expectedSegments() as $expectedSegment) {
            $this->assertDatabaseHas(
                'segments',
                [
                    'schedule_id' => $scheduleId,
                    'sequence_number' => $expectedSegment['sequenceNumber'],
                    'remaining_amount' => $expectedSegment['remainingAmount'],
                    'principal_payment' => $expectedSegment['principalPayment'],
                    'interest_payment' => $expectedSegment['interestPayment'],
                    'euribor_payment' => $expectedSegment['euriborPayment'],
                    'total_payment' => $expectedSegment['totalPayment'],
                    'euribor_rate' => $expectedSegment['euriborRate'],
                ]
            );
        }
    }

    private function expectedSegments(): array
    {
        $json = '[
            {"sequenceNumber":1,"remainingAmount":1000000,"principalPayment":81817,"interestPayment":3333,"euriborPayment":3283,"totalPayment":88433,"euriborRate":394},
            {"sequenceNumber":2,"remainingAmount":918183,"principalPayment":82089,"interestPayment":3061,"euriborPayment":3015,"totalPayment":88165,"euriborRate":394},
            {"sequenceNumber":3,"remainingAmount":836094,"principalPayment":82363,"interestPayment":2787,"euriborPayment":2745,"totalPayment":87895,"euriborRate":394},
            {"sequenceNumber":4,"remainingAmount":753731,"principalPayment":82638,"interestPayment":2512,"euriborPayment":2475,"totalPayment":87625,"euriborRate":394},
            {"sequenceNumber":5,"remainingAmount":671093,"principalPayment":82913,"interestPayment":2237,"euriborPayment":2203,"totalPayment":87353,"euriborRate":394},
            {"sequenceNumber":6,"remainingAmount":588180,"principalPayment":83189,"interestPayment":1961,"euriborPayment":1931,"totalPayment":87081,"euriborRate":394},
            {"sequenceNumber":7,"remainingAmount":504991,"principalPayment":83467,"interestPayment":1683,"euriborPayment":1658,"totalPayment":86808,"euriborRate":394},
            {"sequenceNumber":8,"remainingAmount":421524,"principalPayment":83745,"interestPayment":1405,"euriborPayment":1384,"totalPayment":86534,"euriborRate":394},
            {"sequenceNumber":9,"remainingAmount":337779,"principalPayment":84024,"interestPayment":1126,"euriborPayment":1109,"totalPayment":86259,"euriborRate":394},
            {"sequenceNumber":10,"remainingAmount":253755,"principalPayment":84304,"interestPayment":846,"euriborPayment":833,"totalPayment":85983,"euriborRate":394},
            {"sequenceNumber":11,"remainingAmount":169451,"principalPayment":84585,"interestPayment":565,"euriborPayment":556,"totalPayment":85706,"euriborRate":394},
            {"sequenceNumber":12,"remainingAmount":84866,"principalPayment":84866,"interestPayment":283,"euriborPayment":279,"totalPayment":85428,"euriborRate":394}
        ]';

        return json_decode($json, true);
    }
}
