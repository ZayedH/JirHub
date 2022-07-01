<?php

namespace App\OutdatedLibrariesToElastic\ElasticInput;

class NpmOutdated
{
    use PatternTrait;
    public function getNpmJson(string $path): string
    {

        $array = explode("\n", file_get_contents($path));
        $array = array_filter($array);
        $num   = \count($array);
        $tab   = [];

        for ($i = 1; $i < $num; ++$i) {
            $tab[] = $this->patternLigne(explode(' ', $array[$i]));
        }


        $now     = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $tab[] = ['@timestamp' => $now];
      
        return json_encode(array_values(array_filter($tab)));
    }

    private function patternLigne(array $ligne): array
    {
        $ligne = array_values(array_filter($ligne));
        $version = $ligne[1];
        $latestVersion = $ligne[3];
        if (!$this->isMajor($version, $latestVersion)) {
            return [];
        }
       
        return $this->pattern('Chronos (web)',$ligne[0],$version,$latestVersion);
    }
}
