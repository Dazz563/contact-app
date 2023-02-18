<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    // BOTH THESE METHODS BELOW ALLOW FOR MASS ASSIGNMENT: Company::create($incomingData); else,
    // OR...
    // you will have to assign every single field like this...
    // $company = new Company;
    // $company->name = $req->companyName
    // $company->address = $req->address
    /**
     * if guarded is an empty array no field will be whitelisted (which columns are not mass assignable)
     * opposite of fillable (if you add columns that don't exist in the table it will error)
     */
    // protected $guarded = [];
    /**
     * if you use fillable you can set which fields should be mass assigned, if there are
     * non existing fields in th request they will be dropped and no error will be thrown.
     */
    protected $fillable = ['name', 'email', 'address', 'website'];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }
}
