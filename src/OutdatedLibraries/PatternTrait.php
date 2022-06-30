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
        return ["| $name | version  | version disponible |", '| --- | --- | --- |'];
    }
    private function isMajor(string $version, string $latestVersion): bool
    {
        return (explode('.', $latestVersion)[0] - explode('.', $version)[0]) > 0;
    }
}
