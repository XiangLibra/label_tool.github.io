<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>管理停車格</title>
    <!-- 引入 Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- 引入 jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- 引入 Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        #image-container {
            position: relative;
        }
        .red-dot {
            width: 5px;
            height: 5px;
            background-color: red;
            border-radius: 50%;
            position: absolute;
        }
        .number-label {
            position: absolute;
            color: white;
            background: black;
            padding: 2px;
            font-size: 12px;
            border-radius: 50%;
        }
        .blue-line {
            position: absolute;
            height: 2px;
            background-color: blue;
        }
        .original-size {
    max-width: none;
    max-height: none;
}
    </style>
</head>
<body>
    <div class="container">
        <h1>管理停車格</h1>
      

        <div style="display: flex; justify-content: space-between;">
        <a href="label_tool.php" class="btn btn-info">返回標註工具</a>
        <a href="sql2xml.php" class="btn btn-info">生成XML檔案</a>
        <a href="sql2json.php" class="btn btn-info">生成JSON檔案</a>

        <form action="download_image.php" method="post">
        <input type="hidden" name="image_filename" value="<?php echo $file_name; ?>">
         <input type="submit" class="btn btn-info" value="下載圖片">
        </form>

    <!-- 下載圖片的表單 -->

</div>
        <?php
        include 'config.php'; // 從外部引入帳號
        $dbname = "parking_space"; // 資料庫名稱

        // 創建連接
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 檢測連接
        if ($conn->connect_error) {
            die("連接失敗: " . $conn->connect_error);
        }

      // 處理刪除操作
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    
    // 獲取文件名稱
    $sql = "SELECT file_name FROM park_xml WHERE ID = $delete_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $file_name = $row['file_name'];
        
        // 刪除數據庫中的記錄
        $sql = "DELETE FROM park_xml WHERE ID = $delete_id";
        if ($conn->query($sql) === TRUE) {
            // echo "<div class='alert alert-success'>記錄刪除成功</div>";

            // 刪除uploads資料夾中的文件
            $file_path = 'uploads/' . $file_name;
            if (file_exists($file_path)) {
                unlink($file_path);
                echo "<div class='alert alert-success'>圖片刪除成功</div>";
            } else {
                echo "<div class='alert alert-warning'>找不到對應的圖片</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>刪除記錄時出錯: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>找不到對應的記錄</div>";
    }
}

        // 處理編輯操作
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_id'])) {
            $edit_id = intval($_POST['edit_id']);
            $file_name = $_POST['file_name'];
            $x0 = intval($_POST['x0']);
            $y0 = intval($_POST['y0']);
            $x1 = intval($_POST['x1']);
            $y1 = intval($_POST['y1']);
            $x2 = intval($_POST['x2']);
            $y2 = intval($_POST['y2']);
            $x3 = intval($_POST['x3']);
            $y3 = intval($_POST['y3']);

            $sql = "UPDATE park_xml SET 
                    file_name='$file_name', 
                    x0=$x0, y0=$y0, 
                    x1=$x1, y1=$y1, 
                    x2=$x2, y2=$y2, 
                    x3=$x3, y3=$y3 
                    WHERE ID=$edit_id";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'>記錄更新成功</div>";
            } else {
                echo "<div class='alert alert-danger'>錯誤: " . $sql . "<br>" . $conn->error . "</div>";
            }
        }

        // 查詢並顯示所有記錄
        $sql = "SELECT * FROM park_xml";
        $result = $conn->query($sql);

        echo "<table class='table table-bordered'>";
        echo "<thead class='thead-dark'><tr><th>ID</th><th>文件名</th><th>x0</th><th>y0</th><th>x1</th><th>y1</th><th>x2</th><th>y2</th><th>x3</th><th>y3</th><th>操作</th></tr></thead>";

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['ID']}</td>
                        <td>{$row['file_name']}</td>
                        <td>{$row['x0']}</td>
                        <td>{$row['y0']}</td>
                        <td>{$row['x1']}</td>
                        <td>{$row['y1']}</td>
                        <td>{$row['x2']}</td>
                        <td>{$row['y2']}</td>
                        <td>{$row['x3']}</td>
                        <td>{$row['y3']}</td>
                        <td>
                            <button class='btn btn-primary btn-sm edit-btn' data-id='{$row['ID']}'>編輯</button>
                            <a href='?delete_id={$row['ID']}' class='btn btn-danger btn-sm' onclick=\"return confirm('你確定要刪除嗎?')\">刪除</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='11'>沒有記錄</td></tr>";
        }
        echo "</table>";
        ?>

        <!-- 模態框 -->
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">編輯記錄</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm" method="post" action="">
                            <input type="hidden" name="edit_id" id="edit_id">
                            <div class="form-group">
                                <label>照片名稱:</label>
                                <input type="text" class="form-control" name="file_name" id="edit_file_name" readonly>
                            </div>
                            <div class="form-group">
                                <label>x0:</label>
                                <input type="number" class="form-control" name="x0" id="edit_x0" required>
                            </div>
                            <div class="form-group">
                                <label>y0:</label>
                                <input type="number" class="form-control" name="y0" id="edit_y0" required>
                            </div>
                            <div class="form-group">
                                <label>x1:</label>
                                <input type="number" class="form-control" name="x1" id="edit_x1" required>
                            </div>
                            <div class="form-group">
                                <label>y1:</label>
                                <input type="number" class="form-control" name="y1" id="edit_y1" required>
                            </div>
                            <div class="form-group">
                                <label>x2:</label>
                                <input type="number" class="form-control" name="x2" id="edit_x2" required>
                            </div>
                            <div class="form-group">
                                <label>y2:</label>
                                <input type="number" class="form-control" name="y2" id="edit_y2" required>
                            </div>
                            <div class="form-group">
                                <label>x3:</label>
                                <input type="number" class="form-control" name="x3" id="edit_x3" required>
                            </div>
                            <div class="form-group">
                                <label>y3:</label>
                                <input type="number" class="form-control" name="y3" id="edit_y3" required>
                            </div>
                            <input type="submit" class="btn btn-primary" value="更新">
                        </form>
                        <div id="image-container">
                            <img id="current-image" src="" alt="Image"class="img-fluid original-size" />
                            <div id="red-dots-container"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                var id = $(this).data('id');
                window.location.href = 'label_edit.php?id=' + id;
                // $.ajax({
                //     url:'get_record.php',
                //     type: 'GET',
                //     data: {id: id},
                //     success: function(response) {
                //         var data = JSON.parse(response);
                //         $('#edit_id').val(data.ID);
                //         $('#edit_file_name').val(data.file_name);
                //         $('#edit_x0').val(data.x0);
                //         $('#edit_y0').val(data.y0);
                //         $('#edit_x1').val(data.x1);
                //         $('#edit_y1').val(data.y1);
                //         $('#edit_x2').val(data.x2);
                //         $('#edit_y2').val(data.y2);
                //         $('#edit_x3').val(data.x3);
                //         $('#edit_y3').val(data.y3);

                //         // 設置圖片
                //         $('#current-image').attr('src', 'uploads/' + data.file_name);

                //         // 清除現有的紅點和標籤
                //         $('#red-dots-container').empty();

                //         // 添加新紅點和標籤
                //         var coordinates = [
                //             {x: data.x0, y: data.y0},
                //             {x: data.x1, y: data.y1},
                //             {x: data.x2, y: data.y2},
                //             {x: data.x3, y: data.y3}
                //         ];

                //         coordinates.forEach(function(coord, index) {
                //             var dot = $('<div class="red-dot"></div>').css({
                //                 left: coord.x + 'px',
                //                 top: coord.y + 'px'
                //             });
                //             var label = $('<div class="number-label"></div>').text(index).css({
                //                 left: coord.x + 'px',
                //                 top: coord.y + 'px'
                //             });
                //             $('#red-dots-container').append(dot).append(label);
                //         });

                //         $('#editModal').modal('show');
                //     }
                // });
            });
        });
    </script>
</body>
</html>

