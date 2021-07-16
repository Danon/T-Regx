<?php
namespace Test\Unit\TRegx\CleanRegex\Match\MatchPattern\group_offset\only\only2;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Match\MatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern::group
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_groups()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'Nice Matching Pattern');

        // when
        $twoGroups = $pattern->group('two')->offsets()->only(2);
        $restGroups = $pattern->group('rest')->offsets()->only(2);

        // then
        $this->assertSame([0, 5], $twoGroups);
        $this->assertSame([2, 7], $restGroups);
    }

    /**
     * @test
     */
    public function shouldGet_unmatchedGroups()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<hour>\d\d)?:(?<minute>\d\d)?'), 'First->11:__   Second->__:12   Third->13:32');

        // when
        $hours = $pattern->group('hour')->offsets()->only(2);
        $minutes = $pattern->group('minute')->offsets()->only(2);

        // then
        $this->assertSame([7, null], $hours);
        $this->assertSame([null, 26], $minutes);
    }

    /**
     * @test
     */
    public function shouldReturnEmptyArray_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<two>[A-Z][a-z])?(?<rest>[a-z]+)'), 'NOT MATCHING');

        // when
        $groups = $pattern->group('two')->offsets()->only(2);

        // then
        $this->assertEmpty($groups);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->offsets()->only(2);
    }

    /**
     * @test
     */
    public function shouldThrow_onNonExistentGroup_onNotMatchedSubject()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<existing>[a-z]+)'), 'NOT MATCHING');

        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'missing'");

        // when
        $pattern->group('missing')->offsets()->only(2);
    }

    /**
     * @test
     */
    public function shouldThrow_onInvalidGroupName()
    {
        // given
        $pattern = new MatchPattern(Internal::pattern('(?<existing>[a-z]+)'), 'matching');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string, not starting with a digit, but '2invalid' given");

        // when
        $pattern->group('2invalid')->offsets()->only(2);
    }
}
