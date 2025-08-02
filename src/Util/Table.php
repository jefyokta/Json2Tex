<?php

namespace Jefyokta\Json2Tex\Util;

use Jefyokta\Json2Tex\Converter;
use Jefyokta\Json2Tex\Type\Table as TableType;
use Jefyokta\Json2Tex\Type\TableRow;

class Table
{
    /**
     * Render table as HTML.
     *
     * @param TableType $table
     * @return string
     */
    public function render( $table): string
    {
        $rows = $table->content;
        $colWidths = $this->calculateColWidths($rows);
        $colGroupHtml = $this->renderColGroup($colWidths);
        $tableBody = Converter::getHtml($rows);

        return "<table>$colGroupHtml<tbody>$tableBody</tbody></table>";
    }

    /**
     * Static wrapper to render table.
     *
     * @param TableType $table
     * @return string
     */
    public static function html($table): string
    {
        return (new static())->render($table);
    }

    /**
     * Calculate column widths from table rows.
     *
     * @param TableRow[] $rows
     * @return array<int, int|string>
     */
    private function calculateColWidths(array $rows): array
    {
        $maxCols = 0;
        $colWidths = [];

        foreach ($rows as $row) {
            foreach ($row->content as $i => $cell) {
                if (!isset($colWidths[$i]) && isset($cell->attrs->colwidth[0])) {
                    $colWidths[$i] = $cell->attrs->colwidth[0];
                }
            }
            $maxCols = max($maxCols, count($row->content));
        }

        for ($i = 0; $i < $maxCols; $i++) {
            $colWidths[$i] = $colWidths[$i] ?? 'auto';
        }

        return $colWidths;
    }

    /**
     * Render the <colgroup> tag with widths.
     *
     * @param array<int, int|string> $colWidths
     * @return string
     */
    private function renderColGroup(array $colWidths): string
    {
        $html = '<colgroup>';
        foreach ($colWidths as $width) {
            $html .= '<col style="width:' . $this->convertWidth($width) . '">';
        }
        return $html . '</colgroup>';
    }

    /**
     * Convert column width to CSS string.
     *
     * @param int|string $width
     * @return string
     */
    private function convertWidth(int|string $width): string
    {
        return is_string($width) ? 'auto' : "{$width}px";
    }
}
