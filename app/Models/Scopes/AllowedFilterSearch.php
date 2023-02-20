<?php


namespace App\Models\Scopes;

use Illuminate\Contracts\Database\Eloquent\Builder;

trait AllowedFilterSearch
{
    public function scopeAllowedFilters(Builder $query, ...$keys)
    {
        foreach ($keys as $key) {
            if ($value = request()->query($key)) {
                $query->where('company_id', $value);
            }
        }

        return $query;
    }

    public function scopeAllowedSearch(Builder $query, ...$keys)
    {
        if ($search = request()->query('search')) {
            foreach ($keys as $index => $key) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $query->{$method}($key, "LIKE", "%{$search}%");
            }
        }

        return $query;
    }

    public function scopeAllowedTrash(Builder $query)
    {
        if (request()->query('trash')) {
            $query->onlyTrashed();
        }

        return $query;
    }
}
