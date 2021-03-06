<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\Messages\NonReplacedMessage;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\ThrowStrategy;

class CustomThrowStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow()
    {
        // given
        $strategy = new ThrowStrategy(InvalidArgumentException::class, new NonReplacedMessage());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Replacements were supposed to be performed, but subject doesn't match the pattern");

        // when
        $strategy->substitute('');
    }
}
