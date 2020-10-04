<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\ReplacementWithUnmatchedGroupMessage;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Replace\NonReplaced\ComputedMatchStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ConstantReturnStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\MatchRs;
use TRegx\CleanRegex\Replace\NonReplaced\ThrowStrategy;

class OptionalStrategySelectorImpl implements OptionalStrategySelector
{
    /** @var GroupFallbackReplacer */
    private $replacer;
    /** @var string|int */
    private $nameOrIndex;
    /** @var GroupMapper */
    private $mapper;

    public function __construct(GroupFallbackReplacer $replacer, $nameOrIndex, GroupMapper $mapper)
    {
        $this->replacer = $replacer;
        $this->nameOrIndex = $nameOrIndex;
        $this->mapper = $mapper;
    }

    public function orReturn($substitute): string
    {
        return $this->replace(new ConstantReturnStrategy($substitute));
    }

    public function orElse(callable $substituteProducer): string
    {
        return $this->replace(new ComputedMatchStrategy($substituteProducer, "orElse"));
    }

    public function orThrow(string $exceptionClassName = GroupNotMatchedException::class): string
    {
        return $this->replace(new ThrowStrategy($exceptionClassName, new ReplacementWithUnmatchedGroupMessage($this->nameOrIndex)));
    }

    public function replace(MatchRs $substitute): string
    {
        return $this->replacer->replaceOrFallback($this->nameOrIndex, $this->mapper, $substitute);
    }
}
