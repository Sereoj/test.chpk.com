<?php
include_once "test.php";
include_once "Project.php";

if(Project::OpenFile('D:\OpenServer\domains\test.chpk.com\4-kurs-fd2aqkdk.xlsx'))
{
    Project::SetValuesFromIds(Project::$rows);
}else{
    echo "false";
}

