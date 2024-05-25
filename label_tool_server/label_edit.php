<?php
// 檢查是否有 POST 請求
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 檢查是否有接收到所需的數據
    if (isset($_POST['edit_id']) && isset($_POST['file_name']) && isset($_POST['x0']) && isset($_POST['y0']) &&
        isset($_POST['x1']) && isset($_POST['y1']) && isset($_POST['x2']) && isset($_POST['y2']) &&
        isset($_POST['x3']) && isset($_POST['y3'])) {
        
        // 從 POST 數據中獲取所需的值
        $edit_id = $_POST['edit_id'];
        $file_name = $_POST['file_name'];
        $x0 = $_POST['x0'];
        $y0 = $_POST['y0'];
        $x1 = $_POST['x1'];
        $y1 = $_POST['y1'];
        $x2 = $_POST['x2'];
        $y2 = $_POST['y2'];
        $x3 = $_POST['x3'];
        $y3 = $_POST['y3'];

        // 在這裡執行更新座標的 SQL 語句，假設你有一個名為 park_xml 的資料表
        // 注意：這只是一個示例，請根據你的實際情況編寫更新操作的 SQL 語句
        include 'config.php'; // 包含資料庫連接配置
        $dbname = "parking_space"; // 資料庫名稱
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("連接失敗: " . $conn->connect_error);
        }
        
        $sql = "UPDATE park_xml SET x0=$x0, y0=$y0, x1=$x1, y1=$y1, x2=$x2, y2=$y2, x3=$x3, y3=$y3 WHERE ID=$edit_id";

        if ($conn->query($sql) === TRUE) {
            echo "座標更新成功";
           
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    } else {
        echo "未接收到完整的數據";
    }
} 
?>


<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>編輯停車格</title>
    <!-- 引入 Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- 引入 jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- 引入 Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        
        .container {
    display: flex;
    flex-direction: row;
    /* max-width: 1200px; */
    margin: 20px 0 20px 20px; /* Align to the left */
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

        #image-container {
            /* text-align: center; */
            position: relative;
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
            transform-origin: 0 0; /* 設置旋轉原點為左上角 */
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
        #coordinates {
            position: absolute;
            bottom: 0;
            left: 0;
            background-color: rgba(255, 255, 255, 0.7);
            padding: 5px;
            display: none;
        }
        .left-panel {
    padding: 0 0px; /* 添加內邊距 */
    
}

.left-panel, .right-panel {
    margin: 20px 0 20px 20px; 
    height: 100%; /* 讓兩個面板在垂直方向上擁有相同的高度 */
}


    </style>
</head>
<body>

    
<div class="container">
    
    <br>
    <div class="row">
        <div class="col-sm-2 left-panel">
            <form id="editForm" method="post">
        <?php
        include 'config.php'; // 包含資料庫連接配置
        $dbname = "parking_space"; // 資料庫名稱

        // 創建連接
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 檢測連接
        if ($conn->connect_error) {
            die("連接失敗: " . $conn->connect_error);
        }

        // 獲取編輯記錄的 ID
        $edit_id = $_GET['id'];

        // 查詢編輯記錄
        $sql = "SELECT * FROM park_xml WHERE ID = $edit_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // 讀取圖片和座標數據
            $file_name = $row['file_name'];
            $x0 = $row['x0'];
            $y0 = $row['y0'];
            $x1 = $row['x1'];
            $y1 = $row['y1'];
            $x2 = $row['x2'];
            $y2 = $row['y2'];
            $x3 = $row['x3'];
            $y3 = $row['y3'];

            echo "<form id='editForm' method='post'>";
            echo "<input type='hidden' name='edit_id' value='$edit_id'>";
            echo "<label for='file_name'>File Name:</label>";
            echo "<input type='text' name='file_name' value='$file_name'>";
            echo "<label for='x0'>x0:</label>";
            echo "<input type='number' name='x0' id='x0' value='$x0'>";
            echo "<label for='y0'>y0:</label>";
            echo "<input type='number' name='y0' id='y0' value='$y0'>";
            echo "<label for='x1'>x1:</label>";
            echo "<input type='number' name='x1' id='x1' value='$x1'>";
            echo "<label for='y1'>y1:</label>";
            echo "<input type='number' name='y1' id='y1' value='$y1'>";
            echo "<label for='x2'>x2:</label>";
            echo "<input type='number' name='x2' id='x2' value='$x2'>";
            echo "<label for='y2'>y2:</label>";
            echo "<input type='number' name='y2' id='y2' value='$y2'>";
            echo "<label for='x3'>x3:</label>";
            echo "<input type='number' name='x3' id='x3' value='$x3'>";
            echo "<label for='y3'>y3:</label>";
            echo "<input type='number' name='y3' id='y3' value='$y3'>";
            echo "<input type='submit' class='btn btn-primary' value='更新'>";
            echo "<a href='label_manage.php' class='btn btn-secondary' style='margin-left: 10px;'>取消</a>";
            echo "</form>";
        } else {
            echo "找不到編輯記錄";
        }
        ?>
</form>
        </div>
        <div class="col-md-6 right-panel">
            <h1>編輯停車格</h1>
            <div id="image-container">
            <?php
            
            echo "<div style='position: relative; display: inline-block;'>";
            echo "<img id='current-image' src='uploads/$file_name' alt='Current Image'>";
            echo "</div>";

            // 保留紅點和線
            echo "<div class='red-dot' style='left:{$x0}px; top:{$y0}px;'></div>";
            echo "<div class='red-dot' style='left:{$x1}px; top:{$y1}px;'></div>";
            echo "<div class='red-dot' style='left:{$x2}px; top:{$y2}px;'></div>";
            echo "<div class='red-dot' style='left:{$x3}px; top:{$y3}px;'></div>";

            echo "<div class='blue-line' style='width:" . sqrt(($x1 - $x0) ** 2 + ($y1 - $y0) ** 2) . "px; transform:rotate(" . atan2($y1 - $y0, $x1 - $x0) * 180 / M_PI . "deg); left:{$x0}px; top:{$y0}px;'></div>";
            echo "<div class='blue-line' style='width:" . sqrt(($x2 - $x1) ** 2 + ($y2 - $y1) ** 2) . "px; transform:rotate(" . atan2($y2 - $y1, $x2 - $x1) * 180 / M_PI . "deg); left:{$x1}px; top:{$y1}px;'></div>";
            echo "<div class='blue-line' style='width:" . sqrt(($x3 - $x2) ** 2 + ($y3 - $y2) ** 2) . "px; transform:rotate(" . atan2($y3 - $y2, $x3 - $x2) * 180 / M_PI . "deg); left:{$x2}px; top:{$y2}px;'></div>";
        
        ?>
  </div>
        </div>
    </div>
</div>

    

    <script>
       $(document).ready(function() {
            var coordinateIndex = 0;
            var isFirstClick = true;

            $('#current-image').on('click', function(e) {
                if (isFirstClick) {
            $('.red-dot').remove();
            $('.blue-line').remove();
            isFirstClick = false;
        }
                
                // 如果點擊的是第五個紅點，重置點和線
                if (coordinateIndex >= 4) {
                    coordinateIndex = 0;
                    $('.red-dot').remove();
                    $('.blue-line').remove();
                }

                // 獲取滑鼠點擊位置的坐標
                var x = e.pageX - $(this).offset().left;
                var y = e.pageY - $(this).offset().top;

                // 更新對應的輸入框
                $('#x' + coordinateIndex).val(Math.round(x));
                $('#y' + coordinateIndex).val(Math.round(y));

                // 添加紅點
                var redDot = $('<div class="red-dot"></div>');
                redDot.css({ left: x + 'px', top: y + 'px' });
                $('#image-container').append(redDot);

                // 如果是第二個點及以後，畫線
                if (coordinateIndex > 0) {
                    var prevX = $('#x' + (coordinateIndex - 1)).val();
                    var prevY = $('#y' + (coordinateIndex - 1)).val();
                    drawLine(prevX, prevY, x, y);
                }

                coordinateIndex++;
            });

            function drawLine(x0, y0, x1, y1) {
                var length = Math.sqrt((x1 - x0) ** 2 + (y1 - y0) ** 2);
                var angle = Math.atan2(y1 - y0, x1 - x0) * 180 / Math.PI;

                var line = $('<div class="blue-line"></div>');
                line.css({
                    width: length + 'px',
                    transform: 'rotate(' + angle + 'deg)',
                    left: x0 + 'px',
                    top: y0 + 'px'
                });
                $('#image-container').append(line);
            }
        });

        // 表單提交後重定向到 label_manage.php
        $('#editForm').on('submit', function(event) {
            event.preventDefault(); // 防止表單默認提交
            
            // 提交表單後重定向
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('座標更新成功');
                    window.location.href = 'label_manage.php';
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    // 在錯誤時處理
                    alert("更新座標時出現錯誤");
                    // 或者在頁面上顯示錯誤信息
                }
            });
        });
    //     currentImage.addEventListener('click', function (e) {
    //     if (currentCoordinate < 4) {
    //         var rect = currentImage.getBoundingClientRect();
    //         var x = e.clientX - rect.left;
    //         var y = e.clientY - rect.top;

    //         var inputX = document.getElementById('x' + currentCoordinate);
    //         var inputY = document.getElementById('y' + currentCoordinate);
    //         inputX.value = Math.round(x);
    //         inputY.value = Math.round(y);

    //         var dot = document.createElement('div');
    //         dot.className = 'red-dot';
    //         dot.style.left = x + 'px';
    //         dot.style.top = y + 'px';

    //         var label = document.createElement('div');
    //         label.className = 'number-label';
    //         label.style.left = x + 'px';
    //         label.style.top = y + 'px';
    //         label.textContent = currentCoordinate;

    //         document.getElementById('red-dots-container').appendChild(dot);
    //         document.getElementById('red-dots-container').appendChild(label);

    //         if (currentCoordinate > 0) {
    //             var previousX = document.getElementById('x' + (currentCoordinate - 1)).value;
    //             var previousY = document.getElementById('y' + (currentCoordinate - 1)).value;

    //             var line = document.createElement('div');
    //             line.className = 'blue-line';
    //             var length = Math.sqrt(Math.pow(x - previousX, 2) + Math.pow(y - previousY, 2));
    //             line.style.width = length + 'px';
    //             var angle = Math.atan2(y - previousY, x - previousX) * 180 / Math.PI;
    //             line.style.transform = 'rotate(' + angle + 'deg)';
    //             line.style.left = previousX + 'px';
    //             line.style.top = previousY + 'px';
    //             document.getElementById('red-dots-container').appendChild(line);
    //         }

    //         currentCoordinate++;
    //         if (currentCoordinate === 4) {
    //             document.getElementById('current-coordinate').value = 4;
    //         }
    //     }
    // });
</script>
</body>
</html>