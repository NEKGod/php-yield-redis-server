<?php

namespace core\library;
/**
 * 单例模式
 */
trait Single
{
    private static $singleObject = null;

    final private function __construct(){}
    private function __clone(){}

    public static function getSingle(...$args) :self
    {
        if (!self::$singleObject instanceof self) {
            self::$singleObject = new self(...$args);
        }
        return self::$singleObject;
    }
}