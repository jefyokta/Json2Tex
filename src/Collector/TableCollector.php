<?php

namespace Jefyokta\Json2Tex\Collector;

class TableCollector extends BaseCollector
{

    private int $tableNo = 1;
    public function onNode($node)
    {

        if (($node->attrs->level ?? null) === 1) {
            $this->chapter++;
            $this->tableNo = 1;
        }

        if ($this->shouldCollect($node)) {
            $this->collection[$node->attrs->id] = $this->generateLabel($node,$this->chapter,$this->tableNo);
            $this->tableNo++;
        }
    }
    protected function shouldCollect(object $node): bool
    {
        return $node->type === 'figureTable';
    }

    protected function generateLabel(object $node, int $chapter, int $index): string
    {
        return "Tabel {$chapter}.{$index}";
    }
}
