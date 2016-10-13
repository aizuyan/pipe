<?php
/**
 * php对对popen的封装，通过回调的方式模拟管道命令
 *
 */
namespace Aizuyan\Pipe;

class Pipe
{
    /**
     * 要通过管道执行的命令
     */
    protected $command = "";

    /**
     * 回调函数，将管道数据传递给该函数
     */
    protected $callback = null;

    /**
     * 数据之间的分隔符
     */
    protected $delimiter = "\n";

    /**
     * 设置命令
     *
     * @param cmd string 要运行的命令
     */
    public function setCmd($cmd)
    {
        $this->command = $cmd;
        return $this;
    }

    /**
     * 设置回调函数，处理管道输出的命令
     */
    public function setCallback(callable $cb)
    {
        $this->callback = $cb;
        return $this;
    }

    /**
     * 设置数据片段之间的分隔符
     */
    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * 开始运行
     */
    public function run()
    {
        $fp = popen($this->command, "r");

        if (false === $fp) {
            throw new \RuntimeException("popen execute command failed!");
        }

        $item = "";
        while (!feof($fp)) {
            $char = fgetc($fp);
            if ($this->delimiter == $char) {
                call_user_func($this->callback, $item);
                $item = "";
            } else {
                $item .= $char;
            }
        }
        pclose($fp);
    }
}
