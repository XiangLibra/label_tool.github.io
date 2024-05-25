
<?php

include 'config.php';
$dbname = "parking_space"; // 資料庫名稱

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢測連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}


// 查詢 park_xml 表中的所有 file_name
$sql = "SELECT file_name FROM park_xml";
$result = $conn->query($sql);


// 檢查是否 POST 請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 創建臨時文件夾
    $tempDir = 'temp/';
    if (!file_exists($tempDir)) {
        mkdir($tempDir);
    }

    // 複製 uploads/ 目錄中的所有圖片到臨時文件夾
    $uploadDir = 'uploads/';

    // 下載每個檔案
    while($row = $result->fetch_assoc()) {
        $imageFilename = $row['file_name'];
        // echo "$imageFilename";
        $imagePath = 'uploads/' . $imageFilename;

        // 確保圖片存在
        if (file_exists($imagePath)) {
            // 複製圖片到臨時文件夾
            copy($imagePath, $tempDir . $imageFilename);
        } else {
            echo "找不到圖片: $imageFilename<br>";
        }
    }

    $files = scandir($uploadDir);
    // foreach ($files as $file) {
    //     if (is_file($uploadDir . $file)) {
    //         copy($uploadDir . $file, $tempDir . $file);
    //     }
    // }

    // 創建壓縮文件
    $downloadDir =  'D:\xiang\下載'; // 更改下載路徑為本機的 "下載" 資料夾
    $zipFileName = $downloadDir . '\images.zip';
    // $zipFileName = $_SERVER["DOCUMENT_ROOT"] . '/images.zip'; // 保存 zip 文件的路徑
    echo "$zipFileName";
    $zip = new ZipArchive;
// 使用 ZipArchive::CREATE | ZipArchive::OVERWRITE 標誌來覆蓋現有的文件
if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        // 將臨時文件夾中的所有圖片添加到 zip 文件中
        $tempFiles = scandir($tempDir);
        foreach ($tempFiles as $tempFile) {
            if (is_file($tempDir . $tempFile)) {
                $zip->addFile($tempDir . $tempFile, $tempFile);
            }
        }
        $zip->close();

        // 刪除臨時文件夾及其內容
        array_map('unlink', glob("$tempDir/*.*"));
        rmdir($tempDir);

        // 提示下載完成
        echo "圖片下載完成。";
        // echo '<script>window.location.href = "label_tool.php";</script>';

    // 提示下載完成並使用 JavaScript 跳回原本的 PHP 頁面
        echo '<script>alert("圖片下載完成。"); window.location.href = "label_manage.php";</script>';
        echo '<div class="alert alert-success" role="alert">
        圖片下載完成。
      </div>';
 


// echo '<script>
//         // 使用 JavaScript 跳轉到 label_manage.php
//         window.location.href = "label_manage.php";
//       </script>';


        

        exit;
    } else {
        echo "無法建立壓縮文件";
    }
}
?>
