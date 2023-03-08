<?php


class VendingMachine
{
    private $coinTypes;
    private $prices;
    function __construct()
    {
        $this->coinTypes = [500, 100, 50, 10];
        $this->prices = array(
            "cola" => 120,
            "coffee" => 150,
            "energy_drink" => 210,
        );
    }
    public function buy(array $coins, string $menu): string
    {
        //投入された硬貨の合計を計算する
        $total = $this->calc_sum_insert_coin($coins);
        //メニューに基づいた値段を設定する
        $prices = $this->prices;
        //メニューが存在するかチェックする
        if (!array_key_exists($menu, $prices)) {
            return "メニューがありません";
        }

        $price = $prices[$menu];
        //お釣りを算出する
        $change = $total - $price;
        //お釣りを渡すための硬貨を計算する

        // お釣りを渡すための硬貨の枚数を計算する
        $change_coins = $this->calc_change_coin($change);

        //お釣りを返す
        return $this->convert_change_string($change, $change_coins);
    }

    private function calc_change_coin($change)
    {
        // コインの種類
        $coinTypes = $this->coinTypes;
        $change_coins = array();
        $remain_change = $change;
        foreach ($coinTypes as $coinType) {
            $change_coins[$coinType] = floor($remain_change / $coinType);
            $remain_change = $remain_change % $coinType;
        }
        //枚数が0の硬貨を削除する
        $change_coins = array_filter($change_coins, function ($value) {
            return $value > 0;
        });
        return $change_coins;
    }

    private function convert_change_string($change, $change_coins)
    {
        if ($change > 0) {
            // お釣りを返すための硬貨の枚数を文字列に変換する
            $return_str_arr = array_map(function ($value, $key) {
                return "{$key} {$value}";
            }, $change_coins, array_keys($change_coins));
            $return_str = implode(" ", $return_str_arr);
            return $return_str;
        } elseif ($change == 0) {
            return "nochange";
        }
        return "お金が足りません";
    }

    private function calc_sum_insert_coin(array $coins): int
    {
        $total = 0;
        foreach ($coins as $coin_type => $coin_count) {
            $total += $coin_type * $coin_count;
        }
        return $total;
    }
}
