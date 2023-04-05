<html>
<header>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>AI Final</title>
</header>
<body>
<div class="container mt-5">
    <form action="up.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="fileToUpload" class="form-label">Select image to upload:</label>
            <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Upload Image</button>
    </form>
</div>

<?php
    $menu = "";
    $imgName = "curr.jpg";
    if (isset($_GET['imgName'])) {
        global $imgName;
        $imgName = $_GET['imgName'];
    }
    echo '<img src="' . $imgName . '">';
    echo "<br>";
    //ระบบแปลงภาพเอกสารให้เป็นข้อความ ( Optical Character Recognition: T-OCR )
    $curl = curl_init();
    $img_file = $imgName;
    $data = array("uploadfile" => new CURLFile($img_file, mime_content_type($img_file), basename($img_file)));

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.aiforthai.in.th/ocr",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
          "Content-Type: multipart/form-data",
          "apikey: etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
        ),
    ));

    $ocr = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        $json_string = $ocr;
        $json_string_ocr = str_replace(array("\n", "\r", "\t", ',', '\\','n','"'), ' ', $json_string);
        //echo $ocr;
        //$arr = json_decode($ocr, true);
        //print_r($arr);
        echo "<br>";
        echo $json_string;
    }
    // เพิ่มโค้ด curl ในการเรียกใช้ API text cleansing
    $curl = curl_init();
    
    curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.aiforthai.in.th/textcleansing?text=".urlencode($ocr),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "apikey: etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
    )
    ));
    
    $cleansing = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
    echo "cURL Error #:" . $err;
    } else {
    //echo $cleansing;
    }
    ///EmoNews
    $curl = curl_init();
 
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.aiforthai.in.th/emonews/prediction?text=".urlencode($cleansing),
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,  
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Apikey: etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
      )
    ));
     
    $EmoNews = curl_exec($curl);
    $err = curl_error($curl);
     
    curl_close($curl);
     
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
        $json_string = $EmoNews;
        $json_string_EmoNews = str_replace(array("\n", "\r", "\t", ',', '\\','n','"'), ' ', $json_string);
      //echo $EmoNews;
      //$json_str = $EmoNews;
      //$obj_EmoNews = json_decode(stripslashes($json_str));
;
      /*$text = $obj_EmoNews->text;
      $surprise = $obj_EmoNews->result->surprise;
      $neutral = $obj_EmoNews->result->neutral;
      $sadness = $obj_EmoNews->result->sadness;
      $pleasant = $obj_EmoNews->result->pleasant;
      $fear = $obj_EmoNews->result->fear;
      $anger = $obj_EmoNews->result->anger;
      $joy = $obj_EmoNews->result->joy;*/
    }
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <h4>ต้นฉบับ</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">'<?php echo $json_string_ocr ?>'</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h4>ผลลัพธ์</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">'<?php echo $json_string_EmoNews ?>'</p>
                    </div>
                </div>
            </div>
        </div>
    </div>'
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
