<?php
include_once "test.php";
include_once "Project.php";

if(Project::OpenFile('D:\OpenServer\domains\test.chpk.com\4-kurs-fd2aqkdk.xlsx'))
{
        Project::SetValuesFromIds(Project::getRows());

//    $get_week = Project::getWeeks();
//    print_r($get_week);
//    $get_times = Project::getTimes();
//    print_r($get_times);

//    $get_groups = Project::getGroups();
//    print_r($get_groups);

    $get_subgroups = Project::getSubgroups();
    print_r($get_subgroups);

//    print_r(Project::getRows()[9][7]);

//    $test = Project::GetValuesFromGroup('ИС-1-18');
//    print_r($test);
}else{
    echo "false";
}

