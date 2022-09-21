<?php
namespace core\cli;

use core\library\Single;

class Tty
{
    use Single;

    // 命令提示头
    private string $header = "command> ";
    // 游标最小偏移量 字节
    private int $minCursorOffset = 0;
    // 游标最大偏移量 字节
    private int $maxCursorOffset = 0;

    private int $operateCursorOffset = 0;

    private string $command = "";

    public function getUserCommand(): string
    {
        fwrite(STDOUT, $this->getHeader());
        $command = "";
        $this->setMinCursorOffset(strlen($this->getHeader()));
        while (1) {
            $chr = get_inputchr();
            list($continue) = $this->upKeys($chr, $command, $cursorOffset);
            if (!$continue) {
                break;
            }
            fwrite(STDOUT, sprintf("\r\33[0K%s%s\r\33[%dC", $this->getHeader(), $this->getCommand(), $this->getCursorOffset()));
        }
        $this->initialize();
        echo PHP_EOL;
//        if ($command == 'cls') {
//            system("reset");
//            return;
//        }else if ($command == 'exit'){
//            exit();
//        }else if ($command == 'start'){
//
//        }
        return $this->command;
    }

    public function upKeys($chr, &$command, &$cursorOffset): array
    {
        $continue = true;
        switch ($chr){
            case "\n":
                $continue = false;
                break;
            // 删除键
            case "\177":
                $this->backCommand();
                break;
            // ctrl + l 清屏
            case "\f":
                $this->writeOut("\f" . $this->header . ' ');
                break;
            // 使用了组合键
            case "\33":
                while (($conChr = get_inputchr())) {
                    $chr .= $conChr;
                    if (in_array($conChr, ['A', 'B', 'C', 'D'])) {
                        switch ($conChr){
                            // 上
                            case 'A':
                                // 下
                            case 'B':
                                break;
                            // ->
                            case 'C':
                                $this->setOperateCursorOffset(1);
                                break;
                            // <-
                            case 'D':
                                $this->setOperateCursorOffset(-1);
                                break;
                        }
                        break;
                    }
                }
                break;
            default:
                $this->outCharCommand($chr);
        }
        return [$continue];
    }

    public function writeOut($msg)
    {
        fwrite(STDOUT, "$msg");
    }

    public function initialize()
    {
        $this->operateCursorOffset = 0;
        $this->outCharCommand("", false);
    }
    /**
     * 游标位置
     * @return int
     */
    public function getCursorOffset(): int
    {
        return $this->outCommandLength() + $this->getOperateCursorOffset();
    }

    /**
     * 命令总长度
     * @return int
     */
    public function outCommandLength(): int
    {
        return strlen($this->header) + strlen($this->command);
    }

    /**
     * 用户输入命令长度
     * @return int
     */
    public function getCommandCursorOffset(): int
    {
        return strlen($this->command) + $this->getOperateCursorOffset();
    }
    /**
     * @return int
     */
    public function getMinCursorOffset(): int
    {
        return $this->minCursorOffset;
    }

    /**
     * @param int $minCursorOffset
     */
    public function setMinCursorOffset(int $minCursorOffset): void
    {
        $this->minCursorOffset = $minCursorOffset;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function outCharCommand(string $command, $is_append = true): void
    {
        if ($is_append) {
            $head = substr($this->command, 0, $this->getCommandCursorOffset());
            $tail = substr($this->command, $this->getOperateCursorOffset(), strlen($this->command) - strlen($head));
            $this->command = $head . $command . $tail;
        }else{
            $this->command = $command;
        }
    }

    public function backCommand()
    {
        $this->command = substr($this->command, 0, -1);
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @param string $header
     */
    public function setHeader(string $header): void
    {
        $this->header = $header;
    }

    /**
     * @return int
     */
    public function getOperateCursorOffset(): int
    {
        return $this->operateCursorOffset;
    }

    /**
     * @param int $operateCursorOffset
     */
    public function setOperateCursorOffset(int $operateCursorOffset): void
    {
        if ($operateCursorOffset < 0 && $this->getCursorOffset() + $operateCursorOffset <= $this->minCursorOffset - 1) {
            return;
        }
        if ($operateCursorOffset > 0 && $this->getCursorOffset() + $operateCursorOffset >= $this->outCommandLength() + 1) {
            return;
        }
        $this->operateCursorOffset += $operateCursorOffset;
    }
}