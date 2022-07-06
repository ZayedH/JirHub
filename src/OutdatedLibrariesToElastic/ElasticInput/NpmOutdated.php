<?php

namespace App\OutdatedLibrariesToElastic\ElasticInput;

use App\OutdatedFileToTable\OutdatedFileToTable;

class NpmOutdated
{
    use PatternTrait;

    public function getNpmJson(string $path, string $name): string
    {
        $tab = OutdatedFileToTable::npmOutdatedTable($path);

        foreach ($tab as $key => $value) {
            $tab[$key] = $this->patternArray($name, array_values((array) $value));
        }
        $now   = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $tab[] = ['@timestamp' => $now];

        return json_encode(array_values($tab));
    }
}
