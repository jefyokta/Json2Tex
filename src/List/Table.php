<?php

namespace Jefyokta\Json2Tex\List;

use Jefyokta\Json2Tex\Type\Node;
use Renderable;

class Table implements Renderable
{
    /**
     * @param Node<object{id:string}>[] $nodes
     */

    public function render(&$nodes): string
    {
        $result = '<ul>';
        foreach ($nodes as $node) {
            if ($node->type == 'figureTable') {
                $caption = $node->content[0]->content[0]->text ?? '';
                $result .= "<li><a href='{$node->attrs->id}'>$caption</a></li>";
            }
        }
        $result .= "</ul>";
        return $result;
    }
}
