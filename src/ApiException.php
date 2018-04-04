<?php

namespace MNC\ProblemDetails;

/**
 * This class in an implementation of RFC7807 that is meant to be extended into
 * error types, that then can be catched by your application at controller level
 * to provide a meaningful response to your Api clients.
 * @package MNC\ApiProblem
 * @author Matías Navarro Carter <mnavarro@option.cl>
 * @link https://tools.ietf.org/html/rfc7807
 *
 * An ApiExeption object can have members or properties that help describe the
 * error. Recommended members are defined in the RFC. You should check them out.
 * @link https://tools.ietf.org/html/rfc7807#section-3.1
 */
abstract class ApiException extends \Exception implements ApiExceptionInterface
{
    /**
     * @var string
     */
    private $type = '';
    /**
     * @var string
     */
    private $title = '';
    /**
     * @var array Extra data to include in the error thay may be of utility.
     */
    private $extra = [];
    /**
     * @var int
     */
    private $code;
    /**
     * @var string
     */
    private $message;

    /**
     * The url of the status codes definitions when no error type is setted.
     */
    const STATUS_CODES_URL = 'https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html';
    /**
     * The message to set when no error details are present.
     */
    const NO_DETAIL = 'No details of the error are available.';

    /**
     * Status codes translation table.
     *
     * The list of codes is complete according to the
     * {@link http://www.iana.org/assignments/http-status-codes/ Hypertext Transfer Protocol (HTTP) Status Code Registry}
     * (last updated 2016-03-01).
     *
     * Unless otherwise noted, the status code is defined in RFC2616.
     *
     * @var array
     * @author Symfony Community
     */
    public static $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        103 => 'Early Hints',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',                                     // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    );

    /**
     * ApiException constructor.
     * @param string     $type
     * @param string     $title
     * @param int        $statusCode
     * @param string     $detail
     * @param array      $extra
     * @param \Throwable $previous
     */
    public function __construct(string $type = '', string $title = '', int $statusCode = 500, string $detail = '', array $extra = [], \Throwable $previous = null)
    {
        $this->type = $type;
        $this->title = $title;
        $this->extra = $extra;
        parent::__construct($detail, $statusCode, $previous);
    }

    /**
     * @return string
     */
    final public function getType(): string
    {
        if (empty($this->type)) {
            return self::STATUS_CODES_URL;
        }
        return $this->type;
    }

    /**
     * @return string
     */
    final public function getTitle(): string
    {
        if (empty($this->title)) {
            return self::$statusTexts[$this->code];
        }
        return $this->title;
    }

    /**
     * @return int
     */
    final public function getStatusCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    final public function getDetail(): string
    {
        if (empty($this->message)) {
            return self::NO_DETAIL;
        }
        return $this->message;
    }

    /**
     * @return array
     */
    final public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @param string $type
     * @return ApiExceptionInterface
     */
    final public function setType(string $type): ApiExceptionInterface
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $title
     * @return ApiExceptionInterface
     */
    final public function setTitle(string $title): ApiExceptionInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param int $statusCode
     * @return ApiExceptionInterface
     */
    final public function setStatusCode(int $statusCode): ApiExceptionInterface
    {
        $this->code = $statusCode;
        return $this;
    }

    /**
     * @param string $title
     * @return ApiExceptionInterface
     */
    final public function setDetail(string $title): ApiExceptionInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return ApiExceptionInterface
     */
    final public function set(string $key, mixed $value): ApiExceptionInterface
    {
        $this->extra[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    final public function toArray(): array
    {
        return [
            'type' => $this->getType(),
            'title' => $this->getTitle(),
            'status' => $this->getStatusCode(),
            'detail' => $this->getDetail(),
        ] + $this->extra;
    }

    /**
     * @return array
     */
    final public function jsonSerialize()
    {
        return $this->toArray();
    }
}