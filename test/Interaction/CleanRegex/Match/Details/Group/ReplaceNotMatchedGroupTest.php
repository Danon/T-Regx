<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\GroupExceptionFactory;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\Group\ReplaceNotMatchedGroup;

class ReplaceNotMatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldNotGet_modifiedSubject()
    {
        // given
        $matchGroup = $this->matchGroup('first');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call modifiedSubject() for group 'first', but the group was not matched");

        // when
        $matchGroup->modifiedSubject();
    }

    /**
     * @test
     */
    public function shouldNotGet_modifiedOffset()
    {
        // given
        $matchGroup = $this->matchGroup('first');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call modifiedOffset() for group 'first', but the group was not matched");

        // when
        $matchGroup->modifiedOffset();
    }

    /**
     * @test
     */
    public function shouldNotGet_byteModifiedOffset()
    {
        // given
        $matchGroup = $this->matchGroup('first');

        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call byteModifiedOffset() for group 'first', but the group was not matched");

        // when
        $matchGroup->byteModifiedOffset();
    }

    private function matchGroup(string $group): ReplaceNotMatchedGroup
    {
        /** @var Subjectable $subject */
        /** @var GroupDetails $groupDetails */
        /** @var NotMatchedOptionalWorker $worker */
        $subject = $this->createMock(Subjectable::class);
        $groupDetails = $this->createMock(GroupDetails::class);
        $worker = $this->createMock(NotMatchedOptionalWorker::class);
        return new ReplaceNotMatchedGroup($groupDetails, new GroupExceptionFactory($subject, $group), $worker, '$unused');
    }
}
