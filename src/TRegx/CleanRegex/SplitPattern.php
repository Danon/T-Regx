<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Split\FilteredSplitPattern;
use TRegx\SafeRegex\preg;

class SplitPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(InternalPattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function filter(): FilteredSplitPattern
    {
        return new FilteredSplitPattern($this->pattern, $this->subject);
    }

    /**
     * @return string[]
     */
    public function ex(): array
    {
        return $this->split(false);
    }

    /**
     * @return string[]
     */
    public function inc(): array
    {
        return $this->split(true);
    }

    private function split(bool $includeDelimiter): array
    {
        $flag = $includeDelimiter ? PREG_SPLIT_DELIM_CAPTURE : 0;
        return preg::split($this->pattern->pattern, $this->subject, -1, $flag);
    }
}