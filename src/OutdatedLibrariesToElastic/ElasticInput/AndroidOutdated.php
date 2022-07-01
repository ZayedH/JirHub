<?php

namespace App\OutdatedLibrariesToElastic\ElasticInput;

class AndroidOutdated
{
    use PatternTrait;

    public function getAndroidJson(string $path): string
    {
        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        $tab   = [];
        $k     = 0;

        for ($i = 0; $i < $num; ++$i) {
            if ('Gradle release-candidate updates:' === $array[$i]) {
                break;
            }

            if (1 === $k) {
                $tab[] = $this->patternLigne($array[$i]);
            }

            if ('The following dependencies have later milestone versions:' === $array[$i]) {
                $k = 1;
            }
        }

        $now   = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $tab[] = ['@timestamp' => $now];
      
        return json_encode(array_values(array_filter($tab)));
    }

    private function patternLigne(string $ligne): array
    {
        $tab = explode(' ', $ligne);

        if ('-' !== $tab[1]) {
            return [];
        }
        $version       = explode('[', $tab[3])[1];
        $latestVersion = explode(']', explode('-', $tab[5])[0])[0];

        if (!$this->isMajor($version, $latestVersion)) {
            return [];
        }


        return  $this->pattern('Chronos (android)',$tab[2],$version,$latestVersion);
    }
}
