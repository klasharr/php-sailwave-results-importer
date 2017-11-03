<?php

/**
 * Class RowMapping
 *
 * Turn CSV row into assoc array
 *
 */
class SeriesCSVRowMapping{

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
            if(empty(trim($col))){
                throw new Exception('ERROR: detected an empty column, check for a "," at the end of the line. Columns are: '. $csv_row);
            }
            
            if (in_array(trim($col), array('Rank','Fleet', 'Class', 'Sail No', 'Helm', 'Crew', 'PY', 'Total', 'Nett')
            )) {
                $this->columns[] = $col;
            } else if (preg_match('/([0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4})/', $col, $matches)) {
                $this->columns[] = $matches[1];
            } else {
                throw new Exception('ERROR: "' . $col . '" could not be parsed, invalid column header. Columns are: '. $csv_row);
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