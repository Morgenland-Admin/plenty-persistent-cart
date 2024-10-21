<?php

namespace MorgenlandPersistentCarts\Migrations;

use MorgenlandPersistentCart\Models\CartItem;
use Plenty\Modules\Plugin\DataBase\Contracts\Migrate;

/**
 * Class CreateToDoTable
 */
class CreateCartItemTable
{
    /**
     * @param Migrate $migrate
     */
    public function run(Migrate $migrate)
    {
        $migrate->createTable(CartItem::class);
    }
}