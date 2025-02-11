<?php
declare(strict_types=1);

namespace Kafka\Subscriber;

use App\App;
use App\Subscriber\ApiSubscriber;
use Kafka\Command\DescribeGroupsCommand;
use Kafka\Command\KafkaLagCommand;
use Kafka\Command\ListOffsetsCommand;
use Kafka\Command\OffsetCommitCommand;
use Kafka\Command\OffsetFetchCommand;
use Kafka\Command\ProducerCommand;
use Kafka\Command\RetranCommand;
use Kafka\Command\RpcCommand;
use Kafka\Command\StartCommand;
use Kafka\Event\BootAfterEvent;
use Kafka\Event\BootBeforeEvent;
use Kafka\Event\StartBeforeEvent;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use \App\Subscriber\CoreSubscriber as AppCoreSubscriber;

/**
 * Class BootSubscriber
 *
 * @package Kafka\Subscriber
 */
class BootSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BootBeforeEvent::NAME => 'onBootBefore',
            BootAfterEvent::NAME  => 'onBootAfter',
        ];
    }

    public function onBootBefore(): void
    {
        $dotenv = new Dotenv();
        $dotenv->overload(KAFKA_SWOOLE_ROOT . DIRECTORY_SEPARATOR . '.env');

        APP::$translator = \Kafka\I18N\I18N::getInstance();
//        set_exception_handler([BaseException::class, BaseException::$exception_function_name]);
    }

    /**
     * @throws \Exception
     */
    public function onBootAfter(): void
    {
        // start App
        App::boot();
        $this->registerSubscriber();
        $this->registerCommand();
    }

    /**
     * @throws \Exception
     */
    private function registerCommand(): void
    {
        App::$application->add(new ProducerCommand());
        App::$application->add(new StartCommand());
        App::$application->add(new RpcCommand());
        App::$application->add(new DescribeGroupsCommand());
        App::$application->add(new RetranCommand());
        App::$application->add(new OffsetCommitCommand());
        App::$application->add(new ListOffsetsCommand());
        App::$application->add(new OffsetFetchCommand());
        App::$application->add(new KafkaLagCommand());
        App::$application->run();
    }

    private function registerSubscriber(): void
    {
        App::$dispatcher->addSubscriber(new StartSubscriber());
        App::$dispatcher->addSubscriber(new CoreSubscriber());
        App::$dispatcher->addSubscriber(new ApiSubscriber());
        App::$dispatcher->addSubscriber(new StepSubscriber());
        App::$dispatcher->addSubscriber(new SinkerSubscriber());
        App::$dispatcher->addSubscriber(new AppCoreSubscriber());
    }
}