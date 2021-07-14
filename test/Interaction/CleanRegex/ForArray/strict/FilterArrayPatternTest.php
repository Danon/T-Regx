<?php
namespace Test\Interaction\TRegx\CleanRegex\ForArray\strict;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use Test\Utils\Internal;
use TRegx\CleanRegex\ForArray\FilterArrayPattern;
use TRegx\DataProvider\CrossDataProviders;

/**
 * @covers \TRegx\CleanRegex\ForArray\FilterArrayPattern
 */
class FilterArrayPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider filterMethods
     * @param string $method
     * @param null|int|array|callable|resource $listElement
     * @param string $type
     */
    public function test(string $method, $listElement, string $type)
    {
        // given
        $filterArrayPattern = new FilterArrayPattern(Internal::pcre(''), ['Foo', $listElement], true);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Only elements of type 'string' can be filtered, but $type given");

        // when
        $filterArrayPattern->$method();
    }

    public function filterMethods(): array
    {
        return CrossDataProviders::cross(
            [['filter'], ['filterAssoc']],
            DataProviders::allPhpTypes('string', 'int')
        );
    }
}
