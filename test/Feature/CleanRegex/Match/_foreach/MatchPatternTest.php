<?php
namespace Test\Feature\TRegx\CleanRegex\Match\_foreach;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Match\MatchPattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIterateMatch()
    {
        // given
        $result = [];

        // when
        foreach ($this->match() as $match) {
            $result[] = $match->text();
        }

        // then
        $this->assertSame(['127', '0', '0', '1'], $result);
    }

    /**
     * @test
     */
    public function shouldIterateMatch_asInt()
    {
        // given
        $result = [];

        // when
        foreach ($this->match()->asInt() as $digit) {
            $result[] = $digit;
        }

        // then
        $this->assertSame([127, 0, 0, 1], $result);
    }

    /**
     * @test
     */
    public function shouldIterateMatch_remaining_forEach()
    {
        // given
        $result = [];

        // when
        foreach ($this->match()->remaining(Functions::constant(true)) as $match) {
            $result[] = $match->text();
        }

        // then
        $this->assertSame(['127', '0', '0', '1'], $result);
    }

    private function match(): MatchPattern
    {
        return pattern('(\d+)')->match('127.0.0.1');
    }
}
