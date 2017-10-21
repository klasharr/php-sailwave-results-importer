<?php

class NormaliseData
{

    public static function getCupRaceRow($row, SingleRaceCSVRowMapping $row_mapping){

        $row = str_replace('READ WILSON', 'READ-WILSON', $row);
        $row = preg_replace("/\"([a-z-]*)(,)\s*([a-z-]*)\"/i", "$1 $3", $row);

        $row = explode(',', $row);

        if(count($row) != $row_mapping->getColumnCount()){
            throw new Exception('Column mismatch');
        }


    }


    /**
     *
     * @param SeriesCSVRowMapping $row_mapping
     */
    public static function getSeriesRow($row, SeriesCSVRowMapping $row_mapping)
    {
        $row = str_replace('READ WILSON', 'READ-WILSON', $row);
        $row = str_replace('LLOYD JONES', 'LLOYD-JONES', $row);
        $row = str_replace('SINCLAIR TAYLOR', 'SINCLAIR-TAYLOR', $row);
        $row = preg_replace("/\"([a-z-]*)(,)\s*([a-z-]*)\"/i", "$1 $3", $row);

        $row = explode(',', $row);

        if(count($row) != $row_mapping->getColumnCount()){

            echo "<pre>";
            print_r($row);
            print_r($row_mapping);
            echo "</pre>";

            throw new Exception('Column mismatch');
        }

        // We don't need the last two columns
        array_pop($row);
        array_pop($row);

        $out = array();
        for ($i = 0; $i < 7; $i++) {
            $column_value = self::getSeriesColumnValue($i,$row[$i]);
            if(empty($column_value)) continue;
            $out[$row_mapping->getColumn($i)] = $column_value;
        }


        $races = array();
        // Race positions
        for( $i = 7; $i < 30; $i++){

            if(!isset($row[$i]))break;

            $current_value = preg_replace('/\s*/', '', $row[$i]);

            if(preg_match('/DNC/i', $current_value)){
                continue;
            }
            if(preg_match('/OOD/i', $current_value)){
                $races[$row_mapping->getColumn($i)] = 'OOD';
                continue;
            }

            if(preg_match('/DNS/i', $current_value)){
                $races[$row_mapping->getColumn($i)] = 'DNS';
                continue;
            }

            if(preg_match('/DNF/i', $current_value)){
                $races[$row_mapping->getColumn($i)] = 'DNF';
                continue;
            }

            if(preg_match('/DSQ/i', $current_value)){
                $races[$row_mapping->getColumn($i)] = 'DSQ';
                continue;
            }

            if(preg_match('/([0-9]+.{0,1}[0-9]{0,1})/', $current_value, $match)){
                $races[$row_mapping->getColumn($i)] = $match[0];
                continue;
            }

            if(preg_match('/^\s*$/', $current_value)){
                throw new Exception('Race result is empty');
            }

            echo 'Non parseable result "'.$current_value.'"';
        }
        $out['races'] = $races;

        return $out;
    }

    public static function getSeriesColumnValue($column_number, $data)
    {
        switch ($column_number) {
            case 0:
                if(!preg_match('/[0-9]{1,2}[st|th|td|nd|rd]*/', $data)){
                    throw new Exception('Rank is invalid, got: '. $data);
                }
                break;
            case 1:
                if (!in_array($data, array('MONO', 'DART 18', 'LASER', 'MIXED'))) {
                    throw new Exception('Column 1 expected MONO, DART, MIXED or LASER, got: ' . $data);
                }
                break;
            case 2:
                break;
            case 3:
                $tmp = (int) trim($data);
                if ($tmp != trim($data) || $tmp == 0) {
                    throw new Exception('At column 3 for sail num expected INT, got:' . $data);
                }
                break;
            case 4:
                if(empty(trim($data))){
                    throw new Exception('Helm was empty');
                }
                break;
            case 5:
                return empty(trim($data)) ? false : $data;
                break;
            case 6:
                $tmp = (int) trim($data);
                if ($tmp == 0) {
                    throw new Exception('At column 6 for PY expected INT, got:' . $data);
                }
                break;
        }
        return $data;
    }
}