<?php

namespace Jefyokta\Json2Tex;

use Jefyokta\Json2Tex\Interface\Converter as InterfaceConverter;
use Jefyokta\Json2Tex\Type\Node,
    Jefyokta\Json2Tex\Type\Heading,
    Jefyokta\Json2Tex\Type\Table;

require_once __DIR__ . "/Headers.php";



class Converter implements InterfaceConverter
{

    /**
     * @param Node $node
     * 
     * @return string
     */

    function paragraph($node)
    {
        return "\\par " . JsonToTex::setContent($node->content) . "\n";
    }
    /**
     * 
     * Parsing object to latex headers
     * @param Heading $content
     * 
     * @return string
     */


    function heading($content)
    {

        $synt = Headers::$level[$content->attrs->level] ?? false;
        if (!$synt) {
            return '';
        }
        return "\\$synt{" . JsonToTex::setContent($content->content) . "}\n";
    }

    /**
     * @param Node $content
     * 
     * @return string
     */
    function inlineMath($content): string
    {
        return "$" . ($content->attrs->content ?? '') . "$";
    }

    /**
     * @param Node $content
     * 
     * @return string
     */

    function blockMath($content): string
    {
        return "\n\\[\n" . ($content->attrs->content ?? '') . "\n\\]\n";
    }

    /**
     * @param Node $content
     * 
     * @return string
     */

    function orderedList($content)
    {
        $items = JsonToTex::setContent($content->content)->getLatex();
        return "\\begin{enumerate}\n" . $items . "\\end{enumerate}\n";
    }

    /**
     * @param Node $content
     * 
     * @return string
     */

    function bulletList($content)
    {
        $items = JsonToTex::setContent($content->content)->getLatex();
        return "\\begin{itemize}\n" . $items . "\\end{itemize}\n";
    }

    /**
     * @param Node $content
     * 
     * @return string
     */
    function listItem($content)
    {
        return "\\item " . JsonToTex::setContent($content->content)->getLatex() . "\n";
    }

    /**
     * @param object{attrs:object{src:string,caption:string}} $element
     * 
     * @return string
     */

    function figure($element)
    {
        return "
        \\begin{figure}[h]
        \\centering
        \\includegraphics{" . $element->attrs->src . "}
        \\caption{" . ($element->attrs->caption ?? '') . "}
        \\end{figure}
        ";
    }

    /**
     * @param Node $element
     * 
     * @return string
     */

    function text($element)
    {
        if (!empty($element->marks)) {
            foreach ($element->marks as $mark) {
                if (method_exists($this, $mark->type)) {
                    $element->text =   $this->{$mark->type}($element->text);
                }
            }
        }
        return $element->text;
    }

    function image($element)
    {

        return "\\includegraphics{" . $element->src . "}";
    }

    function cite($element)
    {
        if ($element->attrs->citeA) {
            return "\\citeA{" . ($element->attrs->cite ?? '') . "}";
        }
        return "\\cite{" . ($element->attrs->cite ?? '') . "}";
    }

    function var($element)
    {
        $var = "\\" . $element->attrs->varname;;
        if (!empty($element->marks)) {
            foreach ($element->marks as $mark) {
                if (method_exists($this, $mark->type)) {
                    $var = $this->{$mark->type}($var);
                }
            }
        }

        return $var;
    }

   public function italic($text)
    {
        return "\\textit{" . $text . "}";
    }

    public function bold($text)
    {
        return "\\textbf{" . $text . "}";
    }

    public function comment($text)
    {
        return $text;
    }
    /**
     *  @param Table $element
     * 
     *  
     * */
    function table($element): string
    {
        $contents = $element->content ?? [];

        if (empty($contents)) {
            return '';
        }

        $maxColumns = 0;
        $columnWidths = [];
        foreach ($contents as $row) {
            if ($row->type === "tableRow") {
                $totalCells = 0;
                foreach ($row->content as $cell) {
                    $colspan = $cell->attrs->colspan ?? 1;
                    $totalCells += $colspan;

                    if (!empty($cell->attrs->colwidth)) {
                        foreach ($cell->attrs->colwidth as $index => $width) {
                            $columnWidths[$totalCells - $colspan + $index] = $width;
                        }
                    }
                }
                $maxColumns = max($maxColumns, $totalCells);
            }
        }
        $latexColumns = [];
        for ($i = 0; $i < $maxColumns; $i++) {
            $width = isset($columnWidths[$i]) ? round($columnWidths[$i] / 10, 2) . "cm" : "l"; 
            $latexColumns[] = "p{" . $width . "}";
        }
        $columnFormat = implode("|", $latexColumns);

        $latexTable = "\\begin{tabular}{|$columnFormat|}\n\\hline\n";

        $isFirstRowHeader = isset($contents[0]->content[0]) && $contents[0]->content[0]->type === "tableHeader";
        $rowspanTracker = array_fill(0, $maxColumns, 0);

        foreach ($contents as $rowIndex => $row) {
            if ($row->type !== "tableRow") {
                continue;
            }

            $cells = [];
            $columnIndex = 0;

            foreach ($row->content as $cell) {
                while ($columnIndex < $maxColumns && $rowspanTracker[$columnIndex] > 0) {
                    $rowspanTracker[$columnIndex]--;
                    $columnIndex++;
                }
                $text = JsonToTex::setContent($contents);
                $colspan = $cell->attrs->colspan ?? 1;
                $rowspan = $cell->attrs->rowspan ?? 1;
                $align = $cell->attrs->align ?? 'l'; 
                if ($rowspan > 1) {
                    $rowspanTracker[$columnIndex] = $rowspan - 1;
                }

                if ($colspan > 1) {
                    $cells[] = "\\multicolumn{" . $colspan . "}{|$align|}{" . $text . "}";
                } elseif ($rowspan > 1) {
                    $cells[] = "\\multirow{" . $rowspan . "}{*}{" . $text . "}";
                } else {
                    $cells[] = ($rowIndex === 0 && $isFirstRowHeader) ? "\\textbf{" . $text . "}" : $text;
                }

                $columnIndex += $colspan;
            }

            $latexTable .= implode(" & ", $cells) . " \\\\\n\\hline\n";
        }

        $latexTable .= "\\end{tabular}\n";
        return $latexTable;
    }


    public function tableCell($element)
    {
        
    }

    public function tableHeader()
    {
        
    }

    public function tableRow($element)
    {
        
    }
}
