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
    public function convertBillData(array $orders): array
    {
        // 初期値設定と単価、価格の計算
        $orders = $this->calculateOrderPrices($orders);

        // 税率ごとに集計する
        $taxes = $this->groupTaxes($orders);

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
                    'tax' => 0
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
    public function groupTaxes(array $orders): array
    {
        $taxes = [];
        $taxGroupedOrders = DictionaryKit::groupup($orders, 'tax', null);
        foreach ($taxGroupedOrders as $taxRate => $orderGroup) {
            $amount = array_sum(array_column($orderGroup, 'price'));
            $taxes[] = [
                'rate' => $taxRate,
                'amount' => $amount,
                'tax' => $amount * $taxRate / 100
            ];
        }

        return $taxes;
    }
}
