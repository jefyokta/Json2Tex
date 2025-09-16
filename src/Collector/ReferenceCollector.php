<?php

namespace Jefyokta\Json2Tex\Collector;

use Jefyokta\Json2Tex\Interface\Observer;

class ReferenceCollector implements Observer
{

    private static $usedReference = [];

    public function onNode($node)
    {
        if ($node->type == 'cite') {
            self::$usedReference[] = $node->attrs->cite;
        }
    }


    static function getUsedReferences()
    {

        return self::$usedReference;
    }
}
