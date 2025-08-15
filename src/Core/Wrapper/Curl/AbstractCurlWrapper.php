<?php

namespace Bibo\Mvc\Core\Wrapper\Curl;

abstract class AbstractCurlWrapper
{
    protected mixed $handle;
    protected array $options = [];

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
}
