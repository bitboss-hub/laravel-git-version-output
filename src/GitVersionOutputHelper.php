<?php

namespace BitbossHub\GitVersionOutput;

use BitbossHub\GitVersionOutput\Exception\CouldNotGetVersionException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\RuntimeException;

class GitVersionOutputHelper
{


    private static function appName()
    {
        return config('app.name', 'app');
    }

    private static function createVersionFile()
    {
        try {
            $version = explode("-", GitVersion::getCurrentVersion());
        } catch (CouldNotGetVersionException $e) {
            return [];
        }
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
            GitVersion::APP_NAME => $appName,
            GitVersion::TAG => $tag,
            GitVersion::COMMIT => $commit,
            GitVersion::SINCE_TAG => $commits,
            GitVersion::BUILD_DATE => $date,
        ];
    }

    public static function getOnly($const)
    {
        return self::getInformations()[$const];
    }
}
