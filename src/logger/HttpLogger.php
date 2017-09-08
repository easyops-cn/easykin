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
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, $this->address);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($spans));
        $result = curl_exec($ch);
        if (!$this->muteError && $result === false) throw new LoggerException('Trace upload failed');
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (!$this->muteError && $code !== 202) throw new LoggerException('Trace upload failed');
        curl_close($ch);
    }
}