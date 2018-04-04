<?php

namespace MNC\ProblemDetails;

/**
 * Interface ApiExceptionInterface
 * @package MNC\ApiProblem
 */
interface ApiExceptionInterface extends \JsonSerializable
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return int
     */
    public function getStatusCode(): int;

    /**
     * @return string
     */
    public function getDetail(): string;

    /**
     * @return array
     */
    public function getExtra(): array;

    /**
     * @param string $type
     * @return ApiExceptionInterface
     */
    public function setType(string $type): ApiExceptionInterface;

    /**
     * @param string $title
     * @return ApiExceptionInterface
     */
    public function setTitle(string $title): ApiExceptionInterface;

    /**
     * @param int $statusCode
     * @return ApiExceptionInterface
     */
    public function setStatusCode(int $statusCode): ApiExceptionInterface;

    /**
     * @param string $title
     * @return ApiExceptionInterface
     */
    public function setDetail(string $title): ApiExceptionInterface;

    /**
     * @param string $key
     * @param mixed  $value
     * @return ApiExceptionInterface
     */
    public function set(string $key, mixed $value): ApiExceptionInterface;

    /**
     * @return array
     */
    public function toArray(): array;
}