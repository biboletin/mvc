<?php

namespace Biboletin\Mvc;

class Benchmark
{
    private float $startTime;
    private float $endTime;

    private float $totalTime;

    private float $startMemory;
    private float $endMemory;
    private float $totalMemory;
    public function __construct()
    {
    }

    public function start(): void
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage(true);
    }

    public function stop(): void
    {
        $this->endTime = microtime(true);
        $this->endMemory = memory_get_usage(true);
        $this->totalTime = (float) gmdate('i:s', floor($this->endTime - $this->startTime) / 1000);
        $this->totalMemory = ($this->endMemory - $this->startMemory);
    }

    public function result(): array
    {
        return [
            'start_time' => $this->formatSeconds($this->startTime),
            'end_time' => $this->formatSeconds($this->endTime),
            'total_time' => $this->formatSeconds($this->totalTime),
            'start_memory' => $this->formatBytes($this->startMemory),
            'end_memory' => $this->formatBytes($this->endMemory),
            'total_memory' => $this->formatBytes($this->totalMemory),
        ];
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes === true ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function formatSeconds($seconds)
    {
        $hours = 0;
        $milliseconds = str_replace('0.', '', $seconds - floor($seconds));

        if ($seconds >= 3600) {
            $hours = floor($seconds / 3600);
        }
        $seconds = $seconds % 3600;

        return str_pad($hours, 2, '0', STR_PAD_LEFT)
            . gmdate(':i:s', $seconds)
            . ($milliseconds === true ? '.' . $milliseconds : '')
            ;
    }

    public function __destruct()
    {
    }
}
