<?php

namespace Jefyokta\Json2Tex\Collector;

class ImageCollector extends BaseCollector
{

    private int $figureNo = 1;
    private int $ch = 0;
    public function onNode($node)
    {

        if ($node->type == 'heading' && ($node->attrs->level ?? false) === 1) {
            $this->ch++;
            $this->figureNo = 1;
        }

        if ($this->shouldCollect($node)) {

            $this->collection[$node->attrs->id] = $this->generateLabel($node, $this->ch, $this->figureNo);
            $this->figureNo++;
        }
    }
    protected function shouldCollect(object $node): bool
    {
        return $node->type === 'imageFigure';
    }

    protected function generateLabel(object $node, int $chapter, int $index): string
    {
        return "Gambar {$chapter}.{$index}";
    }
}
