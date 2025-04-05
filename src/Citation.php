<?php

namespace Jefyokta\Json2Tex;

class Citation
{

    private static $maxAuthors = 5;
    private static $conjunction = 'and';
    private static $etal = 'et al.';
    /**
     * @var array{cite:string,author:string,year:string,title:string}[]
     * */
    private static $cites;

    /**
     * @param array{cite:string,author:string,year:string,title:string}[] $cites
     */
    public static function setCollection($cites)
    {
        self::$cites = $cites;
    }


    public static function getCollection()
    {

        return self::$cites;
    }

    public static function set($conjunction = 'and', $etal='et al.', $maxAuthors = 5)
    {

        self::$conjunction = $conjunction;
        self::$etal = $etal;
        self::$maxAuthors = $maxAuthors;
    }


    public static function formatAuthorName(string $names): string
    {
        if (empty($names)) {
            return '';
        }

        if (!str_contains($names, ' and ')) {
            $parts = explode(',', $names);
            return trim($parts[0]);
        }

        $authors = explode(' and ', $names);

        $nameArray = array_map(function ($author) {
            if (str_contains($author, ',')) {
                $parts = explode(',', $author);
                return trim($parts[0]);
            }
            return trim($author);
        }, $authors);

        if (count($nameArray) > self::$maxAuthors) {
            return implode(', ', array_slice($nameArray, 0, self::$maxAuthors)) . ' ' . self::$etal;
        }

        if (count($nameArray) > 1) {
            $last = array_pop($nameArray);
            return implode(', ', $nameArray) . ' ' . self::$conjunction . ' ' . $last;
        }
        return $nameArray[0];
    }
}
