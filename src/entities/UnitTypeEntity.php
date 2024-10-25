<?php

namespace Vertuoza\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UnitTypeEntity extends Model
{
    public $incrementing = false;
    protected $table = 'unit_type';
    public $keyType = 'string';
    protected $primaryKey = 'id';
    protected $tenantKey = 'tenant_id';

    protected $fillable = [
        'id', 'label'
    ];
}
