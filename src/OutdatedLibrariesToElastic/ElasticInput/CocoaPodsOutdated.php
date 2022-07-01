<?php

namespace App\OutdatedLibrariesToElastic\ElasticInput;

class CocoaPodsOutdated
{
    use PatternTrait;

    public function getCocoaPodsJson(string $path): string
    {
        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        $tab   = [];

        for ($i = 3; $i < $num; ++$i) {
            $tab[] = $this->patternLigne(explode(' ', $array[$i]));
        }
        $now   = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $tab[] = ['@timestamp' => $now];
       
        return json_encode(array_values(array_filter($tab)));
    }

    private function patternLigne(array $ligne): array
    {
        $ligne         = array_values(array_filter($ligne));
        $version       = $ligne[2];
        $latestVersion = $ligne[4];

        if ('(unused)' === $ligne[4]) {
          
            return $this->pattern('Chronos (ios)',$ligne[1],$version);
        }

        if (!$this->isMajor($version, $latestVersion)) {
            return [];
        }

       
        return $this->pattern('Chronos (ios)',$ligne[1],$version,$latestVersion);
    }
}
