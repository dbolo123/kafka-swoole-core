<?php
declare(strict_types=1);

namespace KafkaTest\ConnectedProtocol;

use Kafka\Enum\ProtocolErrorEnum;
use Kafka\Protocol\Request\FetchRequest;
use Kafka\Protocol\Request\Fetch\PartitionsFetch;
use Kafka\Protocol\Request\Fetch\TopicsFetch;
use Kafka\Protocol\Type\Int32;
use Kafka\Protocol\Type\Int64;
use Kafka\Protocol\Type\String16;
use Kafka\Server\SocketServer;
use KafkaTest\AbstractProtocolTest;
use Swoole\Client;


final class FetchTest extends AbstractProtocolTest
{
    /**
     * @var FetchRequest $protocol
     */
    private $protocol;

    /**
     * @before
     */
    public function newProtocol()
    {
        $this->protocol = new FetchRequest();
    }

    /**
     * @author caiwenhui
     * @group  connectedEncode
     * @throws \Kafka\Exception\ProtocolTypeException
     * @throws \ReflectionException
     */
    public function testEncode()
    {
        /** @var FetchRequest $protocol */
        $protocol = $this->protocol;
        $protocol->setReplicaId(Int32::value(-1))
                 ->setMaxWaitTime(Int32::value(100))
                 ->setMinBytes(Int32::value(1000))
                 ->setTopics([
                     (new TopicsFetch())->setTopic(String16::value('caiwenhui'))
                                        ->setPartitions([
                                            (new PartitionsFetch())->setPartition(Int32::value(0))
                                                                   ->setFetchOffset(Int64::value(0))
                                                                   ->setPartitionMaxBytes(Int32::value(65536))
                                        ])
                 ]);

        $data = $protocol->pack();
        $expected = '000000450001000000000001000c6b61666b612d73776f6f6c65ffffffff00000064000003e800000001000963616977656e6875690000000100000000000000000000000000010000';
        $this->assertSame($expected, bin2hex($data));

        return $data;
    }

    /**
     * @author  caiwenhui
     * @depends testEncode
     *
     * @param string $data
     */
    public function testSend(string $data)
    {
        /** @var FetchRequest $protocol */
        $protocol = $this->protocol;
        $data = SocketServer::getInstance()->run('mkafka4', 9092, function () use ($data) {
            return $data;
        }, function (string $data, Client $client) use ($protocol) {
            $protocol->response->unpack($data, $client);
        });

        $this->assertIsArray($data);
        foreach ($protocol->response->getResponses() as $response) {
            foreach ($response->getPartitionResponses() as $partitionResponse){
                $this->assertEquals(ProtocolErrorEnum::NO_ERROR, $partitionResponse->getPartitionHeader()->getErrorCode()->getValue());
            }
        }
    }
}
