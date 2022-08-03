<?php
include_once  "./functionall.php";

//判斷檔案上傳是否成功，如果成功就紀錄檔案的名稱
if(!empty($_FILES['img']['tmp_name'])){
    move_uploaded_file($_FILES['img']['tmp_name'],"../img/".$_FILES['img']['name']);
    $data['img']=$_FILES['img']['name'];
}else{

    //排除admin和menu這兩張沒有img欄位的資料表
    if($DB->table!='admin' && $DB->table!='menu'){

        //沒有檔案上傳時img欄位寫入空值
        $data['img']='';
    }
}

//針對欄位不同的資料表名稱各別處理
switch($DB->table){
    case "title":
        $data['text']=$_POST['text'];
        $data['sh']=0;
    break;
    case "admin":
        $data['acc']=$_POST['acc'];
        $data['pw']=$_POST['pw'];
    break;
    case "menu":
        $data['name']=$_POST['name'];
        $data['href']=$_POST['href'];
        $data['sh']=1;
        $data['parent']=0;
    break;
    default:  //欄位格式相同的資料表統一在default區段處理
        $data['text']=$_POST['text']??'';
        $data['sh']=1;
    break;
}

$DB->save($data);
to("../back.php?do=".$DB->table)
?>