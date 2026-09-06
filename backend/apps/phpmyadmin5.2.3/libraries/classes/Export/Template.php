<?php

declare(strict_types=1);

namespace PhpMyAdmin\Export;


final class Template
{
    
    private $id;

    
    private $username;

    
    private $exportType;

    
    private $name;

    
    private $data;

    private function __construct(int $id, string $username, string $exportType, string $name, string $data)
    {
        $this->id = $id;
        $this->username = $username;
        $this->exportType = $exportType;
        $this->name = $name;
        $this->data = $data;
    }

    
    public static function fromArray(array $state): self
    {
        return new self(
            $state['id'] ?? 0,
            $state['username'],
            $state['exportType'] ?? '',
            $state['name'] ?? '',
            $state['data']
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getExportType(): string
    {
        return $this->exportType;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getData(): string
    {
        return $this->data;
    }
}
