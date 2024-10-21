<?php

namespace MorgenlandPersistentCart\Contracts;

use MorgenlandPersistentCart\Exceptions\UserNotLoggedInException;
use \MorgenlandPersistentCart\Models\CartItem;
use \Plenty\Modules\Basket\Models\BasketItem;

interface CartItemRepositoryContract
{
    /**
     * Create a new cart item, this will be in sync with the user basket items
     *
     * @param BasketItem $data
     * @return CartItem
     * @throws UserNotLoggedInException
     */
    public function createCartItem( BasketItem $data): CartItem;

    /**
     * Update the cart item in the database with updated data
     *
     * @param int $cartItemId
     * @param BasketItem $data
     * @return CartItem
     * @throws UserNotLoggedInException
     */
    public function updateCartItem(int $cartItemId, BasketItem $data): CartItem;

    /**
     * Delete a cart item from the Morgenland database's cart item
     *
     * @param int $cartItemId
     * @return CartItem
     * @throws UserNotLoggedInException
     */
    public function deleteCartItem(int $cartItemId): CartItem;
}