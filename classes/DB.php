<?php

class DB{

    /**
     * @var object
     */
    private $conn;

    /**
     * @param $prepped_data_for_db
     * @param $timestamp
     * @throws Exception
     */
    public function insert($prepped_data_for_db, $timestamp, $dry_run){

        $this->getDBConnection();

        foreach($prepped_data_for_db as $row) {

            $sql = sprintf( "INSERT INTO `results` (`race_name`, `race_type`, `date`, `month`, `day`, `fleet`, `boat`, `sail`, `PY`, `sailor`, `role`, `place`, `timestamp`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', %d, %d, '%s', '%s', '%s', %d)",
                $row['race_name'],
                $row['race_type'],
                $row['date'],
                $row['month'],
                $row['day'],
                $row['fleet'],
                $row['boat'],
                $row['sail'],
                $row['PY'],
                $row['sailor'],
                $row['role'],
                $row['place'],
                $timestamp);

            if ($dry_run) {
                echo sprintf("%s\n",$sql);
                continue;
            }


            if ($this->conn->query($sql) === TRUE) {
                echo sprintf("Success %s %s %s %s", $row['race_name'], $row['date'], $row['sailor'], '<br/>');
            } else {
                if(preg_match('/Duplicate/i', $this->conn->error)){
                    echo sprintf("Duplicate: ignoring %s %s %s %s", $row['race_name'], $row['date'], $row['sailor'], '<br/>');
                } else {
                    throw new Exception("Error: " . $sql . ' ' . $this->conn->error);
                }
            }
        }

        $this->closeDBConnection();
    }

    private function getDBConnection(){

        $servername = "localhost";
        $username = "root";
        $password = "a";
        $dbname = "ssc_results";

        $this->conn = new mysqli($servername, $username, $password, $dbname);
        if ($this->conn->connect_error) {
            throw new Exception('Could not connect to DB. Error: '. $conn->connect_error);
        }
    }

    public function closeDBConnection(){
        if($this->conn){
            if(!$this->conn->close()){
                Throw new Exception('Could not close DB connection');
            }
        } else {
            Throw new Exception('$conn not set, there is nothing to close');
        }
    }


    public function getListOfSailCounts(){
        $this->getDBConnection();

        if ($o = $this->conn->query("SELECT distinct sailor, count(*) as sailed from results group by sailor order by sailed desc;")){
           


        }


        $this->closeDBConnection();
    }
}