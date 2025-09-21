<?php

namespace Bibo\Mvc\Core\Interfaces;

interface CompressionInterface
{
    /**
     * Compress data
     *
     * @param $data
     *
     * @return mixed
     */
    public function compress($data): string;

    /**
     * Decompress data
     *
     * @param $data
     *
     * @return mixed
     */
    public function decompress($data): string;
}
