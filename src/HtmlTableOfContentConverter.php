<?php

namespace Jefyokta\Json2Tex;

use Jefyokta\Json2Tex\Type\Node;

class HtmlTableOfContentConverter
{
    /**
     * @param Node[] $nodes
     * @return string
     */
    public function render(array $nodes): string
    {
        $items = [];
        foreach ($nodes as $node) {
            // var_dump($node);
            if ($node->type === 'heading') {
                $level = (int) ($node->attrs->level ?? 1);
                $text = $node->content[0]->text ?? '';
                $id = $this->slugify($text);
                $items[] = [
                    'level' => $level,
                    'text'  => $text,
                    'id'    => $id,
                ];
            }
        }

        if (empty($items)) {
            return '';
        }

        $html = '<ul>';
        $prevLevel = $items[0]['level'];

        foreach ($items as $item) {
            $lvl = $item['level'];
            if ($lvl > $prevLevel) {
                $html .= str_repeat('<ul>', $lvl - $prevLevel);
            }
            elseif ($lvl < $prevLevel) {
                $html .= str_repeat('</ul>', $prevLevel - $lvl);
            }
            // tambahkan item
            $html .= sprintf(
                '<li><a href="#%s">%s</a></li>',
                htmlspecialchars($item['id'], ENT_QUOTES),
                htmlspecialchars($item['text'])
            );

            $prevLevel = $lvl;
        }

        // tutup sisa tag <ul>
        if ($prevLevel > 1) {
            $html .= str_repeat('</ul>', $prevLevel - 1);
        }

        $html .= '</ul>';

        return $html;
    }

    /**
     * Ubah teks menjadi slug (anchor-friendly)
     */
    protected function slugify(string $text): string
    {
        // lowercase, hapus non-alphanumeric, ganti spasi dengan dash
        $slug = preg_replace('/[^\p{L}\p{N}\s]/u', '', mb_strtolower($text));
        $slug = preg_replace('/\s+/', '-', trim($slug));
        return $slug;
    }
}
