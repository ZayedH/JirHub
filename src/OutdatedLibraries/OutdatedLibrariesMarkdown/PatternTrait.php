<?php

namespace App\OutdatedLibraries\OutdatedLibrariesMarkdown;

use App\OutdatedLibraries\OutdatedFileToTable\LibraryOutdated;

trait PatternTrait
{
    private function generateHeader(string $name): array
    {
        return ["| $name | version  | version disponible |", '| --- | --- | --- |'];
    }

    private function patternLigne(LibraryOutdated $value): string
    {
        $name = $value->getName();
        $installedVersion = $value->getInstalledVersion();
        $latestVersion = $value->getLatestVersion();
        return '| ⚠️' . ' ' . " $name  | $installedVersion | $latestVersion |";
    }
}
