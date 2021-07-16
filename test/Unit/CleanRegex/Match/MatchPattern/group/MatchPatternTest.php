<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::group
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // given
        $pattern = new MatchPattern(Internal::pcre('//'), '');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, but array (0) given');

        // when
        $pattern->group([]);
    }
}
