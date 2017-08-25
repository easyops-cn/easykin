<?php
/**
 * @author index
 *   ┏┓   ┏┓+ +
 *  ┏┛┻━━━┛┻┓ + +
 *  ┃       ┃
 *  ┃  ━    ┃ ++ + + +
 * ████━████┃+
 *  ┃       ┃ +
 *  ┃  ┻    ┃
 *  ┃       ┃ + +
 *  ┗━┓   ┏━┛
 *    ┃   ┃
 *    ┃   ┃ + + + +
 *    ┃   ┃     Codes are far away from bugs with the animal protecting
 *    ┃   ┃ +         神兽保佑,代码无bug
 *    ┃   ┃
 *    ┃   ┃   +
 *    ┃   ┗━━━┓ + +
 *    ┃       ┣┓
 *    ┃       ┏┛
 *    ┗┓┓┏━┳┓┏┛ + + + +
 *     ┃┫┫ ┃┫┫
 *     ┗┻┛ ┗┻┛+ + + +
 */

namespace easyops\easykin\logger;


/**
 * Class HttpLogger
 * @package easyops\easykin\logger
 */
class HttpLogger implements Logger
{
    /** @var string $address */
    protected $address;

    /** @var bool $muteError */
    protected $muteError;

    /**
     * HttpLogger constructor.
     * @param string $address
     * @param bool $muteError
     */
    public function __construct($address, $muteError = true)
    {
        $this->address = $address;
        $this->muteError = $muteError;
    }

    /**
     * @param array $spans
     * @throws LoggerException
     */
    public function log($spans)
    {
        $contextOptions = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($spans),
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create($contextOptions);
        @file_get_contents($this->address, false, $context);

        if (!$this->muteError && (empty($http_response_header) || !$this->validResponse($http_response_header))) {
            throw new LoggerException('Trace upload failed');
        }
    }

    /**
     * @param array $headers
     * @return bool
     */
    protected function validResponse($headers)
    {
        foreach ($headers as $header) {
            if (preg_match('/202/', $header)) {
                return true;
            }
        }

        return false;
    }
}