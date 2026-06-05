<?php

namespace App\Repositories\Eloquent;

use App\Models\Navigation;
use App\Repositories\Contracts\NavigationRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NavigationRepository extends BaseRepository implements NavigationRepositoryInterface
{
    public function __construct(Navigation $model)
    {
        parent::__construct($model);
    }

    public function getTree()
    {
        return $this->model
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with(['children' => function ($query) {
                $query->orderBy('order');
            }])
            ->get();
    }

    public function updateOrder(array $items): void
    {
        DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                $this->model->where('id', $item['id'])->update([
                    'order' => $item['order'],
                    'parent_id' => $item['parent_id'] ?? null,
                ]);
            }
        });
    }
}
