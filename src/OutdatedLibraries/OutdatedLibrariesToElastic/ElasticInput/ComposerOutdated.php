<?php

namespace App\OutdatedLibraries\OutdatedLibrariesToElastic\ElasticInput;

use App\OutdatedLibraries\OutdatedFileToTable\OutdatedFileToTable;

class ComposerOutdated
{
    use PatternTrait;
    private OutdatedFileToTable $OutdatedFileToTable;

    public function __construct(OutdatedFileToTable $OutdatedFileToTable)
    {
        $this->OutdatedFileToTable = $OutdatedFileToTable;
    }

    public function getComposerJson(string $path, string $name): string
    {
        $tab = $this->OutdatedFileToTable->composerOutdatedTable($path);

        foreach ($tab as $key => $value) {
            $tab[$key] = $this->patternArray($name, $value);
        }

        $now   = (new \DateTimeImmutable())->format(\DateTimeInterface::RFC3339);
        $tab[] = ['@timestamp' => $now];

        return json_encode($tab);
    }
}
