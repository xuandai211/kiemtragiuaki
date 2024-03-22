<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>THÔNG TIN NHÂN VIÊN</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .pagination a {
            padding: 8px;
            text-decoration: none;
            color: #000;
            margin: 0 10px;
            border: 5px solid #ccc;
        }

        .pagination a.active {
            background-color: #4CAF50;
            color: white;
            border: 5px solid #4CAF50;
        }
        h2 {
            text-align: center;
            color: green;
}
    </style>
</head>
<body>
    <?php
    $servername = "localhost";
    $database = "ql_nhansu";
    $username = "root";
    $password = "";
    $conn = mysqli_connect($servername, $username, $password, $database);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $limit = 5;
    $start = ($page - 1) * $limit;
    $sql_total = "SELECT COUNT(*) AS total FROM NHANVIEN";
    $stmt_total = mysqli_prepare($conn, $sql_total);
    mysqli_stmt_execute($stmt_total);
    mysqli_stmt_bind_result($stmt_total, $total_records);
    mysqli_stmt_fetch($stmt_total);
    mysqli_stmt_close($stmt_total);

    $total_pages = ceil($total_records / $limit);

    $sql = "SELECT n.Ma_NV, n.Ten_NV, n.Phai, n.Noi_Sinh, p.Ten_Phong as Ma_Phong, n.Luong 
            FROM NHANVIEN n
            INNER JOIN PHONGBAN p ON n.Ma_Phong = p.Ma_Phong
            LIMIT ?, ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $start, $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<h2>THÔNG TIN SINH VIÊN</h2>";
        echo "<table>";
        echo "<tr><th>Ma_NV</th><th>Ten_NV</th><th>Phai</th><th>Noi_Sinh</th><th>Ma_Phong</th><th>Luong</th><th>Action</tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row["Ma_NV"]."</td>";
            echo "<td>".$row["Ten_NV"]."</td>";
            echo "<td><img src='images/".($row["Phai"] == 'NAM' ? 'man.jpg' : 'woman.jpg')."' style='max-width: 100px; max-height: 100px;'></td>";
            echo "<td>".$row["Noi_Sinh"]."</td>";
            echo "<td>".$row["Ma_Phong"]."</td>";
            echo "<td>".$row["Luong"]."</td>";
            echo "<td>
                <button onclick='themNhanVien(\"".$row["Ma_NV"]."\")'>Thêm</button>
                <button onclick='suaNhanVien(\"".$row["Ma_NV"]."\")'>Sửa</button>
                <button onclick='xoaNhanVien(\"".$row["Ma_NV"]."\")'>Xóa</button>
              </td>";
        echo "</tr>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<div class='pagination'>";
        for ($i = 1; $i <= $total_pages; $i++) {
            $active_class = ($i == $page) ? 'active' : '';
            echo "<a href='?page=".$i."' class='".$active_class."'>".$i."</a> ";
        }
        echo "</div>";
    } else {
        echo "Không có kết quả nào.";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>
    <script>
    function themNhanVien(employeeId) {
        window.location.href = "add.php";
    }

    function suaNhanVien(employeeId) {
        window.location.href = "edit.php";
    }

    function deleteEmployee(employeeId) {
        window.location.href = "delete.php";
    }
    </script>