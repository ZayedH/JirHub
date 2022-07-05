<?php
namespace App\OutdatedFileToTable;

class OutdatedFileToTable{



    public function composerOutdatedTable(string $path):array
    {
        
        $content = json_decode(file_get_contents($path), true);
        $tab[]   = '';

        foreach ($content['installed'] as $value) {
            $name          = $value['name'];
            $version       = $this->filterVersion($value['version']);
            $latestVersion = $this->filterVersion($value['latest']);
            $latestStatus  = $value['latest-status'];
            $isAbandoned   = $value['abandoned'];

            if ($isAbandoned || \is_string($isAbandoned)) {
                $tab[] = [$name, $version,'abandonné'];
            } else {
                $pieces = explode('/', $name);

                if ('symfony' === $pieces[0]) {
                    if ('http-kernel' === $pieces[1]) {
                        $tab[0] = [$pieces[0], $version, $latestVersion];
                    }
                } elseif ('semver-safe-update' !== $latestStatus) {
                    $tab[] = [$name, $version, $latestVersion];
                }
            }
        }
        return $tab;

    }
    private function filterVersion(string $version): string
    {
        return array_values(array_filter(explode('v', $version)))[0];
    }

    public function npmOutdatedTable(string $path):array
    {
        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        for ($i = 1; $i < $num; ++$i) {
            $tab[] = $this->patternLigneNpm(explode(' ', $array[$i]));
        }
        return array_filter($tab);

    }
    private function patternLigneNpm(array $ligne): array
    {
        $ligne = array_values(array_filter($ligne));
        $version = $ligne[1];
        $latestVersion = $ligne[3];
        if (!$this->isMajor($version, $latestVersion)) {
            return [];
        }
       
        return [$ligne[0],$version,$latestVersion];
    }
    private function isMajor(string $version, string $latestVersion): bool
    {
        return (explode('.', $latestVersion)[0] - explode('.', $version)[0]) > 0;
    }

    public function cocoaPodsOutdatedTable($path):array
    {
        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        for ($i = 3; $i < $num; ++$i) {
            $tab[] = $this->patternLigneCocoaPods(explode(' ', $array[$i]));
        }
        return array_filter($tab);

    }
    private function patternLigneCocoaPods(array $ligne): array
    {
        $ligne = array_values(array_filter($ligne));
        $version = $ligne[2];
        $latestVersion = $ligne[4];
        if ($ligne[4] == '(unused)') {
            return  [$ligne[1], $version,'abandonné'];
        }
        if (!$this->isMajor($version, $latestVersion)) {
            return [];
        }
        return [$ligne[1], $version, $latestVersion];
    }

    public function androidOutdatedTable(string $path):array
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
                $tab[] = $this->patternLigneAndroid($array[$i]);
            }

            if ('The following dependencies have later milestone versions:' === $array[$i]) {
                $k = 1;
            }
        }
        return array_filter($tab);
    }
    private function patternLigneAndroid(string $ligne): array
    {
        $tab = explode(' ', $ligne);

        if ('-' !== $tab[1]) {
            return [];
        }
        $version = explode('[', $tab[3])[1];
        $latestVersion = explode(']', explode('-', $tab[5])[0])[0];
        if (!$this->isMajor($version, $latestVersion)) {
            return [];
        }
        return [$tab[2], $version, $latestVersion];
    }

}