<?php
namespace TRegx\CleanRegex\Internal\Prepared\Expression;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\DelimiterFinder;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Prepared\Quotable\RawQuotable;
use TRegx\CleanRegex\Internal\TrailingBackslash;

class Standard implements Expression
{
    use StrictInterpretation;

    /** @var string */
    private $pattern;
    /** @var string */
    private $flags;

    public function __construct(string $pattern, string $flags)
    {
        $this->pattern = $pattern;
        $this->flags = $flags;
    }

    protected function quotable(): Quotable
    {
        if (TrailingBackslash::hasTrailingSlash($this->pattern)) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
        return new RawQuotable($this->pattern);
    }

    protected function delimiter(): string
    {
        return (new DelimiterFinder())->chooseDelimiter($this->pattern);
    }

    protected function flags(): Flags
    {
        return new Flags($this->flags);
    }

    protected function undevelopedInput(): string
    {
        return $this->pattern;
    }
}
