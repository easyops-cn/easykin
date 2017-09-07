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
 * Class FileLogger
 * @package easyops\easykin\logger
 */
class FileLogger implements Logger
{
    /** @var string $filePath */
    protected $filePath;

    /** @var string $fileName */
    protected $fileName;

    /**
     * FileLogger constructor.
     * @param string $filePath
     * @param string $fileName
     * @param bool $muteError
     * @throws LoggerException
     */
    public function __construct($filePath = 'tmp', $fileName = 'zipkin.log', $muteError = true)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        if (!$muteError && !is_dir($this->filePath . DIRECTORY_SEPARATOR)) {
            throw new LoggerException('Invalid logs directory');
        }
    }

    /**
     * @param array $spans
     */
    public function log($spans)
    {
        file_put_contents(
            $this->filePath . DIRECTORY_SEPARATOR . $this->fileName,
            json_encode($spans) . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}