<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main\Localization\Loc;

/**
 * Bitrix vars
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (empty($arResult))
    return;

echo '<b>'.Loc::getMessage("CATALOG").'</b></br>';
echo '<ul>';

foreach ($arResult['LINKS'] as $news_id=>$sections_id){
    echo '<li>';

    //news name, date
    echo '<b>'.$arResult['NEWS'][$news_id]['NAME'].'</b>'.' - '
         .$arResult['NEWS'][$news_id]['DATE_ACTIVE_FROM'].' (';

    //sect name
    $sections_name=[];
    foreach ($sections_id as $id)
    	$sections_name[]=$arResult['SECTIONS'][$id];
    echo implode(', ',$sections_name);

    echo ')';

	echo '<ul>';
	foreach ($sections_id as $id){
		foreach ($arResult['PRODUCTS'][$id] as $item){
			echo '<li>';
			echo implode(' - ',$item);
			echo '</li>';
		}
    }
	echo '</ul>';

    echo '</li>';
}
echo '</ul>';