<?php

    class Multiple_joinType extends Type
    {

        /* not valid */
        public function getFormValue()
        {
            $db = Register::get('db');

            $link = $this->fieldInfo['link'];
            $foreign = $this->fieldInfo['foreign'];

            $fieldName = $this->fieldName;
            $foreign['join_name'] = 't2.' . $foreign['join_name'];
            $sql = 'SELECT t1.*,' . $foreign['join_name'] . ' as name2 FROM ' . $foreign['table'] . ' t1 left join ' . $foreign['join_table'] . ' t2 on (t1.' . $foreign['cross_fk'] . '=t2.' . $foreign['join_index'] . ')';
            if (!empty($foreign['cond']))
                $sql .= ' WHERE t1.' . $foreign['cond'];
            if (!empty($foreign['order']))
                $sql .= ' ORDER BY t1.' . $foreign['order'] . '';
            $foreignData = $db->query($sql);

            $selectedValues = array();
            $indexValue = $this->indexValue;
            if (!empty($indexValue)) {
                $sql = 'SELECT * FROM ' . $link['table'] . ' WHERE `' . $link['source_id'] . '`=\'' . $indexValue . '\'';
                $linkData = $db->query($sql);

                foreach ($linkData as $value)
                    $selectedValues[] = $value[$link['foreign_id']];
            }

            $result = '<input type="hidden" name="form[' . $fieldName . ']" value="1">';
            $result .= '<table>';
            $result .= '<tbody>';
            for ($i = 0; $i < count($foreignData); $i += 3) {
                $value = $foreignData[$i];
                $selected = (in_array($value[$foreign['id']], $selectedValues)) ? 'checked' : '';

                $result .= '<tr>';

                $result .= '<td style="padding:5px;"><label><input type="checkbox" name="form[' . $fieldName . '][]" ' . $selected . ' value="' . $value[$foreign['id']] . '" /></label></td><td style="padding:5px;">' . $value[$foreign['name']] . ' ' . $this->_getTooltip($foreign['tooltip'], $value);
                ;
                if ($value['name2']) {
                    $result .= '(' . $value['name2'] . ')';
                }
                $result .= '</td>';
                $result .= '</tr>';

                $result .= '<tr>';
                if (!empty($foreignData[$i + 1])) {
                    $value = $foreignData[$i + 1];
                    $selected = (in_array($value[$foreign['id']], $selectedValues)) ? 'checked' : '';
                    $result .= '<td style="padding:5px;"><label><input type="checkbox" name="form[' . $fieldName . '][]" ' . $selected . ' value="' . $value[$foreign['id']] . '" /></label></td><td style="padding:5px;">' . $value[$foreign['name']] . ' ' . $this->_getTooltip($foreign['tooltip'], $value);
                    if ($value['name2']) {
                        $result .= ' (' . $value['name2'] . ')';
                    }
                    $result .= '</td>';
                } else {
                    $result .= '<td></td>';
                }
                $result .= '</tr>';

                $result .= '<tr>';
                if (!empty($foreignData[$i + 2])) {
                    $value = $foreignData[$i + 2];
                    $selected = (in_array($value[$foreign['id']], $selectedValues)) ? 'checked' : '';
                    $result .= '<td style="padding:5px;"><label><input type="checkbox" name="form[' . $fieldName . '][]" ' . $selected . ' value="' . $value[$foreign['id']] . '" /></label></td><td style="padding:5px;">' . $value[$foreign['name']] . ' ' . $this->_getTooltip($foreign['tooltip'], $value);
                    if ($value['name2']) {
                        $result .= ' (' . $value['name2'] . ')';
                    }
                    $result .= '</td>';
                } else {
                    $result .= '<td></td>';
                }
                $result .= '</tr>';
            }
            $result .= '</tbody>';
            $result .= '</table>';


            return $result;
        }

        function _getTooltip($foreignTooltip, $value)
        {
            if (!empty($foreignTooltip)) {
                $tooltip = '';
                if ($foreignTooltip) {
                    foreach ($foreignTooltip as $k=> $v) {
                        $tooltip .= $value[$v] . ' ';
                    }
                }
                return $tooltip;
            } else {
                return '';
            }
        }

        function getSaveValue($values)
        {
            $db = Register::get('db');
            $link = $this->fieldInfo['link'];
            if (!empty($this->indexValue)) {
                $sql = 'DELETE FROM ' . $link['table'] . ' WHERE `' . $link['source_id'] . '`=\'' . $this->indexValue . '\'';
                $db->query($sql);
            } else {
                $this->indexValue = $db->getAutoIncrement($this->table);
            }
            if (is_array($values)) {
                foreach ($values as $val) {
                    $sql = 'INSERT INTO ' . $link['table'] . ' (`' . $link['source_id'] . '`, `' . $link['foreign_id'] . '`)
					VALUES (\'' . $this->indexValue . '\', \'' . $db->escape($val) . '\')';
                    $db->query($sql);
                }
            }
            return Type::NOT_SET;
        }

        function getViewValue()
        {
            $db = Register::get('db');
            $link = $this->fieldInfo['link'];
            $foreign = $this->fieldInfo['foreign'];
            $fieldName = $this->fieldName;

            $indexValue = $this->indexValue;

            $sql = 'SELECT t1.*,t2.' . $foreign['join_name'] . ' as name2 FROM ' . $foreign['table'] . ' t1 left join ' . $foreign['join_table'] . ' t2 on (t1.' . $foreign['cross_fk'] . '=t2.' . $foreign['join_index'] . ')';

            $sql = 'SELECT *,t2.' . $foreign['join_name'] . ' as name2 FROM ' . $foreign['table'] . ' as `f`';
            $sql .= ' LEFT JOIN ' . $foreign['join_table'] . ' t2 ON (f.' . $foreign['cross_fk'] . '=t2.' . $foreign['join_index'] . ')';
            $sql .= 'LEFT JOIN ' . $link['table'] . ' as `l` ON f.`' . $foreign['id'] . '`=l.`' . $link['foreign_id'] . '`';
            $sql .= ' WHERE l.`' . $link['source_id'] . '`=\'' . $this->indexValue . '\'';

            if (!empty($foreign['order']))
                $sql .= ' ORDER BY f.' . $foreign['order'] . '';

            $foreignData = $db->query($sql);

            $printData = array();
            foreach ($foreignData as $item)
                if ($item['name2']) {
                    $printData[] = $item[$foreign['name']] . ' (' . $item['name2'] . ')';
                } else {
                    $printData[] = $item[$foreign['name']] . ' ';
                }

            $result = join(', ', $printData);
            return $result;
        }

    }