<?php

namespace App\OutdatedLibraries;

trait PatternTrait
{

    private function pattern(string $name, string $version, string $latestVersion = 'abandonné'): string
    {
        return '| ⚠️' . ' ' . " $name  | $version  | $latestVersion |";
    }
    private function generateHeader(string $name): array
    {

        return ["| Chronos ($name) | version  | version disponible |", '| --- | --- | --- |'];
    }
}
