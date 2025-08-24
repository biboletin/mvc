<?php

namespace Bibo\Mvc\Core\Wrapper\Curl;

use Bibo\Mvc\Core\Enums\CurlStrategyType;
use Bibo\Mvc\Core\Interfaces\CurlInterface;
use InvalidArgumentException;

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
