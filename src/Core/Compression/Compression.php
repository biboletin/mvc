<?php

namespace Bibo\Mvc\Core\Compression;

use Bibo\Mvc\Core\Interfaces\CompressionInterface;

class Compression implements CompressionInterface
{
    /**
     * Compress data
     *
     * @param $data
     *
     * @return mixed
     */
    public function compress($data): mixed
    {
        // Implement a compression strategy here (e.g., gzip, zlib); return the compressed string or throw on failure.
    }

    /**
     * Uncompress data
     *
     * @param $data
     *
     * @return mixed
     */
    public function decompress($data): mixed
    {
        // Implement the corresponding decompression strategy (e.g., gunzip); return the original string or throw on failure.
    }
}
