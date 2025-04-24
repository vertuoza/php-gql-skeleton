<?php

namespace Vertuoza\Repositories;

use React\Promise\Promise;
use function React\Async\async;
use Overblog\DataLoader\DataLoader;
use Illuminate\Database\Query\Builder;

use Vertuoza\Repositories\Database\QueryBuilder;
use Overblog\PromiseAdapter\PromiseAdapterInterface;

abstract class BaseRepository
{
  protected array $getbyIdsDL;
  protected QueryBuilder $db;
  protected PromiseAdapterInterface $dataLoaderPromiseAdapter;

  public function __construct(
    QueryBuilder $database,
    PromiseAdapterInterface $dataLoaderPromiseAdapter
  ) {
    $this->db = $database;
    $this->dataLoaderPromiseAdapter = $dataLoaderPromiseAdapter;
    $this->getbyIdsDL = [];
  }

  /**
   * Get the model class for this repository
   * 
   * @return string Fully qualified class name of the model
   */
  abstract protected function getModelClass(): string;
  
  /**
   * Get the mapper class for this repository
   * 
   * @return string Fully qualified class name of the mapper
   */
  abstract protected function getMapperClass(): string;

  /**
   * Apply tenant and other filters to the query
   * 
   * @param mixed $query
   * @param string $tenantId
   * 
   * @return mixed Modified query
   */
  protected function applyBaseFilters($query, string $tenantId)
  {
    $modelClass = $this->getModelClass();
    
    $query->whereNull('deleted_at');
    
    // Apply tenant filter
    $query->where(function ($q) use ($tenantId, $modelClass) {
      $q->where($modelClass::getTenantColumnName(), $tenantId);
    });
    
    return $query;
  }

  /**
   * Fetch data by IDs
   *
   * @param string $tenantId
   * @param array $ids
   *
   * @return PromiseInterface
   */
  protected function fetchByIds(string $tenantId, array $ids)
  {
    return async(function () use ($tenantId, $ids) {
      $modelClass = $this->getModelClass();
      $mapperClass = $this->getMapperClass();
      
      $query = $this->getQueryBuilder();
      $query = $this->applyBaseFilters($query, $tenantId);
      $query->whereIn($modelClass::getPkColumnName(), $ids);

      $entities = $query->get()->mapWithKeys(function ($row) use ($modelClass, $mapperClass) {
        $entity = $mapperClass::modelToEntity($modelClass::fromStdclass($row));
        return [$entity->id => $entity];
      });

      // Map the IDs to the corresponding entities, preserving the order of IDs.
      return collect($ids)
        ->map(fn ($id) => $entities->get($id))
        ->toArray();
    })();
  }

  /**
   * Return data loader for collaborator
   *
   * @param string $tenantId
   *
   * @return DataLoader
   */
  protected function getDataloader(string $tenantId): DataLoader
  {
    if (!isset($this->getbyIdsDL[$tenantId])) {
      $dl = new DataLoader(function (array $ids) use ($tenantId) {
        return $this->fetchByIds($tenantId, $ids);
      }, $this->dataLoaderPromiseAdapter);
      $this->getbyIdsDL[$tenantId] = $dl;
    }

    return $this->getbyIdsDL[$tenantId];
  }

  /**
   * Return query builder for a table
   *
   * @return Builder
   */
  protected function getQueryBuilder()
  {
    $modelClass = $this->getModelClass();
    return $this->db->getConnection()->table($modelClass::getTableName());
  }

  /**
   * @param array $ids
   * @param string $tenantId
   *
   * @return Promise
   */
  public function getByIds(array $ids, string $tenantId): Promise
  {
    return $this->getDataloader($tenantId)->loadMany($ids);
  }

  /**
   * @param string $id
   * @param string $tenantId
   * 
   * @return Promise
   */
  public function getById(string $id, string $tenantId): Promise
  {
    return $this->getDataloader($tenantId)->load($id);
  }

  /**
   * Find all data from a class based on filters
   *
   * @param string $tenantId
   * 
   * @return Promise
   */
  public function findMany(string $tenantId)
  {
    $modelClass = $this->getModelClass();
    $mapperClass = $this->getMapperClass();
    
    return async(
      function () use ($tenantId, $modelClass, $mapperClass) {
        $query = $this->getQueryBuilder();
        $query = $this->applyBaseFilters($query, $tenantId);
        
        return $query->get()
          ->map(function ($row) use ($modelClass, $mapperClass) {
            return $mapperClass::modelToEntity($modelClass::fromStdclass($row));
          });
      }
    )();
  }
  
  /**
   * Clear cache
   *
   * @param string $id
   * 
   * @return void
   */
  protected function clearCache(string $id)
  {
    foreach ($this->getbyIdsDL as $dl) {
      if ($dl->key_exists($id)) {
        $dl->clear($id);
        return;
      }
    }
  }
}
