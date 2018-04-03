<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
	die();
use Bitrix\Main\Localization\Loc;
$arComponentParameters = ["GROUPS"     => [],
                          "PARAMETERS" => ["CAT_IB_ID"   => ["NAME"    => Loc::getMessage("CAT_ID"),
                                                             "TYPE"    => "STRING",
                                                             "DEFAULT" => "2",
                                                             "PARENT"  => "BASE",],
                                           "NEWS_IB_ID"  => ["NAME"    => Loc::getMessage("NEWS_ID"),
                                                             "TYPE"    => "STRING",
                                                             "DEFAULT" => "1",
                                                             "PARENT"  => "BASE",],
                                           "CAT_UF_PROP" => ["NAME"    => Loc::getMessage("CAT_PROP"),
                                                             "TYPE"    => "STRING",
                                                             "DEFAULT" => "UF_NEWS_LINK",
                                                             "PARENT"  => "BASE",],
                                           "CACHE_TIME"  => ['DEFAULT'=>'3600000'],]];
?>