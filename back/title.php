<?php
include_once "../api/add.php";
?>
<div style="width:99%; height:87%; margin:auto; overflow:auto; border:#666 1px solid;">
    <p class="t cent botli"><?=$DB->title;?></p>
    <form method="post" action="../api/edit_info.php">
        <table width="100%">
            <tbody>
                <tr class="yel">
                    <td width="45%"><?=$DB->header;?></td>
                    <td width="23%"><?=$DB->append;?></td>
                    <td width="7%">顯示</td>
                    <td width="7%">刪除</td>
                    <td></td>
                </tr>
                <?php
                $rows=$DB->all();
                foreach($rows as $row){
                $checked=($row['sh']==1)?'checked':'';
                ?>
                <tr>
    <td width="45%">
        <!--依題意，標題圖片以300x30來顯示，使用行內樣式直接設定-->
        <img src="./img/<?=$row['img'];?>" style="width:300px;height:30px">
    </td>
    <td width="23%">
        <!--因為編輯資料會以多筆的方式送出，
        所以在name的屬性上使用陣列的方式，確保每筆資料都能被傳送出去-->
        <input type="text" name="text[]" value="<?=$row['text'];?>">
    </td>
    <td width="7%">
        <!--因為radio預設是單選，只會有一個值傳送出去，所以name的屬性不用加陣列；
            這邊要注意的是，我們要呈現資料被選取的狀態，
            所以在input的屬性中加上在迴圈一開始時設定的\$checked，如果sh值為1，
            則會在標籤中顯示checked，radio會呈現選中的狀態，如果sh值為0，
            則會在標籤中顯示空值，radio會呈現未選中的狀態-->
        <input type="radio" name="sh" value="<?=$row['id'];?>" $checked;>
    </td>
    <td width="7%">
        <!--刪除的功能因為是多選，所以以陣列的方式來收集資料，在value中填入id值，
            則會以陣列的方式把被勾選要刪除的資料id傳送出去-->
        <input type="checkbox" name="del[]" value="<?=$row['id'];?>">
    </td>
    <td>
        <!--我們放一個隱藏欄位id，用來對應text欄位，
            這樣才能知道每一個文字欄位各是那個id的資料-->
        <input type="hidden" name="id[]" value="<?=$row['id'];?>">
        <!--更新圖片的功能會使用到modal彈出視窗，所以要指定一個檔案來載入更新時的表單畫面；
            但我們也需要讓表單知道要更新的是那一筆資料的圖片，因此要再加上一個參數id，
            把id資料帶去表單畫面使用-->
        <input type="button" onclick="op('#cover','#cvr','modal/upload_title.php?id=<?=$row['id'];?>')" value="更新圖片">
    </td>
</tr>
<?php
} 
?>
            </tbody>
        </table>
        <table style="margin-top:40px; width:70%;">
            <tbody>
                <tr>
                    <td width="200px"><input type="button" onclick="op('#cover','#cvr','modal/upload_title.php')"
                            value="<?=$DB->button;?>"></td>
                    <td class="cent"><input type="submit" value="修改確定"><input type="reset" value="重置"></td>
                </tr>
            </tbody>
        </table>

    </form>
</div>
