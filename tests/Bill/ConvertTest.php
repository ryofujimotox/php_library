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
    public function testGroupTaxes($orders, $setting, $expected)
    {
        $result = $this->billing->groupTaxes($orders, $setting);
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
                        'name' => '栗饅頭',
                        'quantity' => 1,
                        'unit_price' => 700,
                        'price' => 700,
                        'tax' => 10
                    ],
                    [
                        'name' => 'たこ焼き',
                        'quantity' => 1,
                        'unit_price' => 1000,
                        'price' => 1000,
                        'tax' => 8
                    ]
                ],
                [
                    'already_include_tax' => true,
                    'discount_rate' => 10,
                    'discount_amount' => 1000,
                ],
                [
                    [
                        'rate' => 10,
                        'amount' => 0,
                        'tax' => 0
                    ],
                    [
                        'rate' => 8,
                        'amount' => 509,
                        'tax' => 41
                    ]
                ]
            ],
            [
                [
                    [
                        'name' => 'お茶',
                        'quantity' => 2,
                        'unit_price' => 100,
                        'price' => 200,
                        'tax' => 10
                    ],
                    [
                        'name' => '栗饅頭',
                        'quantity' => 1,
                        'unit_price' => 500,
                        'price' => 500,
                        'tax' => 10
                    ],
                    [
                        'name' => 'たこ焼き',
                        'quantity' => 1,
                        'unit_price' => 1000,
                        'price' => 1000,
                        'tax' => 8
                    ]
                ],
                [
                    'already_include_tax' => true,
                    // 'discount_rate' => 10,
                    // 'discount_amount' => 100,
                ],
                [
                    [
                        'rate' => 10,
                        'amount' => 636,
                        'tax' => 64
                    ],
                    [
                        'rate' => 8,
                        'amount' => 926,
                        'tax' => 74
                    ]
                ]
            ],
            [
                [
                    [
                        'name' => '栗饅頭',
                        'quantity' => 1,
                        'unit_price' => 700,
                        'price' => 700,
                        'tax' => 10
                    ],
                    [
                        'name' => 'たこ焼き',
                        'quantity' => 1,
                        'unit_price' => 1000,
                        'price' => 1000,
                        'tax' => 8
                    ]
                ],
                [
                    'already_include_tax' => true,
                    // 'discount_rate' => 10,
                    'discount_amount' => 1000,
                ],
                [
                    [
                        'rate' => 10,
                        'amount' => 0,
                        'tax' => 0
                    ],
                    [
                        'rate' => 8,
                        'amount' => 648,
                        'tax' => 52
                    ]
                ]
            ],

            // You can add more test cases here
        ];
    }
}
