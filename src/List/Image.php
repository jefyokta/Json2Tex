<?php

namespace Jefyokta\Json2Tex\List;

use Jefyokta\Json2Tex\Interface\Renderable;
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
        foreach ($nodes as $image) {
            $caption = $image->content[1]?->content[0]?->text ?? '';
            $result .= "<li><a href=\"{$image->attrs->id}\">{$caption}</a></li>";
        }
        $result .= "</ul>";
        return '';
    }
};
