<?php
namespace Test\Integration\TRegx\CleanRegex\Replace\Callback;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReplacementException;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\ReplaceDetail;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplaceCallbackObject;
use TRegx\SafeRegex\preg;

class ReplaceCallbackObjectTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateReplaceMatchObject()
    {
        // given
        $pattern = '/[a-z]+/';
        $subject = '...hello there, general kenobi';

        $object = $this->create($pattern, $subject, 3, function (ReplaceDetail $match) use ($subject) {
            // then
            $this->assertEquals(['hello', 'there', 'general', 'kenobi'], $match->all());
            $this->assertEquals($subject, $match->subject());
            $this->assertEquals('hello', $match->text());
            $this->assertEquals(0, $match->index());
            $this->assertEquals(3, $match->offset());
            $this->assertEquals(3, $match->modifiedOffset());
            return 'replacement';
        });

        // when
        $callback = $object->getCallback();
        $callback(['hello']);
    }

    /**
     * @test
     */
    public function shouldReturnCallbackResult()
    {
        // given
        $pattern = '/[a-z]+/';
        $subject = '...hello there, general kenobi';

        $object = $this->create($pattern, $subject, 3, Functions::constant('replacement'));

        // when
        $callback = $object->getCallback();
        $result = $callback(['hello']);

        // then
        $this->assertEquals('replacement', $result);
    }

    /**
     * @test
     */
    public function shouldModifyOffset()
    {
        // given
        $pattern = '/[a-z]+/';
        $subject = '.cat .fish .horse .leopard .cat';

        $offsets = [];
        $modifiedOffsets = [];

        $object = $this->create($pattern, $subject, 5, function (ReplaceDetail $match) use (&$offsets, &$modifiedOffsets) {
            $offsets[] = $match->offset();
            $modifiedOffsets[] = $match->modifiedOffset();
            return 'tiger';
        });

        // when
        $callback = $object->getCallback();
        $callback(['cat']);
        $callback(['fish']);
        $callback(['horse']);
        $callback(['leopard']);
        $callback(['cat']);

        // then
        $this->assertEquals([1, 6, 12, 19, 28], $offsets);
        $this->assertEquals([1, 8, 15, 22, 29], $modifiedOffsets);
    }

    /**
     * @test
     */
    public function shouldThrow_OnNonStringReplacement()
    {
        // given
        $object = $this->create('//', 'foo bar', 1, Functions::constant(2));

        // then
        $this->expectException(InvalidReplacementException::class);
        $this->expectExceptionMessage('Invalid callback() callback return type. Expected string, but integer (2) given');

        // when
        $callback = $object->getCallback();
        $callback(['foo']);
    }

    /**
     * @test
     */
    public function shouldNotThrow_OnMatchGroupReplacement()
    {
        // given
        $object = $this->create('/(?<g>\d)cm/', 'foo 2cm bar', 1, function (ReplaceDetail $match) {
            return $match->group('g');
        });

        // when
        $callback = $object->getCallback();
        $result = $callback(['2cm']);

        // then
        $this->assertEquals('2', $result);
    }

    private function create(string $pattern, string $subject, int $limit, callable $callback): ReplaceCallbackObject
    {
        return new ReplaceCallbackObject($callback, new Subject($subject), $this->analyzePattern($pattern, $subject), $limit, new MatchStrategy());
    }

    private function analyzePattern($pattern, $subject): RawMatchesOffset
    {
        preg::match_all($pattern, $subject, $matches, \PREG_OFFSET_CAPTURE);
        return new RawMatchesOffset($matches);
    }
}
