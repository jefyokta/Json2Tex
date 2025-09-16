<?php

namespace Jefyokta\Json2Tex\List;

use Jefyokta\Json2Tex\Interface\Renderable;
use Jefyokta\Json2Tex\Type\Node;

class Image implements Renderable
{

    /**
     * @var callable(string):string
     */
    private $textGenerator = null;

    /**
     * @param Node<object{id:string}>[] $nodes
     * 
     */

    public function render(&$nodes): string
    {
        $result = '<ul>';
        foreach ($nodes as $image) {
            $caption = $image->content[1]?->content[0]?->text ?? '';
            $result .= "<li><a href=\"{$image->attrs->id}\">" . !$this->textGenerator ? $caption : ($this->textGenerator)($caption) . "</a></li>";
        }
        $result .= "</ul>";
        return '';
    }


    private function makeInstance(callable $generator){
        $this->textGenerator = $generator;
        return $this;
    }

    static function withGenerator(callable $generator){
       return (new static)->makeInstance($generator);
    }
};
