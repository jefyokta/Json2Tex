<?php

namespace Jefyokta\Json2Tex\List;

use Renderable;
use Jefyokta\Json2Tex\Type\Node;

class Image implements Renderable
{

    /**
     * @param Node<object{id:string}>[] $nodes
     * 
     */

    public function render(&$nodes): string
    {
        $result = '<ul>';
        foreach ($nodes as $table) {
            $caption = $table->content[0]?->content[0]?->text ?? '';
            $result .= "<li><a href=\"{$table->attrs->id}\">{$caption}</a></li>";
        }
        $result .= "</ul>";
        return '';
    }
};
