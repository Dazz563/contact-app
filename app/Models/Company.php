<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    // BOTH THESE METHODS BELOW ALLOW FOR: Company::create($incomingData); else,
    // you will have to assign every single field...
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

}
