<?php
use PHPUnit\Framework\TestCase;
use FrUtility\Extended\TextKit;

class TextTest extends TestCase
{
    /**
     * TextKit::format
     */
    public function safeFormatProvider()
    {
        return[
            ['X集合ですW', ['X' => '全員', 'W' => '笑'], '全員集合です笑'],
            ['X集合ですW', ['a' => 'A'], 'X集合ですW'],
            ['', ['a' => 'A'], '']
        ];
    }

    /**
     * TextKit::getInitialOfKana
     */
    public function safeInitialProvider()
    {
        return[
            ['り', 'ら'],
            ['', '他'],
            ['@', '他'],
        ];
    }

    /**
     * TextKit::multiExplode
     */
    public function safeMultiExplodeProvider()
    {
        return[
            [['、'], '区切り文字が指定されていない場合は、元の文字列を単一要素の配列に変換して返す', ['区切り文字が指定されていない場合は', '元の文字列を単一要素の配列に変換して返す']],
            [['が', 'で'], '田中角栄が町おこしで長野県復興', ['田中角栄', '町おこし', '長野県復興']],
            [['が', 'で'], '', []],
            [[], '', []],
        ];
    }

    /**
     * TextKit::format
     * @dataProvider safeFormatProvider
     */
    public function testFormat正常系(string $text, array $format, string $expected): void
    {
        $result = TextKit::format($text, $format);
        $this->assertEquals($expected, $result);
    }

    /**
     * TextKit::getInitialOfKana
     * @dataProvider safeInitialProvider
     */
    public function testGetInitialOfKana正常系(string $text, string $expected): void
    {
        $result = TextKit::getInitialOfKana($text);
        $this->assertEquals($expected, $result);
    }

    /**
     * TextKit::multiExplode
     * @dataProvider safeMultiExplodeProvider
     */
    public function testMultiExplode正常系(array $separators, string $text, array $expected): void
    {
        $result = TextKit::multiExplode($separators, $text);
        $this->assertEquals($expected, $result);
    }
}
