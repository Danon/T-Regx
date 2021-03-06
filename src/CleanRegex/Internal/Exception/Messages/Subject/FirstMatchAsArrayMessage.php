<?php
namespace TRegx\CleanRegex\Internal\Exception\Messages\Subject;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;

class FirstMatchAsArrayMessage implements NotMatchedMessage
{
    public function getMessage(): string
    {
        return 'Expected to get the first match as array, but subject was not matched';
    }
}
