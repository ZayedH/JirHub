<?php
namespace App\NpmOutdated;

trait PatternTrait {

    private function pattern(string $name, string $version, string $latestVersion = 'abandonné'): string
    {
        return '| ⚠️' . ' ' . " $name  | $version  | $latestVersion |";
    }
}