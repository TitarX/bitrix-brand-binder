<?php

namespace Pro\CoreCode\BrandBinder;

use CIBlock;
use CIBlockElement;

class IblockHelper
{
    public static function getIblockName($iblockId): string
    {
        $iblockResult = CIBlock::GetByID($iblockId);
        if ($iblock = $iblockResult->GetNext()) {
            return $iblock['NAME'];
        } else {
            return '';
        }
    }

    public static function getElementsCount($iblockId, $arFilterExtra = array()): string
    {
        $arFilter = array(
            'IBLOCK_ID' => $iblockId
        );
        $arFilter = array_merge($arFilter, $arFilterExtra);
        return CIBlockElement::GetList(array(), $arFilter, array(), false, array());
    }

    public static function getElementsValues($iblockId, $arSelectFields, $arFilterExtra = array(), $limit = 0, $piece = 0): array
    {
        $arOrder = array('ID' => 'ASC');
        $arFilter = array(
            'IBLOCK_ID' => $iblockId
        );
        $arFilter = array_merge($arFilter, $arFilterExtra);
        $arGroupBy = false;

        $arNavStartParams = false;
        if (is_numeric($limit) && $limit > 0) {
            $arNavStartParams = array(
                'nPageSize' => $limit
            );
            if (is_numeric($piece) && $piece > 0) {
                $arNavStartParams['iNumPage'] = $piece;
                $arNavStartParams['checkOutOfRange'] = true;
            }
        }

        $returnValue = array();
        $cIBlockResult = CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelectFields);
        while ($arElement = $cIBlockResult->GetNext()) {
            if (is_array($arElement) && !empty($arElement)) {
                $returnValue[] = $arElement;
            }
        }

        return $returnValue;
    }

    public static function setPropertyValue($elementId, $iblockId, $values): void
    {
        CIBlockElement::SetPropertyValuesEx($elementId, $iblockId, $values);
    }
}
