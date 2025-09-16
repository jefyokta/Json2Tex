<?php

namespace Jefyokta\Json2Tex\Collector;

use Jefyokta\Json2Tex\Interface\Collector;
use Jefyokta\Json2Tex\Interface\Observer;
use Stringable;

abstract class BaseCollector implements Collector, Observer, Stringable
{
    protected array $collection = [];
    protected int $chapter = 0;

    abstract protected function shouldCollect(object $node): bool;

    abstract protected function generateLabel(object $node, int $chapter, int $index): string;
    public function onNode($node) {}
    public function collect(array $nodes): void
    {
        $this->collection = [];
        $this->chapter = 0;
        $counter = 1;

        foreach ($nodes as $node) {
            if (($node->attrs->level ?? null) === 1) {
                $this->chapter++;
                $counter = 1;
            }

            if ($this->shouldCollect($node)) {
                $label = $this->generateLabel($node, $this->chapter, $counter);
                $this->collection[$node->attrs->id] = $label;
                $counter++;
            }
        }
    }

    public function get(string $id): ?string
    {

        // var_dump($this->collection);
        return $this->collection[$id] ?? null;
    }

    public function all(): array
    {
        return $this->collection;
    }

   final public function __toString(): string
    {
        return static::class;
    }
}
