<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
use \Bitrix\Main\Localization\Loc;
$arComponentDescription = array(
	'NAME' => Loc::getMessage("NAME"),
	'DESCRIPTION' => Loc::getMessage("DESC"),
	'PATH' => array(
		'ID' => 'exam2',
		'NAME'=>Loc::getMessage("PNAME")
	),   
);
?>