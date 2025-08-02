<?php
namespace Jefyokta\Json2Tex\Interface;

interface Collector
{
public function collect(array $nodes): void;
public function get(string $id): ?string;
public function all(): array;
}