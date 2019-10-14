<?php
declare(strict_types=1);

namespace Kafka\Protocol\Response;

use Kafka\Protocol\AbstractResponse;
use Kafka\Protocol\Response\Metadata\BrokerMetadata;
use Kafka\Protocol\Response\Metadata\TopicMetadata;
use Kafka\Protocol\TraitStructure\ToArrayTrait;

class MetadataResponse extends AbstractResponse
{
    use ToArrayTrait;

    /**
     * @var BrokerMetadata[] $broker
     */
    private $brokers;

    /**
     * @var TopicMetadata[] $topics
     */
    private $topics;

    /**
     * @return BrokerMetadata[]
     */
    public function getBrokers(): array
    {
        return $this->brokers;
    }

    /**
     * @param BrokerMetadata[] $brokers
     *
     * @return MetadataResponse
     */
    public function setBrokers(array $brokers): MetadataResponse
    {
        $this->brokers = $brokers;

        return $this;
    }

    /**
     * @return TopicMetadata[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param TopicMetadata[] $topics
     *
     * @return MetadataResponse
     */
    public function setTopics(array $topics): MetadataResponse
    {
        $this->topics = $topics;

        return $this;
    }
}
