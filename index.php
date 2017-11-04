<?php

define('RACE_FILES_DIR', '2017'); // 2017/Cupraces

// False means see ouput messages, but no DB change
define('DRY_RUN', false);

// DB credentials
define('DB_NAME', '');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
define('DB_HOST', '');

include('classes/AbstractProcessor.php');
include('classes/SeriesProcessor.php');
include('classes/SingleRaceProcessor.php');
include('classes/DB.php');
include('classes/NormaliseData.php');
include('classes/SeriesCSVRowMapping.php');
include('classes/SingleRaceCSVRowMapping.php');

try{

    $DB = new DB(
        DB_NAME,
        DB_USERNAME,
        DB_PASSWORD,
        DB_HOST
    );


    $files = array(
        'sunday_autumn.csv',
        'sunday_holiday.csv',
        'sunday_spring.csv',
        'sunday_summer.csv',
        'thursday_spring.csv',
        'thursday_summer.csv',
        'thursday_twilight.csv'
    );

   /*
    $files = array(
        '1974_Cup_2017.csv',
        'knoll_cup_2017.csv',
        'ladies_cup_2017.csv',
        'bent_cup_2017.csv',
        'macdona_cup_2017.csv',
        'chellingworth_cup_2017.csv',
        'commodores_cup_2017.csv',
        'owerdale_cup_2017.csv',
        'coronation_cup_2017.csv',
        'rees_cup_2017.csv',
        'elisabeth_cup_2017.csv',
        'rnli_pennant_2017.csv',
        'fleming_trophy_2017.csv',
        'the_opener_2017.csv',
        'fun_race_2017.csv',
        'vicky_thornhill_trophy_2017.csv',
        'james_day_cup_2017.csv',
        'wessex_shield_2017.csv',
    );
*/

    foreach($files as $file){

        $o = new SeriesProcessor($DB, RACE_FILES_DIR . '/' . $file); //.'/cup_races/'

        $o->setRaceName(ucwords(str_replace(array('_', '.csv'), array(' ',''), $file)));
        $o->setRaceType('Cup Race');
        $o->setDryRun(DRY_RUN);
        $o->execute();

    }

} catch( Exception $e){
    echo $e->getMessage();
}