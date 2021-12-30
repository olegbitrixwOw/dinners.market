<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\Getfood\Image;

/**
 * Ресайзинг изображений
 */

Image::resizeCatalogItem($arResult, 325, 245);