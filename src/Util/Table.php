<?php

namespace Jefyokta\Json2Tex\Util;

use Jefyokta\Json2Tex\Converter;
use Jefyokta\Json2Tex\JsonToTex;

class Table
{

    /**
     * @param \Jefyokta\Json2Tex\Type\Table $table
     */
    public function render($table)
    {
        $rows = $table->content;
        $cells = [];
        $min = 1;
        foreach ($rows as $row) {
            $cellsCount = count($row->content) ?? 0;
            if ($cellsCount < $min) {
                $min = $cellsCount;
                $cells = $row->content;
            }
        }

        $colWidth =  $this->extractColWidth($cells);
        $renderedWidth = $this->renderColWidth($colWidth);

        $tableContent = Converter::getHtml($rows);
        return "<table>$renderedWidth<tbody>$tableContent</tbody></table>";
    }
    /**
     * @param \Jefyokta\Json2Tex\Type\TableCell[] $cells
     * 
     * @return array<int|string>
     */

    private function extractColWidth($cells)
    {
        $width = [];
        foreach ($cells as $cell) {
            $width[] = $cell->attrs->colwidth[0] ?? 'auto';
        }

        return $width;
    }

    static function html($table)
    {

        return (new static)->render($table);
    }
    /**
     * 
     * @param int[] $colWidth
     */
    private function renderColWidth($colWidth)
    {
        $res = '<colgroup>';
        foreach ($colWidth as $width) {
            $res .= "<col style=\"width:{$this->convertWidth($width)}\">";
        }
        return $res .= "</colgroup>";
    }

    private function convertWidth($width){

        return is_string($width ?? false) ? 'auto': $width.'px';
    }
}
