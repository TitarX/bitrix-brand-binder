<?php

namespace Pro\CoreCode\BrandBinder;

class Settings
{
    private static $timeout = 300;
    private static $timezone = 'Europe/Moscow';
    private static $pieceSize = 300;

    public static function getTimeout(): int
    {
        return self::$timeout;
    }

    public static function getTimezone(): string
    {
        return self::$timezone;
    }

    public static function getPieceSize(): int
    {
        return self::$pieceSize;
    }
}
