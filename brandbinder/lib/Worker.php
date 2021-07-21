<?php

namespace Pro\CoreCode\BrandBinder;

class Worker
{
    public static function start($productIblockId, $brandIblockId, $productProcessDisabledElements, $productDoNotRewriteExistingValues): void
    {
        $arFilterExtra = array();
        if ($productProcessDisabledElements === '0') {
            $arFilterExtra['ACTIVE'] = 'Y'; // Только активные элементы
        }
        if ($productDoNotRewriteExistingValues === '0') {
            $arFilterExtra['PROPERTY_BRAND'] = array(0, false); // Только элементы с пустым значением или значением 0
        }
        $arFilterExtra['!PROPERTY_BREND'] = false; // Исключение элементов, которым не задан бренд

        $piece = 1;
        $doneProductCount = 0;

        $totalProductsCount = IblockHelper::getElementsCount($productIblockId, $arFilterExtra);
        RuntimeDataHelper::updateSingleValue(
            RuntimeDataHelper::getTotalProductsCountQueryString(),
            $totalProductsCount
        );

        $productIblockName = IblockHelper::getIblockName($productIblockId);
        RuntimeDataHelper::updateSingleValue(
            RuntimeDataHelper::getProductIblockNameQueryString(),
            $productIblockName
        );

        $brandIblockName = IblockHelper::getIblockName($brandIblockId);
        RuntimeDataHelper::updateSingleValue(
            RuntimeDataHelper::getBrandIblockNameQueryString(),
            $brandIblockName
        );

        $arSelectFields = array('ID', 'NAME', 'PROPERTY_PRODUCT_BRAND_NAME');
        $brandsElementsValues = IblockHelper::getElementsValues($brandIblockId, $arSelectFields);

        $arSelectFields = array('ID', 'PROPERTY_BREND', 'PROPERTY_BRAND');
        do {
            RuntimeDataHelper::updateSingleValue(
                RuntimeDataHelper::getPieceQueryString(),
                $piece
            );
            RuntimeDataHelper::updateSingleValue(
                RuntimeDataHelper::getRecallTimestampQueryString(),
                time()
            );
            RuntimeDataHelper::updateSingleValue(
                RuntimeDataHelper::getDoneProductCountQueryString(),
                $doneProductCount
            );

            $productsElementsValues = IblockHelper::getElementsValues($productIblockId, $arSelectFields, $arFilterExtra, Settings::getPieceSize(), $piece);
            foreach ($productsElementsValues as $productElementValue) {
                $brandId = self::findBrandId($productElementValue['~PROPERTY_BREND_VALUE'], $brandsElementsValues);
                if (!empty($brandId)) {
                    IblockHelper::setPropertyValue($productElementValue['ID'], $productIblockId, array('BRAND' => $brandId));
                    usleep(100000);
                }

                $doneProductCount++;
            }

            $piece++;
            sleep(1);
        } while (!empty($productsElementsValues));
    }

    private static function findBrandId($brandName, $brandsElementsValues): ?string
    {
        $brandName = trim($brandName);
        $brandId = null;

        foreach ($brandsElementsValues as $brandElementValue) {
            if ($brandName === trim($brandElementValue['~NAME'])) {
                $brandId = $brandElementValue['ID'];
                break;
            }
        }

        if (empty($brandId)) {
            foreach ($brandsElementsValues as $brandElementValue) {
                if ($brandName === trim($brandElementValue['~PROPERTY_PRODUCT_BRAND_NAME_VALUE'])) {
                    $brandId = $brandElementValue['ID'];
                    break;
                }
            }
        }

        return $brandId;
    }
}
