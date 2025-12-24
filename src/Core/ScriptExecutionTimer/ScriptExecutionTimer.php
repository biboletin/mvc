<?php

namespace Bibo\Mvc\Core\ScriptExecutionTimer;

use InvalidArgumentException;

/**
 * Class ScriptExecutionTimer
 *
 * A simple utility class to measure script execution time and memory usage.
 * It provides methods to start and stop timers, retrieve durations, memory usage,
 * and send Server-Timing headers.
 */
class ScriptExecutionTimer
{
    /**
     * Array to hold start times of timers.
     *
     * @var array
     */
    protected array $timers = [];

    /**
     * Array to hold durations of timers in milliseconds.
     *
     * @var array
     */
    protected array $durations = [];

    /**
     * Array to hold memory usage at the start of timers.
     *
     * @var array
     */
    protected array $memoryStart = [];

    /**
     * Array to hold memory usage during timers.
     *
     * @var array
     */
    protected array $memoryUsage = [];

    /**
     * Array to hold peak memory usage during timers.
     *
     * @var array
     */
    protected array $memoryPeak = [];

    /**
     * Starts a timer with the given name.
     *
     * @param string $name The name of the timer.
     *
     * @return void
     */
    public function start(string $name): void
    {
        $this->timers[$name] = microtime(true);
        $this->memoryStart[$name] = memory_get_usage(true);
    }

    /**
     * Stops the timer with the given name and calculates the duration and memory usage.
     *
     * @param string $name The name of the timer.
     *
     * @return void
     *
     * @throws InvalidArgumentException If the timer with the given name was not started.
     */
    public function stop(string $name): void
    {
        if (!isset($this->timers[$name])) {
            throw new InvalidArgumentException('Timer with name ' . $name . ' has not been started.');
        }

        $end = microtime(true);
        $duration = ($end - $this->timers[$name]) * 1000;
        $this->durations[$name] = $duration;

        $currentMemory = memory_get_usage(true);
        $this->memoryUsage[$name] = max(0, $currentMemory - $this->memoryStart[$name]);
        $this->memoryPeak[$name] = memory_get_peak_usage(true);
    }

    /**
     * Retrieves the duration of the timer with the given name.
     *
     * @param string $name The name of the timer.
     *
     * @return float The duration in milliseconds.
     *
     * @throws InvalidArgumentException If the timer with the given name was not stopped or does not exist.
     */
    public function getDuration(string $name): float
    {
        if (!isset($this->durations[$name])) {
            throw new InvalidArgumentException('Timer with name ' . $name . ' has not been stopped or does not exist.');
        }
        return $this->durations[$name];
    }

    /**
     * Generates the Server-Timing header value based on the recorded timers.
     *
     * @return string The Server-Timing header value.
     */
    public function getServerTimingHeader(): string
    {
        $parts = [];
        foreach ($this->durations as $name => $duration) {
            $dur = round($duration, 2);
            $mem = $this->getMemoryInfo();
            $parts[] = sprintf(
                '%s;dur=%.2f ms;desc="Memory Usage: Real: %s, Allocated: %s, Peak: %s"',
                $name,
                $dur,
                $mem['real'],
                $mem['allocated'],
                $mem['peak']
            );
        }
        return implode(', ', $parts);
    }

    /**
     * Sends the Server-Timing and X-Memory-Usage headers.
     *
     * @return void
     *
     * @throws InvalidArgumentException If headers have already been sent.
     */
    public function sendHeader(): void
    {
        if (headers_sent()) {
            throw new InvalidArgumentException('Headers have already been sent, cannot send headers.');
        }

        $serverTiming = $this->getServerTimingHeader();
        if ($serverTiming) {
            header('Server-Timing: ' . $serverTiming);
        }

        $memoryUsageHeader = $this->getMemoryUsageHeader();
        if ($memoryUsageHeader) {
            header('X-Memory-Usage: ' . $memoryUsageHeader);
        }
    }

    /**
     * Returns the total memory usage (current and peak) as a human-readable string.
     *
     * @return string
     */
    public function getMemoryUsageHeader(): string
    {
        $currentUsage = memory_get_usage(true);
        $peakUsage = memory_get_peak_usage(true);

        return sprintf(
            'Current: %.2f MB; Peak: %.2f MB',
            $currentUsage / 1024 / 1024,
            $peakUsage / 1024 / 1024
        );
    }

    /**
     * Resets all timers and memory usage data.
     *
     * @return void
     */
    public function reset(): void
    {
        $this->timers = [];
        $this->durations = [];
        $this->memoryStart = [];
        $this->memoryUsage = [];
        $this->memoryPeak = [];
    }

    /**
     * Checks if a timer with the given name exists.
     *
     * @param string $name The name of the timer.
     *
     * @return bool True if the timer exists, false otherwise.
     */
    public function hasTimer(string $name): bool
    {
        return isset($this->timers[$name]) || isset($this->durations[$name]);
    }

    /**
     * Returns an array of all timer names.
     *
     * @return array
     */
    public function getTimerNames(): array
    {
        return array_keys($this->timers);
    }

    /**
     * Returns an array of all duration names.
     *
     * @return array
     */
    public function getDurationNames(): array
    {
        return array_keys($this->durations);
    }

    /**
     * Returns all timers with their start times.
     *
     * @return array
     */
    public function getAllTimers(): array
    {
        return $this->timers;
    }

    /**
     * Returns all durations with their values in milliseconds.
     *
     * @return array
     */
    public function getAllDurations(): array
    {
        return $this->durations;
    }

    /**
     * Format bytes to human-readable format
     *
     * @param int $bytes
     *
     * @return string
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $countUnits = count($units);

        while ($bytes >= 1024 && $i < $countUnits - 1) {
            $bytes /= 1024;
            $i++;
        }

        return sprintf('%.2f %s', $bytes, $units[$i]);
    }

    /**
     * Get real memory info
     *
     * @return array
     */
    private function getMemoryInfo(): array
    {
        return [
            // actual used memory
            'real' => $this->formatBytes(memory_get_usage(false)),
            // allocated by PHP engine
            'allocated' => $this->formatBytes(memory_get_usage(true)),
            // peak actual usage
            'peak' => $this->formatBytes(memory_get_peak_usage(false)),
        ];
    }
}
