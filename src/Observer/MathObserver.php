<?php

namespace Jefyokta\Json2Tex\Observer;

use Jefyokta\Json2Tex\Interface\Observer;

class MathObserver implements Observer
{

    private $used = 0;

    public function onNode($node)
    {
        if ($node->type == 'mathBlock' || $node->type  == "inlineMath") {
            ++$this->used;
        }
    }

    function getUsed()
    {
        return $this->used;
    }
};
