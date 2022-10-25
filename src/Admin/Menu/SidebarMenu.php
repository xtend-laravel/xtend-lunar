<?php

namespace Xtend\Extensions\Lunar\Admin\Menu;

use Lunar\Hub\Facades\Menu;
use Lunar\Hub\Menu\MenuSlot;

class SidebarMenu
{
    protected MenuSlot $slot;

    /**
     * Make menu.
     *
     * @return void
     */
    public static function make()
    {
        (new static())
            ->makeTopLevel();
    }

    /**
     * Make top level navigation.
     *
     * @return static
     */
    protected function makeTopLevel()
    {
        $this->slot = Menu::slot('sidebar');

        $this->slot->addItem(function ($item) {
            $item->name(
                __('adminhub::menu.sidebar.index')
            )->handle('hub.index')
            ->route('hub.index')
            ->icon('chart-square-bar');
        });

        $this->makeCatalogueSection();
        $this->makeOrdersSection();

        return $this;
    }

    /**
     * Make the catalogue section.
     *
     * @return void
     */
    protected function makeCatalogueSection(): void
    {
        $catalogue = $this->slot->section('catalogue-manager')->name(
            __('adminhub::menu.sidebar.catalogue.group')
        );

        $catalogue->addItem(function ($item) {
            $item->name(
                __('adminhub::menu.sidebar.brands')
            )->handle('hub.brands')
                 ->route('hub.brands.index')
                 ->icon('view-grid');
        });

        $catalogue->addItem(function ($item) {
            $item->name(
                __('adminhub::menu.sidebar.collections')
            )->handle('hub.collection')
                 ->route('hub.collection-groups.index')
                 ->icon('collection');
        });

        $catalogue->addItem(function ($item) {
            $item->name(
                __('adminhub::menu.sidebar.products')
            )->handle('hub.products')
            ->route('hub.products.index')
            ->icon('shopping-bag');
        });

        $catalogue->addItem(function ($item) {
            $item->name('Product Features')
                 ->handle('hub.product-features')
                 ->route('hub.product-features.index')
                 ->gate('settings:core')
                 ->icon('clipboard-list');
        });

        $catalogue->addItem(function ($item) {
            $item->name('Product Options')
                 ->handle('hub.product-options')
                 ->route('hub.product-options.index')
                 ->gate('settings:core')
                 ->icon('clipboard-list');
        });

        // $catalogue->addItem(function ($item) {
        //     $item->name(
        //         __('adminhub::menu.sidebar.product-types')
        //     )->handle('hub.product-type')
        //     ->route('hub.product-types.index')
        //     ->icon('pencil');
        // });
    }

    /**
     * Make the orders section.
     *
     * @return void
     */
    protected function makeOrdersSection(): void
    {
        $orders = $this->slot->section('order-processing')->name(
            __('adminhub::menu.sidebar.sales.group')
        );

        $orders->addItem(function ($item) {
            $item->name(
                __('adminhub::menu.sidebar.orders')
            )->handle('hub.orders')
                 ->route('hub.orders.index')
                 ->icon('cash');
        });

        $orders->addItem(function ($item) {
            $item->name(
                __('adminhub::menu.sidebar.carts')
            )->handle('hub.carts')
                 ->route('hub.carts.index')
                 ->icon('shopping-cart');
        });

        $orders->addItem(function ($item) {
            $item->name(
                __('adminhub::menu.sidebar.customers')
            )->handle('hub.customers')
                 ->route('hub.customers.index')
                 ->icon('users');
        });
    }
}
