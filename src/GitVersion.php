<?php

namespace BitbossHub\GitVersionOutput;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\RuntimeException;

class GitVersion
{

    const APP_NAME = 'app_name';
    const TAG = 'tag';
    const COMMIT = 'commit';
    const SINCE_TAG = 'since_tag';
    const BUILD_DATE = 'build_date';

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
            throw new CouldNotGetVersionException;
        }

        return trim($output);
    }
}
