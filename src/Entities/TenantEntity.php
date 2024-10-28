<?php

namespace Vertuoza\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TenantEntity extends Model
{
    public $incrementing = false;
    protected $table = 'tenant';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'owner_id'
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            CollaboratorEntity::class
        );
    }

    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(
            CollaboratorEntity::class,
            'collaborator_tenant',
            'tenant_id',
            'collaborator_id'
        );
    }
}
