<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use stdClass;

class CollaboratorModel
{
    public string $id;

    public string $name;
    public string $firstName;
    public ?string $tenantId;

    public static function fromStdclass(stdClass $data): CollaboratorModel
    {
        $model = new CollaboratorModel();
        $model->id = $data->id;
        $model->name = $data->name;
        $model->firstName = $data->first_name;
        $model->tenantId = $data->tenant_id;
        return $model;
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
