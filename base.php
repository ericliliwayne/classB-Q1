<?php
    date_default_timezone_set("Asia/Taipei");
    session_start();

    class DB{
        protected $dsn = "mysql:host=localhost;charset=utf8;dbname=dbq1";
        protected $pdo = 'root';
        protected $pw = '';
        protected $table;
        public function __construct($table)
        {
            $this->table=$table; //將變數(資料表名稱-table)指定到這個class的table
            $this->pdo=new PDO($this->dsn,$this->pdo,$this->pw);
        }

        public function all(...$arg){ //查詢指定資料表之全部或指定條件之所有資料
            $sql = "SELECT * FROM $this->table ";
            if(isset($arg[0])){ //若變數為一個時，
                if(is_array($arg[0])){ //先判定變數是否為陣列
                    foreach($arg[0] as $key => $value){ //是陣列就用foreach一個一個取出來存到空的陣列裡
                        $tmp[]="`$key`='$value'";
                    }
                    $sql .= " WHERE ".join(" AND ",$tmp); //再用where加上join函式把字串整合在一起放到sql語句後方
                }else{
                    $sql .=$arg[0]; //若arg為字串時直接串接在sql語句後
                }
            }

            if(isset($arg[1])){ //若為2個變數時，
                $sql .=$arg[1]; //若第2個變數為字串時也是直接串接在sql語句後，若為陣列則另外做處理
            }
            // echo $sql; //debug用途
            return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }

        public function find($id){ //取一筆指定資料表之資料
            $sql = "SELECT * FROM $this->table ";
            
                if(is_array($id)){ //先判定變數是否為陣列
                    foreach($id as $key => $value){ //id是陣列就用foreach一個一個取出來存到空的陣列裡
                        $tmp[]="`$key`='$value'";
                    }
                    $sql .= " WHERE ".join(" AND ",$tmp); //再用where加上join函式把字串整合在一起放到sql語句後方
                }else{
                    $sql .= " WHERE `id`='$id' "; //若id為數字時直接串接在sql語句裡
                }
        
            // echo $sql; //debug用途
            return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        }

        public function del($id){ //刪除一筆指定資料表之資料
            $sql = "DELETE FROM $this->table ";
            
                if(is_array($id)){ //先判定變數是否為陣列
                    foreach($id as $key => $value){ //id是陣列就用foreach一個一個取出來存到空的陣列裡
                        $tmp[]="`$key`='$value'";
                    }
                    $sql .= " WHERE ".join(" AND ",$tmp); //再用where加上join函式把字串整合在一起放到sql語句後方
                }else{
                    $sql .= " WHERE `id`='$id' "; //若id為數字時直接串接在sql語句裡
                }
        
            // echo $sql; //debug用途
            return $this->pdo->exec($sql);
        }

        public function save($array){ //儲存(更新/新增)一筆或多筆指定資料表之資料
            if(isset($array['id'])){ //若陣列帶有id的值代表此陣列是由資料表取出來的
                //所以代表是執行「更新」的動作
                foreach($array as $key => $value){ //id是陣列就用foreach一個一個取出來存到空的陣列裡
                    if($key != 'id'){
                        $tmp[]="`$key`='$value'";
                    }
                }
                $sql = "UPDATE $this->table FROM " . join(" , ",$tmp)." WHERE `id`='{$array['id']}'";
            }else{
                //沒帶id的陣列就代表執行「新增」的動作
                $sql = "INSERT INTO $this->table (`".join("`,`",array_keys($array))."`) VALUES('".join("','",$array)."') ";
            }
        
            // echo $sql; //debug用途
            return $this->pdo->exec($sql);
        }

        public function math($math,$col,...$arg){ //計算指定資料表($math)之全部或指定條件之單一數值(計算結果),$col=>計算方式(加總/平均/...)
            $sql = "SELECT $math($col) FROM $this->table ";
            if(isset($arg[0])){ //若變數為一個時，
                if(is_array($arg[0])){ //先判定變數是否為陣列
                    foreach($arg[0] as $key => $value){ //是陣列就用foreach一個一個取出來存到空的陣列裡
                        $tmp[]="`$key`='$value'";
                    }
                    $sql .= " WHERE ".join(" AND ",$tmp); //再用where加上join函式把字串整合在一起放到sql語句後方
                }else{
                    $sql .=$arg[0]; //若arg為字串時直接串接在sql語句後
                }
            }

            if(isset($arg[1])){ //若為2個變數時，
                $sql .=$arg[1]; //若第2個變數為字串時也是直接串接在sql語句後，若為陣列則另外做處理
            }
            // echo $sql; //debug用途
            return $this->pdo->query($sql)->fetchColumn();
        }

        public function q($sql){ //非上面能使用的方法則用此函式
            // echo $sql; //debug用途
            return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    function to($url){ //導向網頁功能
        header("lication:".$url);
    }

    function dd($array){ //輸出陣列來debug
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    $Bottom=new DB('bottom');
    
?>