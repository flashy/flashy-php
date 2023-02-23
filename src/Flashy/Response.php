<?php

namespace Flashy;

use Flashy\Exceptions\FlashyResponseException;

class Response implements \ArrayAccess
{

    /**
     * @var object|null
     */
    private $body;

    /**
     * @var array
     */
    private $headers;

    /**
     * Response constructor.
     * @param $response
     * @param bool $decode
     * @param $headers
     * @throws FlashyResponseException
     */
    public function __construct($response, $decode = true, $headers = [])
    {
        if ($decode === true) {
            $this->body = $this->decode($response);
        } else {
            $this->body = $response;
        }

        $this->headers = $headers;
    }

    /**
     * @param $response
     * @return array|bool|float|int|mixed
     * @throws FlashyResponseException
     */
    public function decode($response): mixed
    {
        $result = json_decode($response, true);

        if ($result === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new FlashyResponseException('Unable to decode json string.');
        }

        return $result;
    }

    /**
     * @return array|mixed
     */
    public function getErrors(): mixed
    {
        if (isset($this->body['errors'])) {
            return $this->body['errors'];
        }

        return [];
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return count($this->getErrors()) > 0;
    }

    /**
     * @return bool
     */
    public function success(): bool
    {
        return ( isset($this->body['success']) ) ? $this->body['success'] : false;
    }

    /**
     * @return array|mixed
     */
    public function getData(): mixed
    {
        if (isset($this->body['data'])) {
            return $this->body['data'];
        }

        return [];
    }

    /**
     * @return array|bool|float|int|mixed|object|null
     */
    public function getBody(): mixed
    {
        return $this->body;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function __get($key): mixed
    {
        if (! isset($this->body['data'][$key])) {
            return null;
        }

        return $this->body['data'][$key];
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->body['data'][$offset]);
    }

    /**
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->$offset;
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset,mixed $value): void
    {
        $this->body['data'][$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->body['data'][$offset]);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return float|object|array|bool|int|null
     */
    public function toArray(): float|object|array|bool|int|null
    {
        return $this->body;
    }
}
