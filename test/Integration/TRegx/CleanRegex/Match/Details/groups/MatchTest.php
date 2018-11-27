<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details\groups;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Match;

class MatchTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetEmptyMatchedGroup()
    {
        // when
        $groups = pattern('([a-z]+)(?:\((\d*)\))?')
            ->match('sin(20) + cos() + tan')
            ->map(function (Match $match) {
                return $match->groups()->texts();
            });

        // then
        $expected = [
            ['sin', '20'], // braces value
            ['cos', ''],   // empty braces
            ['tan', null], // no braces
        ];
        $this->assertEquals($expected, $groups);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $groupNames = $match->groupNames();

                // then
                $this->assertEquals(['one', 'two'], $groupNames);
            });
    }

    /**
     * @test
     */
    public function shouldGetNamedGroup()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $groupNames = $match->namedGroups()->texts();

                // then
                $expected = [
                    'one' => 'first',
                    'two' => 'second'
                ];
                $this->assertEquals($expected, $groupNames);
            });
    }

    /**
     * @test
     */
    public function shouldNotHaveGroup()
    {
        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $has = $match->hasGroup('nonexistent');

                // then
                $this->assertFalse($has);
            });
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given
        pattern('(?<existing>first) and (?<two_existing>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $has = $match->hasGroup('existing');

                // then
                $this->assertTrue($has);
            });
    }

    /**
     * @test
     */
    public function shouldThrowOnInvalidGroupName()
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Group name must be an alphanumeric string starting with a letter, given: '2sd'");

        // given
        pattern('(?<one>first) and (?<two>second)')
            ->match('first and second')
            ->first(function (Match $match) {
                // when
                $match->hasGroup('2sd');
            });
    }
}