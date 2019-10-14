<?php
declare(strict_types=1);

namespace Kafka\Protocol\Request\SyncGroup;

use Kafka\Protocol\CommonRequest;
use Kafka\Protocol\Type\String16;

class AssignmentsSyncGroup
{
    /**
     * The ID of the member to assign.
     *
     * @var String16 $memberId
     */
    private $memberId;

    /**
     * The member assignment.
     *
     * @var GroupAssignmentsSyncGroup $assignment
     */
    private $assignment;

    /**
     * @return String16
     */
    public function getMemberId(): String16
    {
        return $this->memberId;
    }

    /**
     * @param String16 $memberId
     *
     * @return AssignmentsSyncGroup
     */
    public function setMemberId(String16 $memberId): AssignmentsSyncGroup
    {
        $this->memberId = $memberId;

        return $this;
    }

    /**
     * @return GroupAssignmentsSyncGroup[]
     */
    public function getAssignment(): array
    {
        return $this->assignment;
    }

    /**
     * @param GroupAssignmentsSyncGroup[] $assignment
     *
     * @return AssignmentsSyncGroup
     */
    public function setAssignment(array $assignment): AssignmentsSyncGroup
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * @param $protocol
     *
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function onAssignment(&$protocol)
    {
        $commentRequest = new CommonRequest();
        $data = $commentRequest->packProtocol(MessageProduce::class, $this->message);
        $this->setMessageSetSize(Int32::value(strlen($data)));
        $protocol .= pack(Int32::getWrapperProtocol(), $this->getMessageSetSize()->getValue());
    }
}
