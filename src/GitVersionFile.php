<?php

namespace BitbossHub\GitVersionOutput;

class GitVersionFile
{

    public static function getPath()
    {
        return base_path() . '/version';
    }

    public static function getContent()
    {
        return trim(file_get_contents(self::getPath()));
    }

    public static function write($content)
    {
        return file_put_contents(self::getPath(), $content);
    }
}
