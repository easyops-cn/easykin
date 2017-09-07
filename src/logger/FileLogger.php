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
    /** @var string $fileDir */
    protected $fileDir;

    /** @var string $filePath */
    protected $filePath;

    /** @var bool $muteError */
    protected $muteError;

    /**
     * FileLogger constructor.
     * @param string $fileDir
     * @param string $fileName
     * @param bool $muteError
     * @throws LoggerException
     */
    public function __construct($fileDir = 'tmp', $fileName = 'zipkin.log', $muteError = true)
    {
        $this->fileDir = $fileDir;
        $this->filePath = $fileDir . DIRECTORY_SEPARATOR . $fileName;
        $this->muteError = $muteError;
    }

    protected function writableCheck()
    {
        if (!is_dir($this->fileDir) or !is_writable($this->fileDir)) {
            if (!$this->muteError) {
                throw new LoggerException('Invalid Directory doesn\'t exist or isn\'t writable');
            }
            else {
                return false;
            }
        }
        elseif (is_file($this->filePath) and !is_writable($this->filePath)) {
            if (!$this->muteError) {
                throw new LoggerException('File exists and isn\'t writable');
            }
            else {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $spans
     */
    public function log($spans)
    {
        if ($this->writableCheck()) {
            file_put_contents(
                $this->filePath,
                json_encode($spans) . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }
    }
}