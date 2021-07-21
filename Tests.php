<?php

CModule::IncludeModule('iblock');

$arOrder = array('ID' => 'ASC');
$arFilter = array(
    '>ID' => '1',
    'IBLOCK_ID' => '25',
    'ACTIVE' => 'Y',
    'PROPERTY_BRAND' => array(0, false)
);
$arGroupBy = false;
$arNavStartParams = array(
    'nPageSize' => '10',
    'iNumPage' => '1',
    'checkOutOfRange' => true,
    'nTopCount' => 1
);
$arSelectFields = array('ID', 'PROPERTY_BREND', 'PROPERTY_BRAND');

$cIBlockResult = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);

$returnValue = array();
while ($arElement = $cIBlockResult->GetNext()) {
    if (is_array($arElement) && !empty($arElement)) {
        $returnValue[] = $arElement;
    }
}

print_r($returnValue);
print PHP_EOL;
print gettype($returnValue[0]['PROPERTY_BREND_VALUE']);
print PHP_EOL;
print gettype($returnValue[0]['PROPERTY_BRAND_VALUE']);
print PHP_EOL;
print (empty($returnValue[1]['PROPERTY_BRAND_VALUE']) ? 'Empty' : 'Not empty');
print PHP_EOL;

// --------

CModule::IncludeModule('iblock');

$arOrder = array('ID' => 'ASC');
$arFilter = array(
    'IBLOCK_ID' => '12',
    'ACTIVE' => 'Y'
);
$arGroupBy = false;
$arNavStartParams = array(
    'nPageSize' => '10',
    'iNumPage' => '1',
    'checkOutOfRange' => true,
    'nTopCount' => 1
);
$arSelectFields = array('ID', 'NAME', 'PROPERTY_PRODUCT_BRAND_NAME');

$cIBlockResult = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);

$returnValue = array();
while ($arElement = $cIBlockResult->GetNext()) {
    if (is_array($arElement) && !empty($arElement)) {
        $returnValue[] = $arElement;
    }
}

print_r($returnValue);
