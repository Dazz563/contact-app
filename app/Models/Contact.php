<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\SimpleSoftDeletes;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\SimpleSoftDeletingScope;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'address', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function scopeAllowedSorts(Builder $query, string $column)
    {
        return $query->orderBy($column);
    }

    public function scopeAllowedFilters(Builder $query, string $key)
    {
        if ($companyId = request()->query($key)) {
            $query->where('company_id', $companyId);
        }

        return $query;
    }

    public function scopeAllowedSearch(Builder $query, array $keys)
    {
        if ($search = request()->query('search')) {
            foreach ($keys as $index => $key) {
                $method = $index === 0 ? 'where' : 'orWhere';
                $query->{$method}($key, "LIKE", "%{$search}%");
            }
        }

        return $query;
    }
}
