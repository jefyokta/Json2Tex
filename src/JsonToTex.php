<?php

namespace Jefyokta\Json2Tex;

use Jefyokta\Json2Tex\Type\Node;

class JsonToTex
{



    /**
     * @return string;
     * 
     */

    public static function compile(string $json)
    {
        $json = json_decode($json);

        return   self::getContent($json->content);
    }

    /**
     * @param Node[] $contents
     * 
     * @return string;
     */

    public static function getContent($contents)
    {
        $result = '';
        $converter = new Converter();
        foreach ($contents as $content) {
            if (method_exists($converter, $content->type)) {
                $result .=  $converter->{$content->type}($content);
            }
        }
        return $result;
    }

    public function __call($name, $arguments)
    {
        $this->$name(...$arguments);
    }
};

