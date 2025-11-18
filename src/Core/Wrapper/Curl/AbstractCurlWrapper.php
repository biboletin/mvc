<?php

namespace Bibo\Mvc\Core\Wrapper\Curl;

use Bibo\Mvc\Core\Interfaces\CurlExtendedInterface;
use CurlHandle;

abstract class AbstractCurlWrapper implements CurlExtendedInterface
{
    /**
     * The cURL handle resource
     *
     * @var CurlHandle|null $handle The cURL handle resource
     */
    protected ?CurlHandle $handle = null;

    /**
     * Array of cURL options set on this handle
     *
     * @var array<int, mixed> $options Array of cURL options
     */
    protected array $options = [];

    /**
     * Set a cURL option on this handle.
     *
     * @param int   $option The cURL option constant (e.g., CURLOPT_URL)
     * @param mixed $value  The value to set for the option
     *
     * @return AbstractCurlWrapper
     */
    public function setOption(int $option, mixed $value): self
    {
        $this->options[$option] = $value;

        if ($this->handle instanceof CurlHandle) {
            curl_setopt($this->handle, $option, $value);
        }

        return $this;
    }

    /**
     * Get the value of a specific cURL option set on this handle.
     *
     * @param int $option The cURL option constant (e.g., CURLOPT_URL)
     *
     * @return mixed The value of the specified option, or null if not set
     */
    public function getOption(int $option): mixed
    {
        return $this->options[$option] ?? null;
    }

    /**
     * Get the underlying cURL handle resource.
     *
     * @return CurlHandle The cURL handle resource
     */
    public function getHandle(): CurlHandle
    {
        return $this->handle;
    }

    /**
     * Set multiple cURL options on this handle.
     *
     * @param array<int, mixed> $options Array of cURL options to set
     *
     * @return AbstractCurlWrapper
     */
    public function setOptions(array $options): self
    {
        foreach ($options as $option => $value) {
            $this->setOption($option, $value);
        }

        return $this;
    }

    /**
     * Get all cURL options currently set on this handle.
     *
     * Returns an array of all cURL constants and their values.
     * Note: This method currently returns all defined constants but should be
     * modified to return only the options set on this handle.
     *
     * @return array<string, mixed> Array of cURL options and their values
     */
    public function getOptions(): array
    {
        $constants = get_defined_constants(true);
        $curlConstantInts = array_flip($constants['curl']);
        $options = [];

        foreach ($this->options as $key => $value) {
            if (isset($curlConstantInts[$key])) {
                $options[$curlConstantInts[$key]] = $value;
            }
        }

        unset($curlConstantInts);

        return $options;
    }

    abstract public function execute(): array;
}
