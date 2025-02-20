<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

use Vertuoza\Entities\Settings\CollaboratorEntity;

class CollaboratorMapper
{
	public static function modelToEntity(CollaboratorModel $dbData): CollaboratorEntity
	{
		$collaborator = new CollaboratorEntity();
		$collaborator->id = $dbData->id;
		$collaborator->name = $dbData->name;
		$collaborator->firstName = $dbData->first_name;
		$collaborator->deleted_at = $dbData->deleted_at;

		return $collaborator;
	}
}