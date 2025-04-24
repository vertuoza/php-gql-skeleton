<?php

namespace Vertuoza\Repositories\Settings\Collaborators;

use Vertuoza\Repositories\BaseRepository;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorMapper;
use Vertuoza\Repositories\Settings\Collaborators\Models\CollaboratorModel;

class CollaboratorRepository extends BaseRepository
{

  /**
   * Return collaborator model class
   *
   * @return string
   */
  protected function getModelClass(): string
  {
    return CollaboratorModel::class;
  }
  
  /**
   * Return collaborator mapper class
   *
   * @return string
   */
  protected function getMapperClass(): string
  {
    return CollaboratorMapper::class;
  }
}
