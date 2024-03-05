<?php

namespace Vertuoza\Repositories\Settings\Collaborators\Models;

class CollaboratorModel
{
	public function __construct(
		public string $id,
		public ?string $tenant_id,
		public string $name,
		public string $firstName,
		public ?\DateTime $created_at,
		public ?\DateTime $updated_at,
		public ?\DateTime $deleted_at
	)
	{
	}

	public static function fromStdclass(\stdClass $data): CollaboratorModel
	{
		return new CollaboratorModel(
			$data->id,
			$data->tenant_id,
			$data->name,
			$data->first_name,
			($data->created_at ? new \DateTime($data->created_at) : null),
			($data->updated_at ? new \DateTime($data->updated_at) : null),
			($data->deleted_at ? new \DateTime($data->deleted_at) : null)
		);
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