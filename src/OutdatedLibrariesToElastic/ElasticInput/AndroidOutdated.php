<?php

namespace App\OutdatedLibrariesToElastic\ElasticInput;

use App\OutdatedFileToTable\OutdatedFileToTable;

class AndroidOutdated
{
    use PatternTrait;

    private OutdatedFileToTable $OutdatedFileToTable;

    public function __construct(OutdatedFileToTable $OutdatedFileToTable)
    {
        $this->OutdatedFileToTable = $OutdatedFileToTable;
    }

    public function getAndroidJson(string $path): string
    {
        $tab = $this->OutdatedFileToTable->androidOutdatedTable($path);

        foreach ($tab as $key => $value) {
            $tab[$key] = $this->patternArray('Chronos (android)', $value);
        }
        $now   = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $tab[] = ['@timestamp' => $now];

        return json_encode(array_values($tab));
    }
}
