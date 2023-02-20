<?php

namespace App\Models;

use App\Models\Scopes\AllowedFilterSearch;
use App\Models\Scopes\AllowedSort;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contact extends Model
{
    use HasFactory, SoftDeletes, AllowedFilterSearch, AllowedSort;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'address', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
