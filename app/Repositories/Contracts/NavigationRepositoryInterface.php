<?php

namespace App\Repositories\Contracts;

interface NavigationRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get the entire navigation tree with eager loaded children.
     */
    public function getTree();

    /**
     * Update the ordering of navigation items.
     * 
     * @param array $items Array of ['id' => id, 'order' => order, 'parent_id' => parent_id]
     */
    public function updateOrder(array $items): void;
}
