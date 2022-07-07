<?php

namespace App\OutdatedLibraries\OutdatedLibrariesToElastic\ElasticInput;

use App\OutdatedLibraries\OutdatedFileToTable\LibraryOutdated;

trait PatternTrait
{
    private function patternArray(string $project, LibraryOutdated $value): array
    {
        $name = $value->getName();
        $installedVersion = $value->getInstalledVersion();
        $latestVersion = $value->getLatestVersion();
        return ['project' => $project, 'library' => $name, 'version' => $installedVersion, 'latestVersion' => $latestVersion];
    }
}
