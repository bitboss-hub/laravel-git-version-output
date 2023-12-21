<?php

namespace BitbossHub\GitVersionOutput;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\RuntimeException;

class GitVersionOutputHelper
{

    const APP_NAME = 'app_name';
    const TAG = 'tag';
    const COMMIT = 'commit';
    const SINCE_TAG = 'since_tag';
    const BUILD_DATE = 'build_date';

    private static function appName()
    {
        return config('app.name', 'app');
    }

    private static function createVersionFile()
    {
        $version = explode("-", GitVersion::getCurrentVersion());
        $tag = !empty($version[0]) ? $version[0] : null;
        $commits = !empty($version[1]) ? $version[1] : null;
        $commit = !empty($version[2]) ? $version[2] : null;

        $versionFileContent = "$tag|$commits|$commit|" . date("Y-m-d H:i:s");;
        GitVersionFile::write($versionFileContent);
        return explode("|", trim($versionFileContent));
    }

    public static function getInformations()
    {
        if (file_exists(GitVersionFile::getPath())) {
            $infos = explode("|", GitVersionFile::getContent());
        } else {
            $infos = self::createVersionFile();
        }

        return self::informationParser($infos);
    }

    private static function informationParser($information = array())
    {
        $appName = self::appName();

        $tag = !empty($information[0]) ? $information[0] : null;
        $commits = !empty($information[1]) ? $information[1] : null;
        $commit = !empty($information[2]) ? $information[2] : null;
        $date = !empty($information[3]) ? $information[3] : null;

        return [
            self::APP_NAME => $appName,
            self::TAG => $tag,
            self::COMMIT => $commit,
            self::SINCE_TAG => $commits,
            self::BUILD_DATE => $date,
        ];
    }

    public function getOnly($const)
    {
        return self::getInformations()[$const];
    }
}
