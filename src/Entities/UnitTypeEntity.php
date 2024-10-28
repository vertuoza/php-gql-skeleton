<?php

namespace Vertuoza\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UnitTypeEntity extends Model
{
    public $incrementing = false;
    protected $table = 'unit_type';
    public $keyType = 'string';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'label', 'tenant_id'
    ];

    public function getIsSystemAttribute(): bool
    {
        return ($this->tenant_id ?? null) === null;
    }
}
