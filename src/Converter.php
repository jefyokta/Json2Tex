<?php 
namespace Jefyokta\Json2Tex;
require_once __DIR__."/Headers.php";

class Converter {

    /**
     * @param object{content:array} $content
     * 
     * @return string
     */

    function paragraph($content) {
        return "\\par " . JsonToTex::getContent($content->content) . "\n";
    }
    /**
     * 
     * Parsing object to latex headers
     * @param object{content:array,attrs:object{level:int}} $content
     * 
     * @return string
     */


    function heading($content) {

        $synt = Headers::$level[$content->attrs->level] ?? false;
        if (!$synt) {
            return '';
        }
        return "\\$synt{" . JsonToTex::getContent($content->content) . "}\n";
    }

    /**
     * @param object{content:array} $content
     * 
     * @return string
     */
    function inlineMath($content): string {
        return "$" . ($content->attrs->content ?? '') . "$";
    }

    /**
     * @param object{content:array} $content
     * 
     * @return string
     */

    function blockMath($content): string {
        return "\n\\[\n" . ($content->attrs->content ?? '') . "\n\\]\n";
    }

    /**
     * @param object{content:array} $content
     * 
     * @return string
     */

    function orderedList($content) {
        $items = JsonToTex::getContent($content->content);
        return "\\begin{enumerate}\n" . $items . "\\end{enumerate}\n";
    }

    /**
     * @param object{content:array} $content
     * 
     * @return string
     */

    function bulletList($content) {
        $items = JsonToTex::getContent($content->content);
        return "\\begin{itemize}\n" . $items . "\\end{itemize}\n";
    }

    /**
     * @param object{content:array} $content
     * 
     * @return string
     */
    function listItem($content) {
        return "\\item " . JsonToTex::getContent($content->content) . "\n";
    }

    /**
     * @param object{attrs:object{src:string,caption:string}} $element
     * 
     * @return string
     */

    function figure($element) {
        return "
        \\begin{figure}[h]
        \\centering
        \\includegraphics{" . $element->attrs->src . "}
        \\caption{" . ($element->attrs->caption ?? '') . "}
        \\end{figure}
        ";
    }

    /**
     * @param object{text:string,marks?:null|object[]} $element
     * 
     * @return string
     */

    function text($element) {
        if (!empty($element->marks)) {
            foreach ($element->marks as $mark) {
                if (method_exists($this, $mark->type)) {
                 $element->text =   $this->{$mark->type}($element->text);
                }
            }
        }
        return $element->text;
    }

    function image($element){

        return "\\includegraphics{".$element->src."}";

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
                   $var = $this->{$mark->type}($var);
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

    private function comment($text){
        return $text;

    }

    
}
