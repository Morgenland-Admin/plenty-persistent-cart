<?php

namespace MorgenlandPersistentCart\Providers;

use MorgenlandPersistentCart\Contracts\CartItemRepositoryContract;
use MorgenlandPersistentCart\Exceptions\UserNotLoggedInException;
use MorgenlandPersistentCart\Repositories\CartItemRepository;
use Plenty\Log\Services\ReferenceContainer;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemAdd;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemRemove;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemUpdate;
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
    public function boot(LibraryCallContract $libCall, Request $request, Dispatcher $dispatcher, ReferenceContainer $container)
    {
        $cartItemRepository = pluginApp(CartItemRepository::class);
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
                $cartItem = $cartItemRepository->updateItem($basketItem->id, $basketItem);
                return $cartItem;

            }, 0);
            $dispatcher->listen(AfterBasketItemUpdate::class, function($event) use ($cartItemRepository) {
                $basketItem  = $event->getBasketItem();
                // manage all other events here.
                $cartItem = $cartItemRepository->updateItem($basketItem->id, $basketItem);
                return $cartItem;
            }, 0);

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
