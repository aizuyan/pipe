<?php
/**
 *
 *
 */
namespace Ritoyan\Pipe;

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

    public function setDelimiter($delimiter)
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    public function run()
    {
        $fp = popen($this->command, "r");

        if (false === $fp) {
            throw new Exception("打开命令失败");
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
