<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$arResult['FIRST_DATE'] = $arResult['ITEMS'][0]['ACTIVE_FROM'];
$this->__component->setResultCacheKeys(['FIRST_DATE']);
