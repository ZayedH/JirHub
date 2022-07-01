<?php

namespace App\OutdatedLibrariesToElastic\ElasticInput;

trait PatternTrait
{
    private function isMajor(string $version, string $latestVersion): bool
    {
        return (explode('.', $latestVersion)[0] - explode('.', $version)[0]) > 0;
    }
    private function pattern(string $project,string $name, string $version, string $latestVersion = 'abandonné'): array
    {
        return ['project' => "$project", "library" => "$name", 'version' => "$version", 'latestVersion' => "$latestVersion"];
    }

}
