<?php

namespace BitbossHub\GitVersionOutput;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\RuntimeException;

class GitVersionOutputHelper
{
    private static function versionFile()
    {
        return base_path() . '/version';
    }

    private static function appName()
    {
        return config('app.name', 'app');
    }

    private static function createVersionFile()
    {
        $version = explode("-", self::getCurrentVersion());
        $tag = !empty($version[0]) ? $version[0] : null;
        $commits = !empty($version[1]) ? $version[1] : null;
        $commit = !empty($version[2]) ? $version[2] : null;

        $versionFile = self::versionFile();

        $versionFileContent = "$tag|$commits|$commit|" . date("Y-m-d H:i:s");;
        file_put_contents($versionFile, $versionFileContent);
        return explode("|", trim($versionFileContent));
    }

    public static function getInformations()
    {
        if (file_exists(self::versionFile())) {
            $infos = explode("|", trim(file_get_contents(self::versionFile())));
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
            'app_name' => $appName,
            'tag' => $tag,
            'commit' => $commit,
            'since_tag' => $commits,
            'build_date' => $date,
        ];
    }
    /**
     * Get the app's version string
     *
     * If a file <base>/version exists, its contents are trimmed and used.
     * Otherwise we get a suitable string from `git describe`.
     *
     * @throws Exception\CouldNotGetVersionException if there is no version file and `git
     * describe` fails
     * @return string Version string
     */
    public static function getCurrentVersion()
    {
        // If we have a version file, just return its contents
        if (file_exists(self::versionFile())) {
            return trim(file_get_contents(self::versionFile()));
        }

        $path = base_path();


        // Get version string from git
        $command = 'git describe --always --tags --dirty';
        $fail = false;
        if (class_exists('\Symfony\Component\Process\Process')) {
            try {
                if (method_exists(Process::class, 'fromShellCommandline')) {
                    $process = Process::fromShellCommandline($command, $path);
                } else {
                    $process = new Process($command, $path);
                }

                $process->mustRun();
                $output = $process->getOutput();
            } catch (RuntimeException $e) {
                $fail = true;
            }
        } else {
            // Remember current directory
            $dir = getcwd();

            // Change to base directory
            chdir($path);

            $output = shell_exec($command);

            // Change back
            chdir($dir);

            $fail = $output === null;
        }

        if ($fail) {
            throw new Exception\CouldNotGetVersionException;
        }

        return trim($output);
    }
}
