<?php

namespace App\OutdatedLibraries\OutdatedFileToTable;

class Library
{
    private string $name;
    private string $installedVersion;
    private string $latestVersion;

    public function __construct(string $name, string $version, string $latestVersion)
    {
        $this->name             = $name;
        $this->installedVersion = $version;
        $this->latestVersion    = $latestVersion;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInstalledVersion(): string
    {
        return $this->installedVersion;
    }

    public function getLatestVersion(): string
    {
        return $this->latestVersion;
    }
}
