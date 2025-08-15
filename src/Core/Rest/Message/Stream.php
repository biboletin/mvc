<?php

namespace Bibo\Mvc\Core\Rest\Message;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

/**
 * Stream class that implements the StreamInterface.
 */
class Stream implements StreamInterface
{
    /**
     * Stream resource
     *
     * @var resource|null
     */
    private $stream;

    /**
     * Stream size
     *
     * @var int|null
     */
    private ?int $size;

    /**
     * Stream position
     *
     * @var int
     */
    private int $position = 0;

    /**
     * Whether the stream is seekable
     *
     * @var bool
     */
    private bool $isSeekable = true;

    /**
     * Whether the stream is writable
     *
     * @var bool
     */
    private bool $isWritable = true;

    /**
     * Whether the stream is readable
     *
     * @var bool
     */
    private bool $isReadable = true;

    /**
     * Stream metadata
     *
     * @var array
     */
    private array $metadata = [];

    /**
     * Stream URI
     *
     * @var string|null
     */
    private ?string $uri = null;

    /**
     * Stream constructor.
     *
     * @param string $stream
     */
    public function __construct(string $stream = '')
    {
        if (is_string($stream)) {
            $resource = fopen('php://temp', 'r+');
            if ($stream !== '') {
                fwrite($resource, $stream);
                rewind($resource);
            }
            $stream = $resource;
        }

        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Invalid stream provided');
        }

        $this->stream = $stream;
        $this->uri = stream_get_meta_data($stream)['uri'] ?? null;
        $this->metadata = stream_get_meta_data($stream);
        $this->isSeekable = $this->metadata['seekable'];
        $this->isReadable = $this->getMetadata('mode') && preg_match('/[r+]/', $this->metadata['mode']);
        $this->isWritable = $this->getMetadata('mode') && preg_match('/[waxc+]/', $this->metadata['mode']);
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @return string
     * @see    http://php.net/manual/en/language.oop5.magic.php#object.tostring
     */
    public function __toString(): string
    {
        $this->seek(0); // Rewind the stream

        return stream_get_contents($this->stream);
    }

    /**
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public function close(): void
    {
        if ($this->stream && is_resource($this->stream)) {
            fclose($this->stream);
            $this->stream = null;
        }
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach()
    {
        $stream = $this->stream;
        $this->stream = null;

        return $stream;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if is unknown.
     */
    public function getSize(): ?int
    {
        // TODO: Implement getSize() method.
        if ($this->size === null) {
            return null;
        }
        return $this->size;
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws RuntimeException on error.
     */
    public function tell(): int
    {
        if (!$this->stream) {
            throw new RuntimeException('Stream is closed');
        }

        return ftell($this->stream);
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public function eof(): bool
    {
        return feof($this->stream);
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public function isSeekable(): bool
    {
        return $this->isSeekable;
    }

    /**
     * Seek to a position in the stream.
     *
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *                    based on the seek offset. Valid values are identical to the built-in
     *                    PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *                    offset bytes SEEK_CUR: Set position to current location plus offset
     *                    SEEK_END: Set position to end-of-stream plus offset.
     *
     * @throws RuntimeException on failure.
     * @link   http://www.php.net/manual/en/function.fseek.php
     */
    public function seek(int $offset, int $whence = SEEK_SET): void
    {
        if (!$this->isSeekable) {
            throw new RuntimeException('Stream is not seekable');
        }

        if (fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException('Unable to seek to position ' . $offset);
        }

        $this->position = ftell($this->stream);
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @throws RuntimeException on failure.
     * @link   http://www.php.net/manual/en/function.fseek.php
     * @see    seek()
     */
    public function rewind(): void
    {
        $this->seek(0);
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public function isWritable(): bool
    {
        return $this->isWritable;
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     *
     * @return int Returns the number of bytes written to the stream.
     * @throws RuntimeException on failure.
     */
    public function write(string $string): int
    {
        if (!$this->isWritable) {
            throw new RuntimeException('Stream is not writable');
        }

        return fwrite($this->stream, $string);
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public function isReadable(): bool
    {
        return $this->isReadable;
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *                    them. Fewer than $length bytes may be returned if underlying stream
     *                    call returns fewer bytes.
     *
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws RuntimeException if an error occurs.
     */
    public function read(int $length): string
    {
        if (!$this->isReadable) {
            throw new RuntimeException('Stream is not readable');
        }

        return fread($this->stream, $length);
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents(): string
    {
        if ($this->eof()) {
            return '';
        }

        return stream_get_contents($this->stream);
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @param string|null $key Specific metadata to retrieve.
     *
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     * @link   http://php.net/manual/en/function.stream-get-meta-data.php
     */
    public function getMetadata(?string $key = null): mixed
    {
        if ($key === null) {
            return $this->metadata;
        }

        return $this->metadata[$key] ?? null;
    }

    /**
     * Get the URI of the stream.
     *
     * @return string|null
     */
    public function getStreamUri(): ?string
    {
        return $this->uri;
    }
}
