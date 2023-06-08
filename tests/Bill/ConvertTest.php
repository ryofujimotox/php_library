<?php
use PHPUnit\Framework\TestCase;
use FrUtility\Bill\Convert;

class ConvertTest extends TestCase
{
    protected $billing;

    public function setUp(): void
    {
        $this->billing = new Convert(); // Billing is your class name, replace it accordingly
    }

    /**
     * @dataProvider orderPricesProvider
     */
    public function testCalculateOrderPrices($orders, $expected)
    {
        $result = $this->billing->calculateOrderPrices($orders);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider taxesProvider
     */
    public function testGroupTaxes($orders, $expected)
    {
        $result = $this->billing->groupTaxes($orders);
        $this->assertEquals($expected, $result);
    }

    public function orderPricesProvider()
    {
        return [
            [
                [
                    [
                        'name' => 'Product 1',
                        'quantity' => 2,
                        'unit_price' => 50,
                        'tax' => 10
                    ],
                    [
                        'name' => 'Product 2',
                        'quantity' => 1,
                        'unit_price' => 100,
                        'tax' => 10
                    ]
                ],
                [
                    [
                        'name' => 'Product 1',
                        'quantity' => 2,
                        'unit_price' => 50,
                        'price' => 100,
                        'tax' => 10
                    ],
                    [
                        'name' => 'Product 2',
                        'quantity' => 1,
                        'unit_price' => 100,
                        'price' => 100,
                        'tax' => 10
                    ]
                ]
            ]
            // You can add more test cases here
        ];
    }

    public function taxesProvider()
    {
        return [
            [
                [
                    [
                        'name' => 'Product 1',
                        'quantity' => 2,
                        'unit_price' => 50,
                        'price' => 100,
                        'tax' => 10
                    ],
                    [
                        'name' => 'Product 2',
                        'quantity' => 1,
                        'unit_price' => 100,
                        'price' => 100,
                        'tax' => 10
                    ]
                ],
                [
                    [
                        'rate' => 10,
                        'amount' => 200,
                        'tax' => 20
                    ]
                ]
            ]
            // You can add more test cases here
        ];
    }
}
