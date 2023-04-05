<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- ติดตั้ง Bootstrap -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <title>รับข้อความ</title>
</head>
<body>
  <div class="container mt-5">
    <h1>รับข้อความ</h1>
    <form action="" method="GET">
      <div class="form-group">
        <label for="message">ข้อความ</label>
        <textarea class="form-control" id="message" name="message" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">ส่งข้อความ</button>
    </form>
  </div>

  <!-- ติดตั้ง JavaScript ของ Bootstrap -->
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<?php
if(isset($_GET["message"])) {
    $message = $_GET["message"];

    $curl = curl_init();
 
  curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.aiforthai.in.th/emonews/prediction?text=".urlencode($message),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,  
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Apikey:etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
    )
  ));
   
  $EmoNews = curl_exec($curl);
  $err = curl_error($curl);
   
  curl_close($curl);
   
  if ($err) {
    echo "cURL Error #:" . $err;
  } else {
    //echo $EmoNews;
    $json_str = $EmoNews;
    $obj_EmoNews = json_decode($json_str);
    echo '<script>console.log(' . json_encode($EmoNews) . ');</script>';
    echo '<div class="container-fluid">
            <div class="col-lg-6">
                <h4>ผลลัพธ์</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">' . $obj_EmoNews->text . '</p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">Result:</p>
                        <ul class="list-group">
                            <li class="list-group-item">Surprise: ' . $obj_EmoNews->result->surprise  . '</li>
                            <li class="list-group-item">Neutral: ' . $obj_EmoNews->result->neutral  . '</li>
                            <li class="list-group-item">Sadness: ' . $obj_EmoNews->result->sadness  . '</li>
                            <li class="list-group-item">Pleasant: ' . $obj_EmoNews->result->pleasant  . '</li>
                            <li class="list-group-item">Fear: ' . $obj_EmoNews->result->fear  . '</li>
                            <li class="list-group-item">Anger: ' . $obj_EmoNews->result->anger  . '</li>
                            <li class="list-group-item">Joy: ' . $obj_EmoNews->result->joy  . '</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>';
  }
}
?>
</body>
</html>
