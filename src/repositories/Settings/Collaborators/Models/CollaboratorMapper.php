<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;

class CollaboratorMapper
{
	public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
	{
		return new CollaboratorEntity(
			$dbData->id,
			$dbData->tenant_id,
			$dbData->name,
			$dbData->firstName,
			$dbData->created_at,
			$dbData->updated_at,
			$dbData->deleted_at
		);
	}
}