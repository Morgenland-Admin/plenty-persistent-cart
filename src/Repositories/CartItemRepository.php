<?php
namespace MorgenlandPersistentCart\Repositories;

use MorgenlandPersistentCart\Contracts\CartItemRepositoryContract;
use MorgenlandPersistentCart\Exceptions\UserNotLoggedInException;
use MorgenlandPersistentCart\Models\CartItem;
use Plenty\Modules\Basket\Models\BasketItem;
use Plenty\Modules\Frontend\Services\AccountService;
use Plenty\Modules\Plugin\DataBase\Contracts\DataBase;
use Plenty\Plugin\Log\Loggable;

class CartItemRepository implements CartItemRepositoryContract
{
    use Loggable;

    public function __construct(
        protected AccountService $accountService,
    ){}

    public function getCartForUser(int $userId): array
    {
        // TODO: Implement getCartForUser() method.
        $database = pluginApp(DataBase::class);

        $cartItemList = $database->query(CartItem::class)
            ->where('user_id', '=', $userId)
            ->get();

        return $cartItemList;

    }


    /**
     * Create a new cart item, this will be in sync with the user basket items
     * @param BasketItem $data
     * @return CartItem
     * @throws UserNotLoggedInException
     * */
    public function createCartItem(BasketItem $data): CartItem
    {

        $isAccountLoggedIn = $this->accountService->getIsAccountLoggedIn();
        if ($isAccountLoggedIn) {
            throw new UserNotLoggedInException("User is not logged in exception");
        }
        $userId = $this->accountService->getAccountContactId();
        $this
            ->getLogger('Repository::createCartItem')
            ->setReferenceType('permaCart' ) // additional information is optional
            ->setReferenceValue("$userId"."__"."$data->id") // additional information is optional
            ->info("Repository::createCartItem", ["userId"=> $userId, "basketItem->id" => $data->id, "saved"=>True]);
        /**
         * @var DataBase $database
         */
        $database = pluginApp(DataBase::class);
        $cartItem = pluginApp(CartItem::class);


        // Mapping fields from BasketItem to CartItem
        $cartItem->price = $data->price;
        $cartItem->id = $data->id;
        $cartItem->sessionId = $data->sessionId;
        $cartItem->basketId = $data->basketId;
        $cartItem->orderRowId = $data->orderRowId;
        $cartItem->quantity = $data->quantity;
        $cartItem->quantityOriginally = $data->quantityOriginally;
        $cartItem->itemId = $data->itemId;
        $cartItem->priceId = $data->priceId;
        $cartItem->attributeValueSetId = $data->attributeValueSetId;
        $cartItem->rebate = $data->rebate;
        $cartItem->vat = $data->vat;
        $cartItem->givenPrice = $data->givenPrice;
        $cartItem->givenVatId = $data->givenVatId;
        $cartItem->useGivenPrice = $data->useGivenPrice;
        $cartItem->inputWidth = $data->inputWidth;
        $cartItem->inputLength = $data->inputLength;
        $cartItem->inputHeight = $data->inputHeight;
        $cartItem->itemType = $data->itemType;
        $cartItem->externalItemId = $data->externalItemId;
        $cartItem->noEditByCustomer = $data->noEditByCustomer;
        $cartItem->costCenterId = $data->costCenterId;
        $cartItem->giftPackageForRowId = $data->giftPackageForRowId;
        $cartItem->position = $data->position;
        $cartItem->size = $data->size;
        $cartItem->shippingProfileId = $data->shippingProfileId;
        $cartItem->referrerId = $data->referrerId;
        $cartItem->deliveryDate = $data->deliveryDate;
        $cartItem->categoryId = $data->categoryId;
        $cartItem->reservationDatetime = $data->reservationDatetime;
        $cartItem->variationId = $data->variationId;
        $cartItem->bundleVariationId = $data->bundleVariationId;
        $cartItem->createdAt = $data->createdAt;
        $cartItem->updatedAt = $data->updatedAt;
        $cartItem->attributeTotalMarkup = $data->attributeTotalMarkup;
        $cartItem->basketItemOrderParams = $data->basketItemOrderParams;
        $cartItem->basketItemVariationProperties = $data->basketItemVariationProperties;

        $cartItem->plentyUserId = $userId;

        // Optionally, insert or update the CartItem in the database
        $database->save($cartItem);

        $this
            ->getLogger('Repository::CreateCartItem')
            ->setReferenceType('permaCart' ) // additional information is optional
            ->setReferenceValue("$userId"."__"."$data->id") // additional information is optional
            ->info("Repository::createCartItem", ["userId"=> $userId, "cartItem->id" => $data->id, "saved"=>True]);
        return $cartItem;
    }

    /**
     * Update the cart item in the database with updated data
     *
     * @param int $cartItemId
     * @param BasketItem $data
     * @return CartItem
     * @throws UserNotLoggedInException
     */
    public function updateCartItem(int $cartItemId, BasketItem $data): CartItem
    {
        /**
         * @var DataBase $database
         */
        $isAccountLoggedIn = $this->accountService->getIsAccountLoggedIn();
        if ($isAccountLoggedIn) {
            throw new UserNotLoggedInException("User is not logged in exception");
        }
        $this
            ->getLogger('Repository::updateCartItem')
            ->setReferenceType('permaCart' ) // additional information is optional
            ->setReferenceValue("$cartItemId"."__"."$data->id") // additional information is optional
            ->info("Repository::updateCartItem", ["cartItemId"=> $cartItemId, "basketItem->id" => $data->id, "updated"=>False]);
        $database = pluginApp(DataBase::class);

        $cartItemList = $database->query(CartItem::class)
            ->where('id', '=', $cartItemId)
            ->get();

        $cartItem = $cartItemList[0];


        // Mapping fields from BasketItem to CartItem
        $cartItem->price = $data->price;
        $cartItem->id = $data->id;
        $cartItem->sessionId = $data->sessionId;
        $cartItem->basketId = $data->basketId;
        $cartItem->orderRowId = $data->orderRowId;
        $cartItem->quantity = $data->quantity;
        $cartItem->quantityOriginally = $data->quantityOriginally;
        $cartItem->itemId = $data->itemId;
        $cartItem->priceId = $data->priceId;
        $cartItem->attributeValueSetId = $data->attributeValueSetId;
        $cartItem->rebate = $data->rebate;
        $cartItem->vat = $data->vat;
        $cartItem->givenPrice = $data->givenPrice;
        $cartItem->givenVatId = $data->givenVatId;
        $cartItem->useGivenPrice = $data->useGivenPrice;
        $cartItem->inputWidth = $data->inputWidth;
        $cartItem->inputLength = $data->inputLength;
        $cartItem->inputHeight = $data->inputHeight;
        $cartItem->itemType = $data->itemType;
        $cartItem->externalItemId = $data->externalItemId;
        $cartItem->noEditByCustomer = $data->noEditByCustomer;
        $cartItem->costCenterId = $data->costCenterId;
        $cartItem->giftPackageForRowId = $data->giftPackageForRowId;
        $cartItem->position = $data->position;
        $cartItem->size = $data->size;
        $cartItem->shippingProfileId = $data->shippingProfileId;
        $cartItem->referrerId = $data->referrerId;
        $cartItem->deliveryDate = $data->deliveryDate;
        $cartItem->categoryId = $data->categoryId;
        $cartItem->reservationDatetime = $data->reservationDatetime;
        $cartItem->variationId = $data->variationId;
        $cartItem->bundleVariationId = $data->bundleVariationId;
        $cartItem->createdAt = $data->createdAt;
        $cartItem->updatedAt = $data->updatedAt;
        $cartItem->attributeTotalMarkup = $data->attributeTotalMarkup;
        $cartItem->basketItemOrderParams = $data->basketItemOrderParams;
        $cartItem->basketItemVariationProperties = $data->basketItemVariationProperties;
        $database->save($cartItem);

        $this
            ->getLogger('Repository::updateCartItem')
            ->setReferenceType('permaCart' ) // additional information is optional
            ->setReferenceValue("$cartItemId"."__"."$data->id") // additional information is optional
            ->info("Repository::updateCartItem", ["cartItemId"=> $cartItemId, "basketItem->id" => $data->id, "updated"=>False]);

        return $cartItem;
    }

    /**
     * Delete a cart item from the Morgenland database's cart item
     *
     * @param int $cartItemId
     * @return CartItem
     * @throws UserNotLoggedInException
     */
    public function deleteCartItem(int $cartItemId): CartItem
    {
        /**
         * @var DataBase $database
         */
        $database = pluginApp(DataBase::class);
        $isAccountLoggedIn = $this->accountService->getIsAccountLoggedIn();
        if ($isAccountLoggedIn) {
            throw new UserNotLoggedInException("User is not logged in exception");
        }
        $userId = $this->accountService->getAccountContactId();
        $this
            ->getLogger('Repository::deleteCartItem')
            ->setReferenceType('permaCart' ) // additional information is optional
            ->setReferenceValue("$cartItemId") // additional information is optional
            ->info("Repository::updateCartItem", ["deleted"=>False]);

        $cartItemList = $database->query(CartItem::class)
            ->where('id', '=', $cartItemId)
            ->get();

        $cartItem = $cartItemList[0];
        $database->delete($cartItem);
        $this
            ->getLogger('Repository::deleteCartItem')
            ->setReferenceType('permaCart' ) // additional information is optional
            ->setReferenceValue("$cartItemId") // additional information is optional
            ->info("Repository::updateCartItem", ["deleted"=>True]);

        return $cartItem;
    }
}