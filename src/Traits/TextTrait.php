<?php

declare(strict_types=1);
/**
 * MIT License
 * Copyright (c) 2021 Electronic Student Services @ Appalachian State University
 *
 * See LICENSE file in root directory for copyright and distribution permissions.
 *
 * @author Matthew McNaney <mcnaneym@appstate.edu>
 * @license https://opensource.org/licenses/MIT
 */

namespace Canopy3\Traits;

trait TextTrait
{

    public static function filterVar(string $value)
    {
        return trim(filter_var($value, FILTER_UNSAFE_RAW,
                FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH));
    }

    /**
     * Removes all tag attributes.
     * @param string $html
     * @return type
     */
    public static function removeTagAttributes(string $html)
    {
        $dom = new \DOMDocument;
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $xpath = new \DOMXPath($dom);
        $elements = $xpath->query('//*');
        foreach ($elements as $element) {
            $attributes = $element->attributes;
            while ($attributes->length) {
                $element->removeAttribute($attributes->item(0)->name);
            }
        }
        return $dom->saveHTML();
    }

    public static function stripTags(string $content, array $allowedTags = [])
    {
        $noAttributes = self::removeTagAttributes($content);
        return strip_tags($content, $allowedTags);
    }

}
