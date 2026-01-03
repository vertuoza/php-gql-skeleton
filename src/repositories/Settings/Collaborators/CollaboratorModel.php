<?php

namespace Vertuoza\Repositories\Settings\Collaborators;

use DateTime;
use stdClass;

class CollaboratorModel
{
    public string $id;
    public string $firstname;
    public string $name;
    public ?DateTime $deleted_at;
    public ?string $tenant_id;

    public static function fromStdclass(stdClass $data): CollaboratorModel
    {
        $model = new CollaboratorModel();
        $model->id = $data->id;
        $model->firstname = $data->first_name;
        $model->name = $data->name;
        $model->deleted_at = $data->deleted_at;
        $model->tenant_id = $data->tenant_id;
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
