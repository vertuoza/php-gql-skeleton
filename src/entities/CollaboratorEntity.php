<?php

namespace Vertuoza\Entities;

use Illuminate\Database\Eloquent\Model;

class CollaboratorEntity extends Model
{
    public $incrementing = false;
    protected $table = 'collaborator';
    protected $primaryKey = 'id';
}
