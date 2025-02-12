<?php 

namespace Jefyokta\Json2Tex;

class Converter {

    function paragraph($content) {
        return "\\par " . JsonToTex::getContent($content->content) . "\n";
    }

    function section($content) {
        return "\\section{" . JsonToTex::getContent($content->content) . "}\n";
    }

    function inlineMath($content): string {
        return "$" . ($content->attrs->content ?? '') . "$";
    }

    function blockMath($content) :string{
        return "\n\\[\n" . ($content->attrs->content ?? '') . "\n\\]\n";
    }

    function orderedList($content) {
        $items = JsonToTex::getContent($content->content);
        return "\\begin{enumerate}\n" . $items . "\\end{enumerate}\n";
    }

    function bulletList($content) {
        $items = JsonToTex::getContent($content->content);
        return "\\begin{itemize}\n" . $items . "\\end{itemize}\n";
    }

    function listItem($content) {
        return "\\item " . JsonToTex::getContent($content->content) . "\n";
    }


    function figure($element){


        return "
        \\begin{figure}\n
        \\centering\n
        \\includegraphics{{ $element->attrs->src }}
        \\caption{}
        \\end{figure}\n
        ";

    }

    function text($element){

    }
}