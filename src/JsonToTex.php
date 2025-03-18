<?php

namespace Jefyokta\Json2Tex;

use Jefyokta\Json2Tex\Converter as Json2TexConverter;
use Jefyokta\Json2Tex\Interface\Converter;
use Jefyokta\Json2Tex\Type\Node;

class JsonToTex
{
    /**
     * @var Converter
     */
    private Converter $converter;

    /**
     * @var Node[]
     */
    private array $contents = [];

    /**
     * Mengompilasi JSON ke dalam konten yang sesuai.
     *
     * @param string $json
     * @return string
     */
    public static function compile(string $json): string
    {
        $decoded = json_decode($json);
        return (new static())->setContent($decoded->content ?? [])->getLatex();
    }

    /**
     * Mengatur konten dari JSON.
     *
     * @param Node[] $contents
     * @return static
     */
    public function setContent(array $contents): static
    {
        $this->contents = $contents;
        return $this;
    }

    /**
     * Menghasilkan HTML dari konten.
     *
     * @param Node[]|null $contents
     * @return string
     */
    public function getHtml(?array $contents = null): string
    {
        $this->converter = new HtmlConverter();
        return $this->getContent($contents);
    }

    /**
     * Get Latex Result.
     *
     * Once the contents is null, it will use this->contents
     * @param Node[]|null $contents
     * @return string
     */
    public function getLatex(?array $contents = null): string
    {
        $this->converter = new Json2TexConverter();
        return $this->getContent($contents ?? $this->contents);
    }

    /**
     * Mengubah konten menjadi string sesuai dengan konverter yang digunakan.
     *
     * @param Node[]|null $contents
     * @return string
     */
    private function getContent(?array $contents = null): string
    {
        $contents = $contents ?? $this->contents;
        $result = '';

        foreach ($contents as $content) {
            if (method_exists($this->converter, $content->type)) {
                $result .= $this->converter->{$content->type}($content);
            }
        }

        return $result;
    }

    /**
     * Memungkinkan pemanggilan metode statis secara dinamis.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return (new static())->{$name}(...$arguments);
    }
}
