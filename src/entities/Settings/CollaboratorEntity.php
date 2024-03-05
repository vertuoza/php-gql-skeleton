<?php

namespace Vertuoza\Entities\Settings;

class CollaboratorEntity
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
}