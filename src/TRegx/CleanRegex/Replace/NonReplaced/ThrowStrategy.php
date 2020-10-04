<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Match\Details\Match;

class ThrowStrategy implements SubjectRs, MatchRs
{
    /** @var SignatureExceptionFactory */
    private $factory;

    public function __construct(string $className, NotMatchedMessage $message)
    {
        $this->factory = new SignatureExceptionFactory($className, $message);
    }

    public function substitute(string $subject): string
    {
        throw $this->factory->create($subject);
    }

    public function substituteGroup(Match $match): string
    {
        throw $this->factory->create($match->subject());
    }
}