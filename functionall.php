<?php
date_default_timezone_set("Asia/Taipei");
session_start();

class DB{

//宣告成員屬性
private $dsn="mysql:host=localhost;charset=utf8;dbname=dbq1";
private $root='root';
private $password='';
// private $table;
private $pdo;
public $table;
public $title;
public $button;
public $header;
public $append;
public $upload;




//建立建構式，在建構時帶入table名稱會建立資料庫的連線
public function __construct($table){
    $this->table=$table;
    $this->pdo=new PDO($this->dsn,$this->root,$this->password);
    $this->setStr($table);
}

private function setStr($table){
    switch($table){
        case "title":
            $this->title="網站標題管理";
            $this->button="新增網站標題圖片";
            $this->header="網站標題";
            $this->append="替代文字";
            $this->upload="網站標題圖片";
        break;
        case "ad":
           $this->title="動態文字廣告管理";
           $this->button="新增動態文字廣告";
           $this->header="動態文字廣告";
        break;
        case "mvim":
           $this->title="動畫圖片管理";
           $this->button="新增動畫圖片";
           $this->header="動畫圖片";
           $this->upload="動畫圖片";
        break;
        case "image":
           $this->title="校園映像資料管理";
           $this->button="新增校園映像圖片";
           $this->header="校園映像資料圖片";
           $this->upload="校園映像圖片";
        break;
        case "total":
           $this->title="進站總人數管理";
           $this->button="";
           $this->header="進站總人數:";
        break;
        case "bottom":
           $this->title="頁尾版權資料管理";
           $this->button="";
           $this->header="頁尾版權資料";
        break;
        case "news":
           $this->title="最新消息資料管理";
           $this->button="新增最新消息資料";
           $this->header="最新消息資料內容";
        break;
        case "admin":
           $this->title="管理者帳號管理";
           $this->button="新增管理者帳號";
           $this->header="帳號";
           $this->append="密碼";
        break;
        case "menu":
           $this->title="選單管理";
           $this->button="新增主選單";
           $this->header="主選單名稱";
           $this->append="選單連結網址";
        break;
    }
}

//此方法可能會有不帶參數，一個參數及二個參數的用法，因此使用不定參數的方式來宣告
public function all(...$arg){

    //在class中要引用內部的成員使用$this->成員名稱或方法
    //當參數數量不為1或2時，那麼此方法就只會執行選取全部資料這一句SQL語法
    $sql="select * from $this->table ";
    
    //依參數數量來決定進行的動作因此使用switch...case
    switch(count($arg)){
        case 1:
    
            //判斷參數是否為陣列
            if(is_array($arg[0])){
    
                //使用迴圈來建立條件語句的字串型式，並暫存在陣列中
                foreach($arg[0] as $key => $value){
    
                    $tmp[]="`$key`='$value'";
    
                }
    
                //使用implode()來轉換陣列為字串並和原本的$sql字串再結合
                $sql.=" WHERE ". implode(" AND " ,$tmp);
            }else{
                
                //如果參數不是陣列，那應該是SQL語句字串，因此直接接在原本的$sql字串之後即可
                $sql.=$arg[0];
            }
        break;
        case 2:
    
            //第一個參數必須為陣列，使用迴圈來建立條件語句的陣列
            foreach($arg[0] as $key => $value){
    
                $tmp[]="`$key`='$value'";
    
            }
    
            //將條件語句的陣列使用implode()來轉成字串，最後再接上第二個參數(必須為字串)
            $sql.=" WHERE ". implode(" AND " ,$tmp) ." ". $arg[1];
        break;
    
        //執行連線資料庫查詢並回傳sql語句執行的結果
        }
    
    
    //echo $sql;  //保留echo $sql 除錯時可用
    
    //fetchAll()加上常數參數FETCH_ASSOC是為了讓取回的資料陣列中只有欄位名稱
    return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    
    }

    public function math($math,$col,...$arg){

        $sql="SELECT $math($col) FROM $this->table ";
        
        //依參數數量來決定進行的動作因此使用switch...case
        switch(count($arg)){
            case 1:
        
                //判斷參數是否為陣列
                if(is_array($arg[0])){
        
                    //使用迴圈來建立條件語句的字串型式，並暫存在陣列中
                    foreach($arg[0] as $key => $value){
        
                        $tmp[]="`$key`='$value'";
        
                    }
        
                    //使用implode()來轉換陣列為字串並和原本的$sql字串再結合
                    $sql .= " WHERE ". implode(" AND " ,$tmp);
                }else{
                    
                    //如果參數不是陣列，那應該是SQL語句字串，因此直接接在原本的$sql字串之後即可
                    $sql .= $arg[0];
                }
            break;
            case 2:
        
                //第一個參數必須為陣列，使用迴圈來建立條件語句的陣列
                foreach($arg[0] as $key => $value){
        
                    $tmp[]="`$key`='$value'";
        
                }
        
                //將條件語句的陣列使用implode()來轉成字串，最後再接上第二個參數(必須為字串)
                $sql.=" WHERE ". implode(" AND " ,$tmp) ." ". $arg[1];
            break;
        
            //執行連線資料庫查詢並回傳sql語句執行的結果
            }
        
        
        //echo $sql;  //保留echo $sql 除錯時可用
        
        //fetchColumn()只會取回的指定欄位資料預設是查詢結果的第1欄位的值
        return $this->pdo->query($sql)->fetchColumn();
        
        }

        public function find($id){
            $sql="select * from $this->table where ";
                
                if(is_array($id)){
            
                    foreach($id as $key => $value){
            
                        $tmp[]="`$key`='$value'";
            
                    }
            
                    $sql .= implode(" AND ",$tmp);
            
                }else{
            
                    $sql .= " `id`='$id'";
            
                }
            
            // echo $sql;
            return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
            
            }

            public function del($id){

                $sql="delete from $this->table where ";
            
                if(is_array($id)){
            
                    foreach($id as $key => $value){
            
                        $tmp[]="`$key`='$value'";
            
                    }
            
                        $sql .= implode(" && ",$tmp);
            
                }else{
             
                    $sql .= " `id`='$id'";
            
                }
            
            //echo $sql;
            return $this->pdo->exec($sql);
            
            }

            public function save($array){

                //判斷資料陣列中是否有帶有 'id' 這個欄位，有則表示為既有資料的更新
                //沒有 'id' 這個欄位則表示為新增的資料
                if(isset($array['id'])){
                    //update
                    foreach($array as $key => $value){
            
                        if($key!='id'){
            
                            $tmp[]="`$key`='$value'";
            
                        }
                    }
            
                    //建立更新資料(update)的sql語法
                    $sql="update $this->table set ".implode(',',$tmp)." where `id`='{$array['id']}'";
            
                }else{
                    //insert
            
                    //建立新增資料(insert)的sql語法
                    $sql="insert into $this->table (`".implode("`,`",array_keys($array))."`) 
                                 values('".implode("','",$array)."')";
            
                    /* 覺得一行式寫法太複雜可以利用變數把語法拆成多行再組合
                     * $cols=implode("`,`",array_keys($array));
                     * $values=implode("','",$array);
                     * $sql="INSERT INTO $table (`$cols`) VALUES('$values')";        
                     */
            
                }
            //echo $sql;
            return $this->pdo->exec($sql);
            
            }

            
}


function to($url){

    header("location:".$url);

}
$Title = new DB('title');
$Ad = new DB('ad');
$Mvim = new DB('mvim');
$Image = new DB('image');
$News = new DB('news');
$Admin = new DB('admin');
$Menu = new DB('menu');
$Bottom = new DB('bottom');
$Total = new DB('total');


// $Total->save(['id'=>1,'total'=>$_POST['total']]);

if(!isset($_SESSION['total'])){
    $total = $Total->find(1);
    $total['total']++;
    $Total->save($total);
    $_SESSION['total'] = $total['total'];

}

$tt=$_GET['do']??'';  //取得網址參數do的值

switch($tt){   //利用網址參數來轉換$DB代表的資料表
    case "ad":
        $DB=$Ad;
    break;
    case "mvim":
        $DB=$Mvim;
    break;
    case "image":
        $DB=$Image;
    break;
    case "total":
        $DB=$Total;
    break;
    case "bottom":
        $DB=$Bottom;
    break;
    case "news":
        $DB=$News;
    break;
    case "admin":
        $DB=$Admin;
    break;
    case "menu":
        $DB=$Menu;
    break;
    default:
        $DB=$Title;
    break;
}


?>