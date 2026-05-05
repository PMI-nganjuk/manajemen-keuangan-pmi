<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationProfile extends Model
{
    use HasFactory;

    protected $table = 'organization_profiles';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'organization_name',
        'address',
        'chairperson',
        'headquarters_treasurer',
        'blood_donation_unit_treasurer',
        'financial_period_start',
        'financial_period_end',
        'fiscal_year',
    ];
}

