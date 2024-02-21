<?php

declare(strict_types=1);

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use DateTimeImmutable;
use Exception;
use Vertuoza\Entities\Settings\CollaboratorEntity;

/**
 * The mapper for the various collaborator representations.
 *
 * @phpstan-type RowObjectShape object{
 *   id: string,
 *   tenant_id: string,
 *   name: string,
 *   first_name: string,
 *   created_at: string|null,
 *   updated_at: string|null,
 *   deleted_at: string|null
 * }
 */
class CollaboratorMapper
{
    /** Map model to API entity */
    public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
    {
        return new CollaboratorEntity(
            name: $dbData->name,
            firstName: $dbData->first_name,
            id: $dbData->id,
        );
    }

    /**
     * Create a new model from stdClass object.
     * @param RowObjectShape $row
     * @throws Exception
     */
    public static function objectToModel(object $row): CollaboratorModel
    {
        return new CollaboratorModel(
            $row->id,
            $row->tenant_id,
            $row->name,
            $row->first_name,
            $row->created_at ? new DateTimeImmutable($row->created_at) : null,
            $row->updated_at ? new DateTimeImmutable($row->updated_at) : null,
            $row->deleted_at ? new DateTimeImmutable($row->deleted_at) : null,
        );
    }

    /**
     * Create a new entity from stdClass object.
     * @param RowObjectShape $row
     * @throws Exception
     */
    public static function objectToEntity(object $row): CollaboratorEntity
    {
        return self::modelToEntity(self::objectToModel($row));
    }
}
