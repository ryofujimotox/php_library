<?php

use PHPUnit\Framework\TestCase;
use FrUtility\Extended\ArrayKit;

class ArrayKitTest extends TestCase {
    public function testCusSliceFirla() {
        $array = [1, 2, 3, 4, 5];
        $first = 1;
        $last = 2;
        $want = [1, 4, 5];
        $sliced_array = ArrayKit::slice_firla($array, $first, $last);
        $this->assertCount($first + $last, $sliced_array);
        $this->assertSame($want, $sliced_array);

        // 要素の大きさと順番は関係ないこと
        $array = [20, 8, 2, 9];
        $first = 1;
        $last = 2;
        $want = [20, 2, 9];
        $sliced_array = ArrayKit::slice_firla($array, $first, $last);
        $this->assertCount($first + $last, $sliced_array);
        $this->assertSame($want, $sliced_array);

        // 選択値が超えていても大丈夫
        $array = [20, 8, 2, 9];
        $first = 3;
        $last = 2;
        $want = [20, 8, 2, 2, 9];
        $sliced_array = ArrayKit::slice_firla($array, $first, $last);
        $this->assertCount($first + $last, $sliced_array);
        $this->assertSame($want, $sliced_array);
    }
}
