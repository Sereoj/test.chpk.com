<?php
include_once "test.php";
include_once "Project.php";

if(Project::OpenFile('D:\OpenServer\domains\test.chpk.com\4-kurs.xlsx'))
{
    Project::SetValuesFromIds(Project::getRows());

//    $get_week = Project::getWeeks();
//    print_r($get_week);
//    $get_times = Project::getTimes();
//    print_r($get_times);

    $test = Project::GetValuesFromGroup();
    print_r($test);
}else{
    echo "false";
}

