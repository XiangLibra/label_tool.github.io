<?php
include 'config.php';  // 從外部引入帳號
$dbname = "parking_space"; // 資料庫名稱

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢測連接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function updatejson($conn, $jsonFolder) //更新JSON檔案
{
    // 如果JSON資料夾不存在，則建立該資料夾
    // if (!is_dir($jsonFolder)) {
    //     mkdir($jsonFolder);
    //     echo '資料夾 ' . $jsonFolder . ' 創建成功<br>';
    // }

    $sql = "SELECT * FROM park_xml";
    $result = $conn->query($sql);

    // 檢查查詢結果
    if ($result) {
        $data = array(
            "categories" => array(),
            "images" => array(),
            "annotations" => array()
        );

        $category_id = 1; // 假設類別ID為1
        $data["categories"][] = array(
            "id" => $category_id,
            "name" => "1",
            "keypoints" => array("0", "1", "2", "3")
        );

        $annotation_id = 1;
        $image_id = 1;

        while ($row = $result->fetch_assoc()) {
            // 添加圖像資訊
            $image_info = array(
                "id" => $image_id,
                "width" => intval($row["width"]), // 假設有這個欄位，請根據實際情況調整
                "height" => intval($row["height"]), // 假設有這個欄位，請根據實際情況調整
                "file_name" => $row["file_name"]
            );
            $data["images"][] = $image_info;

            // 計算bbox
            $x_coords = array($row["x0"], $row["x1"], $row["x2"], $row["x3"]);
            $y_coords = array($row["y0"], $row["y1"], $row["y2"], $row["y3"]);
            $bbox = array(
                min($x_coords),
                min($y_coords),
                max($x_coords) - min($x_coords),
                max($y_coords) - min($y_coords)
            );

            // 添加註釋資訊
            $annotation = array(
                "id" => $annotation_id,
                "image_id" => $image_id,
                "category_id" => $category_id,
                "bbox" => array_map('intval', $bbox),
                "segmentation" => array(
                    array_map('intval', array(
                        $row["x0"], $row["y0"],
                        $row["x1"], $row["y1"],
                        $row["x2"], $row["y2"],
                        $row["x3"], $row["y3"]
                    ))
                ),
                "keypoints" => array_map('intval', array(
                    $row["x0"], $row["y0"], 2,
                    $row["x1"], $row["y1"], 2,
                    $row["x2"], $row["y2"], 2,
                    $row["x3"], $row["y3"], 2
                )),
                "num_keypoints" => 4,
                "iscrowd" => 0
            );
            $data["annotations"][] = $annotation;

            $annotation_id++;
            $image_id++;
        }

        // 將陣列轉換為JSON格式
        $json_data = json_encode($data, JSON_PRETTY_PRINT);

        // 設定JSON檔案名
        // $json_filename = $jsonFolder . '/parking_space.json';
       
        $json_filename = 'D:/xiang/下載/parking_space.json';

        // 輸出到文件
        file_put_contents($json_filename, $json_data);
        echo "JSON文件已成功生成: " . $json_filename . "<br>";
         // JavaScript to redirect
         echo "<script>
         alert('JSON文件已成功生成: " . $json_filename . "');
         window.location.href = 'label_manage.php';
       </script>";


    } else {
        // 處理查詢失敗
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

updatejson($conn, 'json_file'); //呼叫更新JSON函數

$conn->close();
?>


<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>建立JSON標籤</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h2>請增加影像的名稱和座標</h2>
    <form action="sql2json.php" method="post">
        <label for="file_name">File Name:</label>
        <input type="text" name="file_name" required>
        <br>
        <label for="x0">x0:</label>
        <input type="number" name="x0" required>
        <br>
        <label for="y0">y0:</label>
        <input type="number" name="y0" required>
        <br>
        <label for="x1">x1:</label>
        <input type="number" name="x1" required>
        <br>
        <label for="y1">y1:</label>
        <input type="number" name="y1" required>
        <br>
        <label for="x2">x2:</label>
        <input type="number" name="x2" required>
        <br>
        <label for="y2">y2:</label>
        <input type="number" name="y2" required>
        <br>
        <label for="x3">x3:</label>
        <input type="number" name="x3" required>
        <br>
        <label for="y3">y3:</label>
        <input type="number" name="y3" required>
        <br>
        <input type="submit" value="Add Annotation">
    </form>
</body>
</html>




 -->
