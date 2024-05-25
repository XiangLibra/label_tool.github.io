<?php
include 'config.php';

$dbname = "parking_space";
// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢測連接
if ($conn) {
    echo "ok" . "<br>";
} else {
    echo "error";
}

// 檢查資料夾是否存在，不存在則創建
// $xmlFolder = 'xml_file';
$xmlFolder = 'D:\xiang\下載\xml_file';
if (!file_exists($xmlFolder)) {
    mkdir($xmlFolder, 0777, true);
    echo "資料夾已成功創建: $xmlFolder" . "<br>";
}

$sql = "SELECT * FROM park_xml";
$result = $conn->query($sql);

// 檢測查詢結果
if ($result) {
    // 使用fetch_assoc()函數
    while ($row = $result->fetch_assoc()) {
        // 使用pathinfo函數取得檔案資訊
        $fileInfo = pathinfo($row['file_name']);

        // 創建 XML 對象
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><annotation></annotation>');

        // 添加 size 元素
        $size = $xml->addChild('size');
        $size->addChild('width', '1920');
        $size->addChild('height', '1080');
        $size->addChild('depth', '3');

        // 創建 object 元素
        $object = $xml->addChild('object');
        $object->addChild('name', '1');  // 使用資料表的 1 作為 name

        // 創建 bndbox 元素
        $bndbox = $object->addChild('bndbox');
        $bndbox->addChild('x0', $row['x0']);
        $bndbox->addChild('y0', $row['y0']);
        $bndbox->addChild('x1', $row['x1']);
        $bndbox->addChild('y1', $row['y1']);
        $bndbox->addChild('x2', $row['x2']);
        $bndbox->addChild('y2', $row['y2']);
        $bndbox->addChild('x3', $row['x3']);
        $bndbox->addChild('y3', $row['y3']);

        // 格式化 XML
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;

        // 設定 XML 檔案存放路徑
        $xmlFilePath = $xmlFolder . '/' . $fileInfo['filename'] . '.xml';
                // 如果檔案已存在，則刪除舊檔案
                if (file_exists($xmlFilePath)) {
                    unlink($xmlFilePath);
                    echo "舊的 XML 文件已刪除: " . $xmlFilePath . "<br>";
                }
        
                // 創建 XML 對象
                $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><annotation></annotation>');
        

        // 將 XML 存入檔案
        $dom->save($xmlFilePath);

        echo "XML 文件已成功生成: " . $xmlFilePath . "<br>";
        echo "<script>
        alert('XML文件已成功生成');
        window.location.href = 'label_manage.php';
      </script>";
    }
} else {
    // 查詢失敗的處理
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// 關閉連接
$conn->close();
?>
