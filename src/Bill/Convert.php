<?php

namespace FrUtility\Bill;

use FrUtility\Extended\DictionaryKit;

class Convert
{
    /**
     * 請求書データの変換
     *
     * @param array $orders 各注文の情報が格納された配列
     *
     * @return array $billData 注文と税金の詳細を含む請求書データ
     */
    public function convertBillData(array $orders, array $setting = []): array
    {
        $setting = array_merge(
            [
                'already_include_tax' => true,
                'discount_rate' => 0,
                'discount_amount' => 0,
            ],
            $setting
        );

        // 初期値設定と単価、価格の計算
        $orders = $this->calculateOrderPrices($orders);

        // 税率ごとに集計する
        $taxes = $this->groupTaxes($orders, $setting);

        // 請求書データの組み立て
        $billData = [
            'orders' => $orders,
            'taxes' => $taxes
        ];
        return $billData;
    }

    /**
     * 注文の単価と価格を計算
     *
     * @param array $orders 各注文の情報が格納された配列
     *
     * @return array $orders 単価と価格が計算された注文情報
     */
    public function calculateOrderPrices(array $orders): array
    {
        $orders = array_map(function (array $order): array {
            $order = array_merge(
                [
                    'name' => '',
                    'quantity' => 0,
                    'unit_price' => 0,
                    'price' => 0,
                    'tax_rate' => 0
                ],
                $order
            );
            if ($order['unit_price']) {
                $order['price'] = $order['quantity'] * $order['unit_price'];
            }
            if ($order['price'] && $order['quantity'] != 0) {
                $order['unit_price'] = $order['price'] / $order['quantity'];
            }
            return $order;
        }, $orders);

        return $orders;
    }

    /**
     * 注文情報を元に税金の情報をグループ化
     *
     * @param array $orders 各注文の情報が格納された配列
     *
     * @return array $taxes 各税金の詳細情報を含む配列
     */
    public function groupTaxes(array $orders, $setting = []): array
    {
        $setting = array_merge(
            [
                'already_include_tax' => true,
                'discount_rate' => 0,
                'discount_amount' => 0,
            ],
            $setting
        );

        //
        $taxGroupedOrders = DictionaryKit::groupup($orders, 'tax_rate', null);

        //
        $discount_rate = $setting['discount_rate'] ?? 0;
        $left_discount_amount = $setting['discount_amount'] ?? 0;

        //
        $result = [];
        foreach ($taxGroupedOrders as $rate => $_order) {
            $total_price = array_sum(array_column($_order, 'price'));

            $discounted_price = $total_price;
            if ($discount_rate) {
                $discounted_price = $discounted_price - ($discounted_price * $discount_rate / 100);
            }

            if ($left_discount_amount) {
                if ($left_discount_amount < $discounted_price) {
                    $discounted_price = $discounted_price - $left_discount_amount;
                    $left_discount_amount = 0;
                } else {
                    $left_discount_amount = $left_discount_amount - $discounted_price;
                    $discounted_price = 0;
                }
            }

            $price_without_tax = (int) round($discounted_price / (1 + ($rate / 100)));
            $tax = (int) $discounted_price - $price_without_tax;
            $result[] = [
                'rate' => $rate,
                'amount' => $price_without_tax,
                'tax' => $tax,
                'discounted_price' => $discounted_price,
                'max_price' => $total_price
            ];
        }

        return $result;
    }
}
