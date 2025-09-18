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
     * Uncompress data
     *
     * @param $data
     *
     * @return mixed
     */
    public function uncompress($data): string;
}
