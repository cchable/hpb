<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      
 * @copyright 
 * @license   
 */

namespace Hpb\Db\Sql;

use Zend\Db\Sql\Select;


/**
 *
 * @property Where $where
 * @property Having $having
 */
class FBSelect extends Select
{
  /**#@+
   * Constant
   * @const
   */
  const FBLIMIT  = 'rows';
  const FBOFFSET = 'to';
  
  /**
   * @va                     array Specifications
   */
  protected $specifications = [
    'statementStart' => '%1$s',
    self::SELECT => [
        'SELECT %1$s FROM %2$s' => [
            [1 => '%1$s', 2 => '%1$s AS %2$s', 'combinedby' => ', '],
            null
        ],
        'SELECT %1$s %2$s FROM %3$s' => [
            null,
            [1 => '%1$s', 2 => '%1$s AS %2$s', 'combinedby' => ', '],
            null
        ],
        'SELECT %1$s' => [
            [1 => '%1$s', 2 => '%1$s AS %2$s', 'combinedby' => ', '],
        ],
    ],
    self::JOINS  => [
        '%1$s' => [
            [3 => '%1$s JOIN %2$s ON %3$s', 'combinedby' => ' ']
        ]
    ],
    self::WHERE  => 'WHERE %1$s',
    self::GROUP  => [
        'GROUP BY %1$s' => [
            [1 => '%1$s', 'combinedby' => ', ']
        ]
    ],
    self::HAVING => 'HAVING %1$s',
    self::ORDER  => [
        'ORDER BY %1$s' => [
            [1 => '%1$s', 2 => '%1$s %2$s', 'combinedby' => ', ']
        ]
    ],
    self::LIMIT  => 'ROWS %1$s',
    self::OFFSET => 'TO %1$s',
    'statementEnd' => '%1$s',
    self::COMBINE => '%1$s ( %2$s )',
  ];    
  
  /**
   * Constructor
   *
   * @param  null|string|array|TableIdentifier $table
   */
  public function __construct($table = null)
  {
    parent::__construct($table);
  }

    /**
   * @param int $offset
   * @return self Provides a fluent interface
   * @throws Exception\InvalidArgumentException
   */
  public function offset($offset)
  {
    if (! is_numeric($offset)) {
      throw new Exception\InvalidArgumentException(sprintf(
        '%s expects parameter to be numeric, "%s" given',
        __METHOD__,
        (is_object($offset) ? get_class($offset) : gettype($offset))
      ));
    }

    if (!$this->limit) {
      $this->limit = $offset + 1;
      if ($offset) $this->offset = $offset;
    }
    
    return $this;
  }
  
  /**
   * @param int $limit
   * @return self Provides a fluent interface
   * @throws Exception\InvalidArgumentException
   */
  public function limit($limit)
  {
    if (! is_numeric($limit)) {
      throw new Exception\InvalidArgumentException(sprintf(
        '%s expects parameter to be numeric, "%s" given',
        __METHOD__,
        (is_object($limit) ? get_class($limit) : gettype($limit))
      ));
    }
    
    if (!$this->offset) {
      $this->limit = $limit;
    } else {
      $this->offset += $limit;
    }
    
    return $this;
  }         
}
