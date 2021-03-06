<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Prepared\Expression;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Prepared\Expression\Identity;

class IdentityTest extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        // given
        $input = new Definition('/foo/', 'foo');
        $interpretation = new Identity($input);

        // when
        $actual = $interpretation->definition();

        // then
        $this->assertSame($input, $actual);
    }
}
