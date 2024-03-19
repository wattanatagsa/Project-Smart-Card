
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>อ่านบัตร</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="123.css">
</head>

<body class="" >
  <center>
    <div>
<img src="ชนแก้ว.png" class="container-sm w-25 h-25">
</div>
</center>

  <script>
   const api_host = "http://127.0.0.1:9898";
const socket = io(api_host, { transports: ['websocket', 'polling'] });

socket.on('smc-data', function (data) {
  var cid = data.personal.cid;
  console.log("CID:", cid);
  var full_name = data.personal.name.full_name;
  console.log("fullname:", full_name);
  window.location = "read.php?cid=" + cid + "&fullname=" + full_name;
});


  </script>
  <?php

  


// -----------------------เชื่อม db ----------------------------
 $con=mysqli_connect("localhost","root","","mydata");

 if (!$con->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $con->error);
}

date_default_timezone_set("Asia/Bangkok");

$setTimeZone = "SET @@session.time_zone = '+07:00'";
mysqli_query($con,$setTimeZone);

// -----------------------------บันทึกข้อมูล-------------------------------
if(isset($_GET['cid']))
{
    $cid = $_GET['cid'];
    $fullname = $_GET['fullname'];
    echo "<center><h1>ข้อมูลล่าสุด<br>$cid $fullname</h1></center>";

    // ตรวจสอบว่า CID นี้มีอยู่แล้วหรือไม่
    $checkDuplicateSql = "SELECT * FROM `project` WHERE `cid` = '$cid';";
    $checkDuplicateResult = mysqli_query($con, $checkDuplicateSql);
    if(mysqli_num_rows($checkDuplicateResult) > 0) {
        // CID ซ้ำ
        echo "<center><h2>ข้อมูลซ้ำ ไม่สามารถเพิ่มข้อมูลได้</h2></center>";
    } else {
        // CID ไม่ซ้ำ, ทำการเพิ่มข้อมูล
        $insertSql = "INSERT INTO `project` (`running`, `cid`, `fullname`, `day`) VALUES (NULL, '$cid', '$fullname', current_timestamp())";
        $insertResult = mysqli_query($con, $insertSql);

        if($insertResult) {
            echo "<center><h2>เพิ่มข้อมูลเรียบร้อย</h2></center>";
        } else {
            echo "<center><h2>เกิดข้อผิดพลาดในการเพิ่มข้อมูล</h2></center>";
        }
    }
}
// ================================== แสดงจำนวนคนวันนี้ ================================



$sql = "SELECT COUNT(cid) AS total FROM `project` WHERE DATE(day) = CURRENT_DATE;";
$result = mysqli_query($con,$sql);
$row = mysqli_fetch_array($result);
$total = $row['total'];
echo "<center><h1>จำนวนสุทธิ : $total  คน</h1></center>";  


// ---------------------------------------เริ่มอ่านข้อมูล-----------------------------------------------------------
?>
 

  <form class="index container mb-3 d-flex justify-content-center align-items-center w-50 m-10 p-2 mt-5 container-sm" >
   
    <div class="mb-3">

<?php
 $sql = "SELECT `cid`,`fullname`,`day` FROM `project` WHERE 1;";
$result = mysqli_query($con,$sql);
$n=0;
while($row = mysqli_fetch_array($result))
    {



        $n++;
        echo "<br>$n) ".$row['cid']." ".$row['fullname']." ".$row['day'];
        
}
?>
</div>
  </form>


</body>

</html>
