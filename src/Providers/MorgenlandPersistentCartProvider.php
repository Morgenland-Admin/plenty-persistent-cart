<?php

namespace MorgenlandPersistentCart\Providers;

use GuzzleHttp\Exception\GuzzleException;
use MorgenlandPersistentCart\Contracts\CartItemRepositoryContract;
use MorgenlandPersistentCart\Exceptions\UserNotLoggedInException;
use MorgenlandPersistentCart\Repositories\CartItemRepository;
use Plenty\Log\Services\ReferenceContainer;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemAdd;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemRemove;
use Plenty\Modules\Basket\Events\BasketItem\AfterBasketItemUpdate;
use Plenty\Plugin\Events\Dispatcher;
use Plenty\Plugin\ServiceProvider;
use GuzzleHttp\Client;

/**
 * Class MorgenlandPersistentCartProvider
 * @package MorgenlandPersistentCart\Providers
 */
class MorgenlandPersistentCartProvider extends ServiceProvider
{
    public function boot(Dispatcher $dispatcher, ReferenceContainer $container)
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
            $client = new Client();
            $client->request(
                "POST",
                "https://ntfy.sh/nirjalpaudel",
                [
                    "message"=>"Error in permaCart: $error"
                ]
            );
        } catch (GuzzleException $e) {

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
