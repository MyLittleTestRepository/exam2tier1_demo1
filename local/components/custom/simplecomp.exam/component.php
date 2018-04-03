<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

/**
 * Bitrix vars
 * @global CUser $USER
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 */

use Bitrix\Main\Localization\Loc;

///////////////input
if (empty($arParams['CACHE_TIME']) or intval($arParams['CACHE_TIME']))
	$arParams['CACHE_TIME'] = 3600000;
$arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);

if (empty($arParams['CACHE_TYPE']))
	$arParams['CACHE_TYPE'] = 'A';

$arParams['CAT_IB_ID'] = intval($arParams['CAT_IB_ID']);
$arParams['NEWS_IB_ID'] = intval($arParams['NEWS_IB_ID']);

if (empty($arParams['CAT_UF_PROP']))
{
	ShowError(Loc::getMessage("NO_PROP"));
	return;
}

$arParams['CAT_UF_PROP'] = trim($arParams['CAT_UF_PROP']);

////////////cache
if ($this->startResultCache())
{
	if (!CModule::IncludeModule('iblock'))
	{
		$this->abortResultCache();
		ShowError(Loc::getMessage("NO_MOD_IB"));
		return;
	}

	//get sections
	$arFilter = ['IBLOCK_ID'     => $arParams['CAT_IB_ID'],
	             'GLOBAL_ACTIVE' => 'Y',
	             'ACTIVE'        => 'Y',
	             '!UF_NEWS_LINK' => false,];

	$arSelectFields = ['ID', 'NAME', 'UF_NEWS_LINK'];

	$Res = CIBlockSection::GetList(false, $arFilter, false, $arSelectFields, false);

	while ($item = $Res->Fetch())
	{
		foreach ($item['UF_NEWS_LINK'] as $news_id)
			$arResult['LINKS'][intval($news_id)][] = intval($item['ID']);
		$arResult['SECTIONS'][$item['ID']] = $item['NAME'];
	}

	//get elements
	$arFilter = ['ACTIVE' => 'Y',
	             ['LOGIC' => 'OR',
	              ['IBLOCK_ID' => $arParams['NEWS_IB_ID'], 'ID' => array_keys($arResult['LINKS']),],
	              ['IBLOCK_ID'  => $arParams['CAT_IB_ID'],
	               'SECTION_ID' => array_keys($arResult['SECTIONS']),],],];

	$arSelectFields = ['ID',
	                   'IBLOCK_ID',
	                   'IBLOCK_SECTION_ID',
	                   'NAME',
	                   'DATE_ACTIVE_FROM',
	                   'PROPERTY_MATERIAL',
	                   'PROPERTY_ARTNUMBER',
	                   'PROPERTY_PRICE',];

	$Res = CIBlockElement::GetList(false, $arFilter, false, false, $arSelectFields);

	$arResult['COUNT'] = 0;
	$arResult['MAX'] = 0;
	$arResult['MIN'] = NULL;
	while ($item = $Res->Fetch())
	{
		switch ($item['IBLOCK_ID'])
		{
			case $arParams['NEWS_IB_ID']:
				$cat =& $arResult['NEWS'][intval($item['ID'])];
				$cat['NAME'] = $item['NAME'];
				$cat['DATE_ACTIVE_FROM'] = $item['DATE_ACTIVE_FROM'];
				break;
			case $arParams['CAT_IB_ID']:
				$cat =& $arResult['PRODUCTS'][intval($item['IBLOCK_SECTION_ID'])][intval($item['ID'])];
				$cat['NAME'] = $item['NAME'];
				$cat['PROPERTY_PRICE_VALUE'] = intval($item['PROPERTY_PRICE_VALUE']);
				$cat['PROPERTY_MATERIAL_VALUE'] = $item['PROPERTY_MATERIAL_VALUE'];
				$cat['PROPERTY_ARTNUMBER_VALUE'] = $item['PROPERTY_ARTNUMBER_VALUE'];

				if ($arResult['MIN']===NULL)
					$arResult['MIN']=$cat['PROPERTY_PRICE_VALUE'];

				$arResult['COUNT']++;
				$arResult['MAX'] = max($arResult['MAX'], $cat['PROPERTY_PRICE_VALUE']);
				$arResult['MIN'] = min($arResult['MAX'], $cat['PROPERTY_PRICE_VALUE']);
				break;
			default:
				continue;
		}
	}

	//count
	$this->setResultCacheKeys('COUNT');

	$this->includeComponentTemplate();
}

$APPLICATION->SetTitle(Loc::getMessage("TITLE") . $arResult['COUNT']);