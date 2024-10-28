<?php

namespace Vertuoza\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CollaboratorEntity extends Model
{
    public $incrementing = false;
    protected $table = 'collaborator';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id', 'username', 'password', 'firstname', 'lastname', 'fullname', 'admin'
    ];

    /**
     * @return string
     */
    public function getFullnameAttribute(): string
    {
        return trim(
            implode(' ', [
                $this->firstname,
                $this->lastname
            ])
        );
    }

    /**
     * @return BelongsToMany
     */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(
            TenantEntity::class,
            'collaborator_tenant',
            'collaborator_id',
            'tenant_id'
        );
    }

    /**
     * @param string|TenantEntity $id
     *
     * @return bool
     */
    public function isCollaboratorOfTenant(string|TenantEntity $id): bool
    {
        return (bool) $this->tenants()->whereKey($id)->get()->count();
    }
}
