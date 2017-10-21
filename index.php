<?php

define('RACE_FILES_DIR', 'example_results');
define('DRY_RUN', false);


include('classes/AbstractProcessor.php');
include('classes/SeriesProcessor.php');
include('classes/SingleRaceProcessor.php');
include('classes/DB.php');
include('classes/NormaliseData.php');
include('classes/SeriesCSVRowMapping.php');
include('classes/SingleRaceCSVRowMapping.php');

try{

    $files = array(
        'sunday_autumn.csv',
        'sunday_holiday.csv',
        'sunday_spring.csv',
        'sunday_summer.csv',
        'thursday_spring.csv',
        'thursday_summer.csv',
        'thursday_twilight.csv'
    );


     $files = array(
        '1974_cup.csv',
        'chellingworth_cup.csv',
        'commodores_cup.csv',
        'elizabeth_cup.csv',
        'fleming_trophy.csv',
        'james_day_cup.csv',
        'knoll_cup.csv',
        'rees_cup.csv',
        'owerdale_cup.csv',
        'rnli_pennant.csv',
        'the_opener.csv',
        'vicky_thornhill_trophy.csv',
        'wessex_shield.csv',
        'coronation_cup.csv',
    );

    foreach($files as $file){

        //
        $o = new SeriesProcessor(new DB, RACE_FILES_DIR.'/cup_races/'.$file);

        $o->setRaceName(ucwords(str_replace(array('_', '.csv'), array(' ',''), $file)));
        
        // Needs to be edited for runs, Series Race or Cup Race
        $o->setRaceType('Cup Race');
        $o->setDryRun(DRY_RUN);
        $o->execute();

    }

} catch( Exception $e){
    echo $e->getMessage();
}