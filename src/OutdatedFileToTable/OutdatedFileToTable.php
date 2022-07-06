<?php

namespace App\OutdatedFileToTable;

class OutdatedFileToTable
{
    private string $name;
    private string $version;
    private string $latesVersion;

    public function __construct(string $name, string $version, string $latesVersion)
    {
        $this->name         = $name;
        $this->version      = $version;
        $this->latesVersion = $latesVersion;
    }

    public static function composerOutdatedTable(string $path): array
    {
        $content = json_decode(file_get_contents($path), true);
        $tab[]   = '';

        foreach ($content['installed'] as $value) {
            $name          = $value['name'];
            $version       = self::filterVersion($value['version']);
            $latestVersion = self::filterVersion($value['latest']);
            $latestStatus  = $value['latest-status'];
            $isAbandoned   = $value['abandoned'];

            if ($isAbandoned || \is_string($isAbandoned)) {
                $tab[] = new self($name, $version, 'abandonné');
            } else {
                $pieces = explode('/', $name);

                if ('symfony' === $pieces[0]) {
                    if ('http-kernel' === $pieces[1]) {
                        $tab[0] = new self($pieces[0], $version, $latestVersion);
                    }
                } elseif ('semver-safe-update' !== $latestStatus) {
                    $tab[] = new self($name, $version, $latestVersion);
                }
            }
        }

        return $tab;
    }

    private function filterVersion(string $version): string
    {
        return array_values(array_filter(explode('v', $version)))[0];
    }

    public static function npmOutdatedTable(string $path): array
    {
        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);

        for ($i = 1; $i < $num; ++$i) {
            $tab[] = self::patternLigneNpm(explode(' ', $array[$i]));
        }

        return array_filter($tab);
    }

    private function patternLigneNpm(array $ligne)
    {
        $ligne         = array_values(array_filter($ligne));
        $version       = $ligne[1];
        $latestVersion = $ligne[3];

        if (!self::isMajor($version, $latestVersion)) {
            return [];
        }

        return new self($ligne[0], $version, $latestVersion);
    }

    private function isMajor(string $version, string $latestVersion): bool
    {
        return (explode('.', $latestVersion)[0] - explode('.', $version)[0]) > 0;
    }

    public static function cocoaPodsOutdatedTable($path): array
    {
        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);

        for ($i = 3; $i < $num; ++$i) {
            $tab[] = self::patternLigneCocoaPods(explode(' ', $array[$i]));
        }

        return array_filter($tab);
    }

    private function patternLigneCocoaPods(array $ligne)
    {
        $ligne         = array_values(array_filter($ligne));
        $version       = $ligne[2];
        $latestVersion = $ligne[4];

        if ('(unused)' === $ligne[4]) {
            return new self($ligne[1], $version, 'abandonné');
        }

        if (!self::isMajor($version, $latestVersion)) {
            return [];
        }

        return new self($ligne[1], $version, $latestVersion);
    }

    public static function androidOutdatedTable(string $path): array
    {
        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        $k     = 0;

        for ($i = 0; $i < $num; ++$i) {
            if ('Gradle release-candidate updates:' === $array[$i]) {
                break;
            }

            if (1 === $k) {
                $tab[] = self::patternLigneAndroid($array[$i]);
            }

            if ('The following dependencies have later milestone versions:' === $array[$i]) {
                $k = 1;
            }
        }

        return array_filter($tab);
    }

    private function patternLigneAndroid(string $ligne)
    {
        $tab = explode(' ', $ligne);

        if ('-' !== $tab[1]) {
            return [];
        }
        $version       = explode('[', $tab[3])[1];
        $latestVersion = explode(']', explode('-', $tab[5])[0])[0];

        if (!self::isMajor($version, $latestVersion)) {
            return [];
        }

        return new self($tab[2], $version, $latestVersion);
    }
}
