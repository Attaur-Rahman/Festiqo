<?php

namespace App\Services\Category;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Get paginated categories.
     */
    public function index(array $filters): LengthAwarePaginator
    {
        return Category::query()
            ->when(
                !empty($filters['search']),
                fn($query) => $query->where(function ($q) use ($filters) {
                    $q->where('name', 'like', "%{$filters['search']}%")
                        ->orWhere('description', 'like', "%{$filters['search']}%");
                })
            )
            ->when(
                array_key_exists('status', $filters),
                fn($query) => $query->where('status', $filters['status'])
            )
            ->orderBy(
                $filters['sort'] ?? 'created_at',
                $filters['direction'] ?? 'desc'
            )
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    /**
     * Get a single category.
     */
    public function show(Category $category): Category
    {
        return $category;
    }

    /**
     * Create category.
     */
    public function store(array $data): Category
    {
        $data['slug'] = Str::slug($data['name']);

        return Category::create($data);
    }

    /**
     * Update category.
     */
    public function update(Category $category, array $data): Category
    {
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);

        return $category->refresh();
    }

    /**
     * Delete category.
     */
    public function destroy(Category $category): void
    {
        $category->delete();
    }
}
