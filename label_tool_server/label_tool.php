<?php
session_start();
include 'config.php';

$dbname = "parking_space";

// 創建連接
// 連接 MySQL 伺服器
$conn = new mysqli($servername, $username, $password);
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    // echo "資料庫創建成功或已存在<br>";
} else {
    echo "錯誤創建資料庫: " . $conn->error . "<br>";
}



// 檢測連接
if ($conn) {
    // echo "ok" . "<br>";
} else {
    echo "error";
}
// 創建資料庫（如果不存在）



// 選擇資料庫
$conn->select_db($dbname);

// 創建資料表（如果不存在）
$sql = "CREATE TABLE IF NOT EXISTS `park_xml` (
    `ID` int(10) NOT NULL AUTO_INCREMENT,
    `file_name` char(30) DEFAULT NULL,
    `width` int(10) DEFAULT NULL,
    `height` int(10) DEFAULT NULL,
    `x0` int(10) DEFAULT NULL,
    `y0` int(10) DEFAULT NULL,
    `x1` int(10) DEFAULT NULL,
    `y1` int(10) DEFAULT NULL,
    `x2` int(10) DEFAULT NULL,
    `y2` int(10) DEFAULT NULL,
    `x3` int(10) DEFAULT NULL,
    `y3` int(10) DEFAULT NULL,
    PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

if ($conn->query($sql) === TRUE) {
    // echo "資料表創建成功或已存在<br>";
} else {
    echo "錯誤創建資料表: " . $conn->error . "<br>";
}

// 檢查資料夾是否存在，不存在則創建
$xmlFolder = 'xml_file';
if (!file_exists($xmlFolder)) {
    mkdir($xmlFolder, 0777, true);
    echo "資料夾已成功創建: $xmlFolder" . "<br>";
}

// 檢查是否有 POST 資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 文件上傳處理
    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] == UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['images']['name'][0]);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['images']['tmp_name'][0], $uploadFile)) {
            echo "圖片上傳成功。" . "<br>";
        } else {
            echo "圖片上傳失敗。" . "<br>";
        }
    } else {
        echo "圖片上傳錯誤。" . "<br>";
    }

    // 收集用戶輸入
    $file_name = $_POST["file_name"];
    $width = $_POST["width"];
    $height = $_POST["height"];
    $x0 = $_POST["x0"];
    $y0 = $_POST["y0"];
    $x1 = $_POST["x1"];
    $y1 = $_POST["y1"];
    $x2 = $_POST["x2"];
    $y2 = $_POST["y2"];
    $x3 = $_POST["x3"];
    $y3 = $_POST["y3"];

    // 插入數據
    $sql = "INSERT INTO park_xml(ID, file_name, width, height, x0, y0, x1, y1, x2, y2, x3, y3)
            VALUES (NULL, '$file_name', '$width', '$height', '$x0', '$y0', '$x1', '$y1', '$x2', '$y2', '$x3', '$y3')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "建立成功 <br>照片名稱是 " . $file_name;
    } else {
        $_SESSION['message'] = "錯誤: " . $sql . "<br>" . $conn->error;
    }
    // 使用 PRG 模式進行重定向
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// 關閉連接
$conn->close();
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>建立客製化標籤</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .container {
            display: flex;
            flex-direction: row;
            /* max-width: 1200px; */
            margin: 20px 0 20px 20px; /* Align to the left */
            padding: 20px 0 20px 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .left-panel {
            flex: 0 0 300px; /* 固定左邊面板的寬度為300px */
            margin: 0px;
            background-color: #ff7f50; /* 更鮮艷的橙色背景 */
        }

        .right-panel {
            flex: 1; /* 右邊面板彈性擴展 */
            margin: 10px;
        }
        .left-panel h2, .right-panel h1 {
            margin-top: 0;
            font-size: 24px; /* 更大的字體大小 */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3); /* 添加文字陰影 */
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            font-size: 14px;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
        }
        .button:hover {
            background-color: #218838;
        }
        #image-container {
            text-align: center;
            position: relative;
        }
        #image-container img {
            display: block;
            margin: 0 auto;
        }
        #coordinates {
            position: absolute;
            bottom: 0;
            left: 0;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 5px;
            display: none;
        }
        .red-dot {
            width: 10px;
            height: 10px;
            background-color: red;
            border-radius: 50%;
            position: absolute;
            transform: translate(-50%, -50%);
        }
        .number-label {
            position: absolute;
            color: white;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            width: 20px;
        }
        .blue-line {
            position: absolute;
            height: 2px;
            background-color: blue;
            transform-origin: 0% 50%;
        }
        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #B5C213; /* 綠色背景 */
            color: white; /* 白色字體 */
            border: none; /* 無邊框 */
            border-radius: 4px; /* 圓角 */
            font-size: 16px; /* 字體大小 */
        }

        input[type="file"] {
            display: none;
        }

        /* 滑鼠懸停效果 */
        .custom-file-upload:hover {
            background-color: #45a049; /* 更深的綠色 */
        }

        /* 按鈕的活躍狀態 */
        .custom-file-upload:active {
            background-color: #23B6B1; /* 最深的綠色 */
            box-shadow: 0 5px #666; /* 陰影 */
            transform: translateY(4px); /* 按下的效果 */
        }
    </style>
</head>
<body>
<div class="container">
        <div class="col-md-3 left-panel">
        <a href="label_manage.php" class="button">管理資料</a>
            <h2>請增加影像的名稱和座標</h2>
            <?php
            if (isset($_SESSION['message'])) {
                echo $_SESSION['message'];
                unset($_SESSION['message']); // 顯示消息後清除它
            }
            ?>
            <form action="" method="post" enctype="multipart/form-data">
            <label for="images" class="btn btn-primary">
                    <span>選擇圖片</span>
                    <input type="file" name="images[]" id="images" multiple="multiple" accept="image/*" />
                </label>
                <br>
                <label for="file_name">照片名稱:</label>
                <input type="text" name="file_name" id="file_name" required readonly>
                <br>
                <label for="width">寬度:</label>
                <input type="number" name="width" id="width" readonly>
                <br>
                <label for="height">高度:</label>
                <input type="number" name="height" id="height" readonly>
                <br>
                <label for="x0">x0:</label>
                <input type="number" name="x0" id="x0" required>
                <br>
                <label for="y0">y0:</label>
                <input type="number" name="y0" id="y0" required>
                <br>
                <label for="x1">x1:</label>
                <input type="number" name="x1" id="x1" required>
                <br>
                <label for="y1">y1:</label>
                <input type="number" name="y1" id="y1" required>
                <br>
                <label for="x2">x2:</label>
                <input type="number" name="x2" id="x2" required>
                <br>
                <label for="y2">y2:</label>
                <input type="number" name="y2" id="y2" required>
                <br>
                <label for="x3">x3:</label>
                <input type="number" name="x3" id="x3" required>
                <br>
                <label for="y3">y3:</label>
                <input type="number" name="y3" id="y3" required>
                <br>
               
                <input type="submit" value="新增標籤">
            </form>
            <div style="display: flex; justify-content: space-between;">

</div>

           
        </div>
        <div class="col-md-6 right-panel">
            <h1>客製化影像標籤工具</h1>
            <div id="image-container">
                <img id="current-image" src="" alt="Image" />
                <div id="coordinates">X: 0, Y: 0</div>
                <div id="red-dots-container"></div>
            </div>
            <input type="hidden" name="current_coordinate" id="current-coordinate" value="0">
        </div>
    </div>

    <script>
        var images = [];
        var currentImageIndex = 0;
        var currentImage = document.getElementById('current-image');
        var coordinates = document.getElementById('coordinates');
        var fileNameInput = document.getElementById('file_name');
        var isDrawing = false; 
        var currentCoordinate = 0;

        function updateCoordinates() {
            coordinates.innerHTML = 'X: 0 , Y: 0';
        }

        document.getElementById('images').addEventListener('change', function (e) {
            images = Array.from(e.target.files);
            if (images.length > 0) {
                loadImage(0);
                document.getElementById('file_name').value = images[0].name;
            }
        });

        function loadImage(index) {
            var reader = new FileReader();
            reader.onload = function (e) {
                currentImage.src = e.target.result;
                currentImage.style.display = 'block';
                currentImage.onload = function () {
                    document.getElementById('width').value = currentImage.width;
                    document.getElementById('height').value = currentImage.height;
                };
            };
            reader.readAsDataURL(images[index]);
        }

        currentImage.addEventListener('click', function (e) {
            if (currentCoordinate >= 4) {
            currentCoordinate = 0; // 重置 currentCoordinate 變量
            document.getElementById('red-dots-container').innerHTML = ''; // 清空紅點和藍線
            document.getElementById('current-coordinate').value = 0; // 重置隱藏的 current-coordinate input
        }
        
    if (currentCoordinate <4 ) {
        var rect = currentImage.getBoundingClientRect();
        var x = e.clientX - rect.left;
        var y = e.clientY - rect.top;

        var inputX = document.getElementById('x' + currentCoordinate);
        var inputY = document.getElementById('y' + currentCoordinate);
        inputX.value = Math.round(x);
        inputY.value = Math.round(y);

        var dot = document.createElement('div');
        dot.className = 'red-dot';
        dot.style.left = x + 'px';
        dot.style.top = y + 'px';

        var label = document.createElement('div');
        label.className = 'number-label';
        label.style.left = x + 'px';
        label.style.top = y + 'px';
        label.textContent = currentCoordinate;

        document.getElementById('red-dots-container').appendChild(dot);
        document.getElementById('red-dots-container').appendChild(label);

        if (currentCoordinate > 0) {
            var previousX = document.getElementById('x' + (currentCoordinate - 1)).value;
            var previousY = document.getElementById('y' + (currentCoordinate - 1)).value;

            var line = document.createElement('div');
            line.className = 'blue-line';
            var length = Math.sqrt(Math.pow(x - previousX, 2) + Math.pow(y - previousY, 2));
            line.style.width = length + 'px';
            var angle = Math.atan2(y - previousY, x - previousX) * 180 / Math.PI;
            line.style.transform = 'rotate(' + angle + 'deg)';
            line.style.left = previousX + 'px';
            line.style.top = previousY + 'px';
            document.getElementById('red-dots-container').appendChild(line);
        }

    
       
       
        
    }
   

        currentCoordinate++;
       

});

</script>

</body>
</html>

