<?php

namespace MorgenlandPersistentCart\Providers;

use MorgenlandPersistentCart\Contracts\CartItemRepositoryContract;
use MorgenlandPersistentCart\Exceptions\UserNotLoggedInException;
use MorgenlandPersistentCart\Repositories\CartItemRepository;
use Plenty\Log\Services\ReferenceContainer;
use Plenty\Modules\Authentication\Events\AfterAccountAuthentication;
use Plenty\Modules\Basket\Contracts\BasketItemRepositoryContract;
use Plenty\Modules\Basket\Contracts\BasketRepositoryContract;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemAdd;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemRemove;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemUpdate;
use Plenty\Modules\Basket\Models\BasketItem;
use Plenty\Modules\Plugin\Libs\Contracts\LibraryCallContract;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\Http\Request;
use Plenty\Plugin\ServiceProvider;

/**
 * Class MorgenlandPersistentCartProvider
 * @package MorgenlandPersistentCart\Providers
 */
class MorgenlandPersistentCartProvider extends ServiceProvider
{
    public function boot(
        LibraryCallContract $libCall,
        Dispatcher $dispatcher,
        ReferenceContainer $container,
        BasketItemRepositoryContract $basketItemRepository,
        BasketRepositoryContract $basketRepository,
        CartItemRepositoryContract $cartItemRepository,
    )
    {
        try{
            $dispatcher->listen(AfterBasketItemAdd::class, function($event) use ($cartItemRepository) {
                // manage all the basket items logic to db here
                $basketItem = $event->getBasketItem();
                $cartItem = $cartItemRepository->createCartItem($basketItem);
                return $cartItem;
            }, 0);

            $dispatcher->listen(AfterBasketItemRemove::class, function($event) use ($cartItemRepository) {
                $basketItem = $event->getBasketItem();
                // manage all the basket items logic
                $cartItem = $cartItemRepository->deleteCartItem($basketItem->id);
                return $cartItem;

            }, 0);
            $dispatcher->listen(AfterBasketItemUpdate::class, function($event) use ($cartItemRepository) {
                $basketItem  = $event->getBasketItem();
                // manage all other events here.
                $cartItem = $cartItemRepository->updateCartItem($basketItem->id, $basketItem);
                return $cartItem;
            }, 0);

            $dispatcher->listen(
                AfterAccountAuthentication::class, function(AfterAccountAuthentication $event
            ) use (
                $cartItemRepository, $basketItemRepository, $basketRepository,
            ){
                // get the current user
                $contact = $event->getAccountContact();
                $userId = $contact->userId;
                $userCartItems = $cartItemRepository->getCartForUser($userId);

                $userBasket = $basketRepository->load();

                foreach ($userCartItems as $userCartItem) {

                    $basketItemData = [];
                    $basketItemData["id"] = $userBasket->id;
                    $basketItemData["variationId"] = $userCartItem->variation_id;
                    $basketItemData["quantity"] = $userCartItem->quantity;
                    $basketItemData["price"] = $userCartItem->price;
                    $basketItemData["variationPrice"] = $userCartItem->variation_price;

                    $basketItem = $basketItemRepository->findExistingOneByData($basketItemData);

                    if ($basketItem instanceof BasketItem) {
                        $existingBasketItemDataId = $basketItem->id;
                        $basketItemRepository->updateBasketItem($existingBasketItemDataId, $basketItem);
                    } else {
                        $basketItemRepository->addBasketItem($basketItem);
                    }
                }
            });

        }
        catch (UserNotLoggedInException $e){

        }
        catch(\Exception $exception){
            $error = $exception->__toString();
            $cartItemRepository->log("EXCEPTION OCCURRED: $error");
        }
    }

    /*
     * Register the service provider
     *
     * @returns void
     */
    public function register(): void
    {
        $this->getApplication()->register(MorgenlandPersistentCartRoutesProvider::class);
        $this->getApplication()->bind(CartItemRepositoryContract::class, CartItemRepository::class);
    }
}
