<?php

namespace Vertuoza\Entities;

use Illuminate\Database\Eloquent\Model;

class TenantEntity extends Model
{
    public $incrementing = false;
    protected $table = 'tenant';
    protected $primaryKey = 'id';
}
