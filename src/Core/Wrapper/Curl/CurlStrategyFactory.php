<?php

namespace Bibo\Core\Wrapper\Curl;

use Bibo\Core\Enum\CurlStrategyType;
use Bibo\Core\Interfaces\CurlInterface;
use InvalidArgumentException;
use Bibo\Core\Wrapper\Curl\CurlSingleWrapper;
use Bibo\Core\Wrapper\Curl\CurlMultiWrapper;

class CurlStrategyFactory
{
    /**
     * Creates and returns an instance of a cURL implementation based on the provided strategy enum.
     *
     * @param CurlStrategyType $type Strategy type to create.
     *
     * @return CurlInterface
     * @throws InvalidArgumentException
     */
    public static function create(CurlStrategyType $type = CurlStrategyType::SINGLE): CurlInterface
    {
        return match ($type) {
            CurlStrategyType::MULTI => (function () {
                $curl = new CurlMultiWrapper();
                $curl->init();
                return $curl;
            })(),
            CurlStrategyType::SINGLE => new CurlSingleWrapper(),
        };
    }
}
