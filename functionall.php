<?php
date_default_timezone_set("Asia/Taipei");
session_start();

class DB{

//宣告成員屬性
private $dsn="mysql:host=localhost;charset=utf8;dbname=dbq1";
private $root='root';
private $password='';
private $table;
private $pdo;



//建立建構式，在建構時帶入table名稱會建立資料庫的連線
public function __construct($table){
    $this->table=$table;
    $this->pdo=new PDO($this->dsn,$this->root,$this->password);
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
            
            //echo $sql;
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
$title = new DB('title');
$ad = new DB('ad');
$mvim = new DB('mvim');
$image = new DB('image');
$news = new DB('news');
$admin = new DB('admin');
$menu = new DB('menu');
$bottom = new DB('bottom');
$total = new DB('total');

$total->save(['id'=>1,'total'=>$_POST['total']]);

if(isset($_SESSION['total'])){
    $toTal = $total->find(1);
    $toTal['total']++;
    $toTal->save($total);
    $_SESSION['total'] = $toTal['total'];

}

?>