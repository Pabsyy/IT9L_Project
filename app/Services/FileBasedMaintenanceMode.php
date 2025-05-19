<?php

namespace App\Services;

use Illuminate\Contracts\Foundation\MaintenanceMode;

class FileBasedMaintenanceMode implements MaintenanceMode
{
    protected $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function active(): bool
    {
        return file_exists($this->filePath);
    }

    public function data(): array
    {
        if (!$this->active()) {
            return [];
        }

        $data = json_decode(file_get_contents($this->filePath), true);

        return is_array($data) ? $data : [];
    }

    public function activate(array $payload): void
    {
        file_put_contents($this->filePath, json_encode($payload));
    }

    public function deactivate(): void
    {
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }
} 