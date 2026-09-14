<?php

namespace Vertuoza\Repositories\Collaborators\Models;

use DateTime;
use stdClass;

class CollaboratorModel
{
    public string $id;
    public string $name;
    public string $first_name;
    public ?DateTime $deleted_at;
    public ?string $tenant_id;

    public static function fromStdclass(stdClass $data): CollaboratorModel
    {
        $collaborator = new CollaboratorModel();
        $collaborator->id = $data->id;
        $collaborator->name = $data->name;
        $collaborator->first_name = $data->first_name;
        $collaborator->deleted_at = isset($data->deleted_at) ? new DateTime($data->deleted_at) : null;
        $collaborator->tenant_id = $data->tenant_id;
        return $collaborator;
    }

    public static function getPkColumnName(): string
    {
        return 'id';
    }

    public static function getTenantColumnName(): string
    {
        return 'tenant_id';
    }

    public static function getTableName(): string
    {
        return 'collaborator';
    }
}