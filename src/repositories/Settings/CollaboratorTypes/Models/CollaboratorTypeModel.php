<?php

namespace Vertuoza\Repositories\Settings\CollaboratorTypes\Models;

use DateTime;
use stdClass;

class CollaboratorTypeModel
{
    public string $id;
    public string $name;
    public string $first_name;
    public ?string $tenant_id;
    public ?DateTime $created_at;
    public ?DateTime $updated_at;
    public ?DateTime $deleted_at;

    public static function fromStdclass(stdClass $data): CollaboratorTypeModel
    {
        $model = new CollaboratorTypeModel();
        $model->id = $data->id;
        $model->name = $data->name;
        $model->first_name = $data->first_name;
        $model->tenant_id = $data->tenant_id;
        $model->created_at = $data->created_at ? new DateTime($data->created_at) : null;
        $model->updated_at = $data->updated_at ? new DateTime($data->updated_at) : null;
        $model->deleted_at = $data->deleted_at ? new DateTime($data->deleted_at) : null;
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
