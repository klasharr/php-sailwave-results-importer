<?php


class SeriesProcessor extends AbstractProcessor {

    /**
     * @var array
     */
    private $header_row;

    private $series;

    public function __construct(DB $DB, $filename){
        parent::__construct($DB, $filename);
        $this->series = ucwords(str_replace(array(RACE_FILES_DIR.'/', '_', '.csv'), array('', ' ', ''), $filename));
    }


    public function loadContentsFromCSV(){

        if(!$this->raw_file_contents_as_array = file($this->filename)){
            throw new Exception('ERROR: trying to grab '. $this->filename);
        }
    }

    public function getRawContents(){
        return $this->raw_file_contents_as_array;
    }

    public function parseContents(){

        $row_mapping = new SeriesCSVRowMapping($this->raw_file_contents_as_array[0]);

        // remove header row
        $o = array_shift($this->raw_file_contents_as_array);

        $data = array();
        foreach($this->raw_file_contents_as_array as $i => $row){
            try{
                $data[] = NormaliseData::getSeriesRow($row, $row_mapping);
            } catch( Exception $e){
                throw new Exception( $this->filename . ' ' . $e->getMessage() . "<pre>" . print_r( $row, 1) . "</pre>" );
            }
        }

        $this->data_to_insert = array('series' => $this->series, 'results' => $data);
        return $this->data_to_insert;
    }

    /**
     ** @param array $results
     */
    private function prepareDBImportRows(array $results){

        $out = array();

        foreach($results as $row){

            $data = array();
            $data['race_name'] = addslashes($this->race_name);
            $data['fleet'] = ($row['Fleet']);
            $data['boat'] = addslashes($row['Class']) ;
            $data['sail'] = (int) $row['Sail No'];
            $data['PY'] = (int) $row['PY'];
            $data['race_type'] = addslashes($this->race_type);
            $data['timestamp'] = $this->timestamp;

            $sailors = array('Helm');
            if(!empty($row['Crew']) && strlen($row['Crew']) > 5){
                $sailors[] = 'Crew';
            }

            // Duplicate the row for Helm and Crew if there was a Crew
            foreach($sailors as $sailor_role){

                $data['sailor'] = addslashes($row[$sailor_role]);
                $data['role'] = $sailor_role;

                // Separate records for every date sailed in the series.
                foreach($row['races'] as $date => $place){
                    $data['date'] = DateTime::createFromFormat('d/m/Y', $date)->format('Y-m-d');
                    $data['month'] = DateTime::createFromFormat('d/m/Y', $date)->format('M'); // Oct
                    $data['day'] = DateTime::createFromFormat('d/m/Y', $date)->format('D'); // Fri
                    $data['place'] = $place;
                    /*
                     * $copy = $data;
                    unset($copy['timestamp']);
                    $hash = md5(serialize($copy));

                    $data['hash'] = $hash;
                    */
                    $out[] = $data;
                }
            }
        }
        return $out;
    }



    /**
     * @param $dry_run int
     */
    public function insertRowsToDB(){

        $series = $this->data_to_insert['series'];
        if(!$series){
            throw new Exception('$series is not set');
        }

        $results = $this->data_to_insert['results'];
        if(!$results || !is_array($results)){
            throw new Exception('$results is not set or is empty');
        }

        $this->DB->insert(
            $this->prepareDBImportRows($results), $this->timestamp, $this->dry_run
        );
    }

}