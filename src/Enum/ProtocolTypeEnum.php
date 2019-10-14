<?php
declare(strict_types=1);

namespace Kafka\Enum;

/**
 * Class ProtocolTypeEnum
 *
 * @package Kafka\Enums
 */
class ProtocolTypeEnum extends AbstractEnum
{
    /**
     * @message("C")
     */
    public const B8 = 1;

    /**
     * @message("n")
     */
    public const B16 = 2;

    /**
     * @message("N")
     */
    public const B32 = 4;

    /**
     * @message("N2")
     */
    public const B64 = 8;
}