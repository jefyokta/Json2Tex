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

    function blockMath($content): string {
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

    function figure($element) {
        return "
        \\begin{figure}[h]
        \\centering
        \\includegraphics{" . $element->attrs->src . "}
        \\caption{" . ($element->attrs->caption ?? '') . "}
        \\end{figure}
        ";
    }

    function text($element) {
        if (!empty($element->marks)) {
            foreach ($element->marks as $mark) {
                if (method_exists($this, $mark->type)) {
                    $element->text = call_user_func([$this, $mark->type], $element->text);
                }
            }
        }
        return $element->text;
    }

    function image($element){

        return "\\inludegraphics{".$element->src."}";

    }

    function cite($element) {
        if ($element->attrs->citeA) {
            return "\\citeA{" . ($element->attrs->cite ?? '') . "}";
        }
        return "\\cite{" . ($element->attrs->cite ?? '') . "}";
    }

    function var($element){
        $var ="\\".$element->attrs->varname;;
        if (!empty($element->marks)) {

            foreach($element->marks as $mark){
                if (method_exists($this, $mark->type)) {
                    $var= call_user_func([$this, $mark->type], $var);
                }
            }
        }

        return $var;

    }

    private function italic($text) {
        return "\\textit{" . $text . "}";
    }

    private function bold($text) {
        return "\\textbf{" . $text . "}";
    }

    
}
