<?php

/**
 * Class RowMapping
 *
 * Turn CSV row into assoc array
 *
 */
class SingleRaceCSVRowMapping{

    /**
     * The columns in this CSV file
     * @var
     */
    private $columns;

    /**
     * @param $csv_row string
     */
    public function __construct($csv_row)
    {
        $header_cols = explode(',', $csv_row);

        foreach ($header_cols as $col) {
            if (in_array(trim($col), array('Rank', 'Fleet', 'Class', 'Sail No', 'Helm',	'Crew', 'PY', 'Elapsed',
                'Laps', 'Corrected', 'Points', 'Date')
            )) {
                $this->columns[] = $col;
            } elseif ( empty($col) || strlen($col) < 3) {
                continue;
            } else {
                throw new Exception('ERROR: "' . $col . '" could not be parsed, invalid column header');
            }
        }
        
        return $this->columns;
    }

    public function getColumn($col){
        return $this->columns[$col];
    }

    public function getColumnCount(){
        return count($this->columns);
    }
    
}