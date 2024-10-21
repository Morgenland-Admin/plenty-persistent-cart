<?php

namespace MorgenlandPersistentCart\Models;

/**
 * Class CartItem
 *
 * @property int    $plentyUserId
 * @property int    $id
 * @property int    $basketId
 * @property string $sessionId
 * @property int    $orderRowId
 * @property float  $quantity
 * @property float  $quantityOriginally
 * @property int    $itemId
 * @property int    $priceId
 * @property int    $attributeValueSetId
 * @property int    $rebate
 * @property float  $vat
 * @property float  $price
 * @property float  $givenPrice
 * @property int    $givenVatId
 * @property bool   $useGivenPrice
 * @property int    $inputWidth
 * @property int    $inputLength
 * @property int    $inputHeight
 * @property int    $itemType
 * @property string $externalItemId
 * @property bool   $noEditByCustomer
 * @property int    $costCenterId
 * @property int    $giftPackageForRowId
 * @property int    $position
 * @property string $size
 * @property int    $shippingProfileId
 * @property float  $referrerId
 * @property string $deliveryDate
 * @property int    $categoryId
 * @property int    $reservationDatetime
 * @property int    $variationId
 * @property int    $bundleVariationId
 * @property string $createdAt
 * @property string $updatedAt
 * @property float  $attributeTotalMarkup
 * @property array  $basketItemOrderParams
 * @property array  $basketItemVariationProperties
 *
 */
class CartItem
{
    /**
     *
     * @var int
     */
    protected $primaryKeyFieldName      = 'id';
    protected $primaryKeyFieldType      = 'int';
    protected $autoIncrementPrimaryKey  = 'false';

    public		$id;
    public    $plentyUserId= 0;

    public		$basketId;

    public		$sessionId;

    public		$orderRowId;

    public		$quantity;

    public		$quantityOriginally;

    public		$itemId;

    public		$priceId;

    public		$attributeValueSetId;

    public		$rebate;

    public		$vat;

    public		$price;

    public		$givenPrice;

    public		$givenVatId;

    public		$useGivenPrice;

    public		$inputWidth;

    public		$inputLength;

    public		$inputHeight;

    public		$itemType;

    public		$externalItemId;

    public		$noEditByCustomer;

    public		$costCenterId;

    public		$giftPackageForRowId;

    public		$position;

    public		$size;

    public		$shippingProfileId;

    public		$referrerId;

    public		$deliveryDate;

    public		$categoryId;

    public		$reservationDatetime;

    public		$variationId;

    public		$bundleVariationId;

    public		$createdAt;

    public		$updatedAt;

    public		$attributeTotalMarkup;

    public		$basketItemOrderParams;

    public		$basketItemVariationProperties;

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return 'MorgenlandPersitentCart::CartItem';
    }

    /**
     * @Returns this model as an array
     */
    public function toArray(): array
    {
        return [];
    }
}