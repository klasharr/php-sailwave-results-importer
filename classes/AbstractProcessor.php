<?php

class AbstractProcessor {

    /**
     * @var array
     */
    protected $raw_file_contents_as_array;

    /**
     * @var array
     */
    protected $data_to_insert;

    /**
     * @var string e.g. Thursday Spring Series or Coronation Cup
     */
    protected $race_name = 'Race ?';

    /**
     * @var string e.g. Series, Cup Race, Regatta
     */
    protected $race_type = 'Type ?';

    /**
     * Do dry run import or not. False will output insert statements only
     *
     * @var boolean
     */
    protected $dry_run = FALSE;

    /**
     * @var DB
     */
    protected $DB;


    protected $timestamp;

    protected $filename;

    public function __construct(DB $DB, $filename) {
        $this->DB = $DB;
        $this->timestamp = time();

        if(empty($filename)){
            throw new Exception('$filename is empty');
        }
        if(!file_exists($filename)){
            throw new Exception($filename . ' does not exist');
        }

        $this->filename = $filename;
    }

    public function setRaceName($str){
        $this->race_name = (string) $str;
    }

    public function setRaceType($str){
        $this->race_type = (string) $str;
    }

    public function setDryRun($bool){
        $this->dry_run = (bool) $bool;
    }

    public function execute(){
        $this->loadContentsFromCSV();
        $this->parseContents();
        $this->insertRowsToDB();
    }

}