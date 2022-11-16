<?php

class SearchParams
{
  private $query_params;
  private $offset;
  private $limit;

  private $extra_entry;

  private $sort_params;
  private $tables = array();

  /**
   * @var DistanceSearchParam
   */
  private $distance_params;

  private $distance = 2000;

  private $joined_tables = array();
  private $joined_field_tables = array();

  private $table;
  private $id_field_name = 'id';
  private $select_fields = ['*'];
  private $join_select_fields = [];
  private $sql;

  private $calc_found_rows = FALSE;

  public function __construct()
  {

  }

  public function addCriteria($criteria, $operator, $value)
  {
  }

  public function joined_field_models()
  {
    return array_map(function ($a) {
      $list = array_map(function ($b) {
        return ucfirst($b);
      }, explode('_', $a));

      return implode('', $list) . 'Model';
    }, $this->joined_field_tables);
  }

  public function addParam($param, $value, $index_name = '', $bonus_params = [])
  {
    if (preg_match('/^([^\.]+)\.[^.]+$/', $param, $matches)) {
      if (!in_array($matches[1], $this->tables) && !isset($this->joined_tables[$matches[1]])) {
        $this->tables[] = trim($matches[1]);
      }
    }

    $operator = '=';

    if (preg_match('/^.+<= *$/ims', $param)) {
      $operator = '<=';
    }

    if (preg_match('/^.+>= *$/ims', $param)) {
      $operator = '>=';
    }

    if (preg_match('/^.+> *$/ims', $param)) {
      $operator = '>';
    }

    if (preg_match('/^.+!= *$/ims', $param)) {
      $operator = '!=';
    }

    if (preg_match('/^.+ LIKE *$/ims', $param)) {
      $operator = 'LIKE';
    }

    if (preg_match('/^.+ OR *$/ims', $param)) {
      $operator = 'OR';
    }

    if (preg_match('/^.+ IN *$/ims', $param)) {
      $operator = 'IN';
    }

    if (preg_match('/^.+ AND *$/ims', $param)) {
      $operator = 'AND';
    }

    $param = preg_replace('/^[^A-Za-z\.0-9_]*([A-Za-z\.0-9_]+)[^A-Za-z\.0-9_]*$/ims', '$1', $param);
    $param = trim($param);

    $param = trim($param, 'LIKE');
    $param = trim($param, 'OR');
    $param = trim($param, 'AND');
    $param = trim($param, 'IN');

    if (!empty($bonus_params['w_mask'])) {
      switch ($bonus_params['w_mask']) {
        case 'both':
          $value = '%' . $value . '%';
          break;
        default:
          $value = $value . '%';
      }
      $operator = 'LIKE';
    }

    if ($index_name) {
      $this->query_params[$index_name] = array(
        'param' => $param,
        'operator' => $operator,
        'value' => $value,
      );
    } else {
      $this->query_params[] = array(
        'param' => $param,
        'operator' => $operator,
        'value' => $value,
      );
    }
  }

  public function addDistanceParam(GeoPoint $geo_point, $latitude_field, $longitude_field, $distance)
  {
    $distance_param = new DistanceSearchParam();
    $distance_param->distance = $distance;
    $distance_param->geo_point = $geo_point;
    $distance_param->latitude_field = $latitude_field;
    $distance_param->longitude_field = $longitude_field;


    $this->distance_params[] = $distance_param;
  }

  public function removeDistanceParams()
  {
    $this->distance_params = array();
  }

  public function setDistance($distance)
  {
    $this->distance = $distance;
  }

  public function removeParam($index_name)
  {
    unset($this->query_params[$index_name]);

    $flag = false;
    foreach ($this->query_params as $v) {
      if (!is_string($v)) {
        $flag = true;
      }
    }

    if (!$flag)
      $this->query_params = array();
  }

  public function setParamsList($params_list)
  {
    foreach ($params_list as $k => $v) {
      $this->addParam($k, $v);
    }
  }

  public function setIdFieldName($id_field_name)
  {
    $this->id_field_name = $id_field_name;
  }

  public function addSortParam($param, $order, $field_desc = '')
  {
    if (preg_match('/^([^\.]+)\.[^.]+$/', $param, $matches)) {
      if (!in_array($matches[1], $this->tables) && !isset($this->joined_tables[$matches[1]])) {
        $this->tables[] = trim($matches[1]);
      }
    }

    $this->sort_params[] = array(
      'param' => $param,
      'order' => $order,
      'field_desc' => $field_desc
    );
  }

  public function addJoinTableFields($table)
  {
    if (isset($this->joined_tables[$table]) and !in_array($table, $this->joined_field_tables)) {
      $this->joined_field_tables[] = $table;
      $this->join_select_fields[$table] = ['*'];
    }
  }

  public function addJoin($table, $join_field = NULL, $joined_field = NULL, $join_type = 'INNER JOIN')
  {
    if (preg_match('/^([A-Za-z0-9_]+) +([A-Za-z0-9_]+)$/ims', $table, $matches)) {
      $index = $matches[2];
    } else {
      $index = $table;
    }

    if (!isset($this->joined_tables[$index]))
      $this->joined_tables[$index] = array(
        'table' => $table,
        'join_field' => $join_field,
        'joined_field' => $joined_field,
        'join_type' => $join_type
      );
  }

  public function removeJoin($table)
  {
    unset($this->joined_tables[$table]);
  }

  public function setOffsetAndLimit($offset, $limit)
  {
    $this->offset = $offset;
    $this->limit = $limit;
  }

  public function setLimit($limit)
  {
    $limit_bak = $this->limit;
    $this->limit = $limit;
    return [$this->offset, $limit_bak];
  }

  public function removePaging()
  {
    $this->offset = 0;
    $this->limit = 0;
  }

  public function startBracket()
  {
    $this->query_params[] = 'start_bracket';
  }

  public function endBracket()
  {
    $this->query_params[] = 'end_bracket';
  }

  /**
   * Данный метод вызывается для того, чтобы в выборке получить на одну запись больше
   * (чтобы знать, есть следующая страница или нет)
   */
  public function setGetExtraEntry()
  {
    $this->extra_entry = TRUE;
  }

  public function setPagingParams($page, $by_page)
  {
    $this->offset = ($page - 1) * $by_page;
    $this->limit = $by_page;
  }

  public function buildQuery($table)
  {
    $this->table = $table;

    $this->buildSelectSection();
    $this->buildFromSection();
    $this->buildJoinSection();
    $this->buildWhereSection();
    $this->buildGroupSection();
    $this->buildOrderSection();

    if ($this->distance_params) {
      $this->sql = '  SELECT *
                FROM (' . $this->sql . ') a
                WHERE a.distance <= ' . (int)$this->distance . '
                ORDER BY a.distance ASC';
    }

    $this->buildLimitSection();


    return $this->sql;
  }


  public function calcFoundRows()
  {
    $this->calc_found_rows = TRUE;
  }

  private function buildSelectSection()
  {
    $this->sql = 'SELECT ';

    if ($this->calc_found_rows)
      $this->sql .= ' SQL_CALC_FOUND_ROWS ';


    if (count($this->joined_field_tables)) {
      foreach ($this->joined_field_tables as $table) {
        if(count($this->join_select_fields[$table])) {
          $this->sql .= ' `' . $table . '`.'.implode(', `' . $table . '`.', $this->join_select_fields[$table]).', ';
        }
      }

    }


    if(count($this->select_fields)) {
      $this->sql .= ' `' . $this->table . '`.'.implode(', `' . $this->table . '`.', $this->select_fields).', ';
    }

    if (count($this->distance_params) == 1) {
      $this->sql .= ', ';
      foreach ($this->distance_params as $distance_param) {
        $this->sql .= '(6372795 * 2 * asin(
              sqrt(
                pow(sin((' . $distance_param->latitude_field . ' - ' . $distance_param->geo_point->getLatitude() . ')*' . PI() . '/360),2) +
                  cos(' . $distance_param->latitude_field . '*' . PI() . '/180)*cos(' . $distance_param->geo_point->getLatitude() . '*' . PI() . '/180)*
                    pow(sin((' . $distance_param->longitude_field . ' - ' . $distance_param->geo_point->getLongitude() . ')*' . PI() . '/360),2)
              )
            )';
      }
      $this->sql .= ') distance,  ';
    } elseif (count($this->distance_params) > 1) {
      $this->sql .= ', LEAST(';
      foreach ($this->distance_params as $distance_param) {
        $this->sql .= '6372795 * 2 * asin(
              sqrt(
                pow(sin((' . $distance_param->latitude_field . ' - ' . $distance_param->geo_point->getLatitude() . ')*' . PI() . '/360),2) +
                  cos(' . $distance_param->latitude_field . '*' . PI() . '/180)*cos(' . $distance_param->geo_point->getLatitude() . '*' . PI() . '/180)*
                    pow(sin((' . $distance_param->longitude_field . ' - ' . $distance_param->geo_point->getLongitude() . ')*' . PI() . '/360),2)
              )
            ),';
      }
      $this->sql = trim($this->sql, ',');
      $this->sql .= ') distance,  ';
    }

      $this->sql = trim($this->sql);
      $this->sql = rtrim($this->sql,',');
  }

  private function buildFromSection()
  {
    $this->sql .= ' FROM `' . $this->table . '`';
  }

  private function buildJoinSection()
  {
    if (count($this->tables)) {
      foreach ($this->tables as $join_table_name) {
        $this->sql .= ' INNER JOIN `' . $join_table_name . '` ON `' . $join_table_name . '`.id = `' . $this->table . '`.' . $join_table_name . '_id';
      }
    }

    if (count($this->joined_tables)) {
      foreach ($this->joined_tables as $join_table_info) {
        if (preg_match('/^([A-Za-z0-9_]+) +([A-Za-z0-9_]+)$/ims', $join_table_info['table'], $matches)) {
          $table = '`' . $matches[1] . '` ' . $matches[2];
        } else {
          $table = '`' . $join_table_info['table'] . '`';
        }
        $this->sql .= ' ' . $join_table_info['join_type'] . ' ' . $table . ' ON ';

        if ($join_table_info['join_field'])
          $this->sql .= $join_table_info['join_field'];
        else
          $this->sql .= $table . '.' . $this->table . '_id ';

        $this->sql .= ' = ';

        if ($join_table_info['joined_field'])
          $this->sql .= $join_table_info['joined_field'];
        else
          $this->sql .= '`' . $this->table . '`.id';
      }
    }
  }

  private function buildWhereSection()
  {
    if ($this->query_params) {
      $this->sql .= ' WHERE ';
      $where_num = 1;

      $bracket_param_num = 0;
      foreach ($this->query_params as $query_param) {

        if (($where_num != 1) && ($bracket_param_num != 1)) {
          $this->sql .= ' AND ';
        }

        if ($query_param == 'start_bracket') {
          $this->sql .= '(';
          $bracket_param_num = 1;
          continue;
        }

        if ($query_param == 'end_bracket') {
          $this->sql = trim($this->sql, ' AND ');
          $this->sql = trim($this->sql, ' OR ');

          $this->sql .= ')';

          $this->sql = preg_replace('/(.+)\(\)/ims', '$1', $this->sql);

          $bracket_param_num = 0;
          continue;
        }

        $param = $query_param['param'];
        if (strpos($param, '.') === FALSE) {
          $param = '`' . $this->table . '`.' . $param;
        } else {
          $param = '' . $param . '';
        }

        if ($query_param['operator'] == 'OR') {
          if (is_array($query_param['value'])) {
            $str = '';
            foreach ($query_param['value'] as $val) {
              if ($val !== NULL) {
                $str .= $param . ' = "' . Register::get('db')->escape($val) . '" OR ';
              }
            }

            $str = trim($str, ' OR ');

            $this->sql .= ' (' . $str . ')';
          } else {
            $this->sql .= $param . ' = "' . Register::get('db')->escape($query_param['value']) . '" OR ';
          }
          $where_num++;
        } else {
          if (is_array($query_param['value'])) {
            $this->sql .= $param . ' ' . $query_param['operator'] . ' (' . join($query_param['value'], ',') . ') ';
          } else {
            if (($query_param['value'] !== FALSE) && ($query_param['value'] !== NULL)) {
              $this->sql .= $param . ' ' . $query_param['operator'] . ' "' . Register::get('db')->escape($query_param['value']) . '"';
            } else {
              $this->sql .= $param . ' IS NULL';
            }
          }


          $where_num++;

          if ($bracket_param_num)
            $bracket_param_num++;
        }
      }

      $this->sql = trim($this->sql, ' AND ');
      //$this->sql = preg_replace('/^(.*)(WHERE)?$/ims', '$1', $this->sql);
    }
  }

  private function buildGroupSection()
  {
    $this->sql .= ' GROUP BY `' . $this->table . '`.' . $this->id_field_name . ' ';
  }

  private function buildOrderSection()
  {
    if ($this->sort_params) {
      $sort_num = 1;
      foreach ($this->sort_params as $sort_param) {
        if ($sort_num == 1) {
          $this->sql .= ' ORDER BY ';
        } else {
          $this->sql .= ', ';
        }

        $param = $sort_param['param'];

        if (is_array($sort_param['order'])) {
          $order_string = 'FIELD (' . $param . '';

          foreach ($sort_param['order'] as $order_value) {
            $order_string .= ', ' . $order_value;
          }

          $order_string .= ') ' . $sort_param['field_desc'];

        } else {

          if ($param == 'RAND') {
            $order_string = 'RAND()';
          } else {
            if (strpos($param, '.') === FALSE) {
              $param = '`' . $this->table . '`.' . $param;
            } else {
              $tmp = preg_split('/\./', $param);
              $param = '`' . $tmp[0] . '`.`' . $tmp[1] . '`';
            }

            $order_string = $param . ' ' . $sort_param['order'];
          }
        }
        $this->sql .= $order_string;
        $sort_num++;
      }
    }
  }

  private function buildLimitSection()
  {
    if ($this->limit) {
      $limit = $this->limit;
      if ($this->extra_entry)
        $limit++;

      $this->sql .= ' LIMIT ' . $this->offset . ', ' . $limit;
    }
  }

  public function setSelectFields($select_fields)
  {
    $select_fields_bak = $this->select_fields;
    $this->select_fields = $select_fields;
    return $select_fields_bak;
  }


  public function setJoinSelectFields($select_fields)
  {
    $select_fields_bak = $this->join_select_fields;
    $this->join_select_fields = $select_fields;
    return $select_fields_bak;
  }

}