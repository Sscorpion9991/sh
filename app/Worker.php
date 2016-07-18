<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Venturecraft\Revisionable\RevisionableTrait;
use Elasticquent\ElasticquentTrait;

class Worker extends Model
{
  use RevisionableTrait;
  use ElasticquentTrait;

  /**
   * @var array
   */
  protected $fillable = ['fio', 'position', 'department', 'subDepartment'];

  protected $indexSettings = [
    'analysis' => [
      'filter' => [
        'ngram_filter' => [
          'type' => 'ngram',
          'min_gram' => 2,
          'max_gram' => 30
        ]
      ],

      'analyzer' => [
        'index_ngram' => [
          'type' => 'custom',
          'tokenizer' => 'keyword',
          'filter' => ['lowercase', 'ngram_filter']
        ],
        'search_ngram' => [
          'type' => 'custom',
          'tokenizer' => 'keyword',
          'filter' => ['lowercase']
        ]
      ]
    ]
  ];

  /**
   * elastic search mapping
   * @var array
   */
  protected $mappingProperties = [
    'fio' => [
      'type' => 'string',
      'index' => 'not_analyzed'
    ],

    'search' => [
      'type' => 'string',
      'analyzer' => 'index_ngram',
      'search_analyzer' => 'search_ngram'
    ],

    'organization_id' => [
      'type' => 'long'
    ]
  ];

  /**
   * @return array
   */
  public function getIndexDocumentData()
  {
    return [
      'id' => $this->id,
      'fio' => $this->fio,
      'search' => $this->fio,
      'organization_id' => $this->organization_id
    ];
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function snapshot()
  {
    return $this->belongsTo('App\Snapshot');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function organization()
  {
    return $this->belongsTo('App\Organization');
  }

  /**
   * @return \Illuminate\Database\Eloquent\Relations\HasMany
   */
  public function notes()
  {
    return $this->hasMany('App\Note');
  }

  /**
   * @return Collection
   */
  public function publications()
  {
    return $this->notes()->where('type', 'publications');
  }

  /**
   * @return Collection
   */
  public function finance()
  {
    return $this->notes()->where('type', 'finance');
  }

  /**
   * @return Collection
   */
  public function realty()
  {
    return $this->notes()->where('type', 'realty');
  }

  /**
   * @return Collection
   */
  public function cars()
  {
    return $this->notes()->where('type', 'cars');
  }

  /**
   * @return Collection
   */
  public function law()
  {
    return $this->notes()->where('type', 'law');
  }

  /**
   * @return Collection
   */
  public function business()
  {
    return $this->notes()->where('type', 'business');
  }

  /**
   * Собираем работников в древовидную структуру
   * @param  Collection $workers
   * @return array
   */
  public static function structure($workers) {
    $result = [
      'main' => [
        'workers' => []
      ]
    ];

    foreach($workers as $worker) {
      if(empty($worker['department'])) {
        $result['main']['workers'][] = $worker;
      } else {
        if(empty($worker['subDepartment']))
          $result[$worker['department']]['workers'][] = $worker;
        else
          $result[$worker['department']]['sub'][$worker['subDepartment']][] = $worker;
      }
    }

    return $result;
  }
}
