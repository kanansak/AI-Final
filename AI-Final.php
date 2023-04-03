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
            "apikey: etTW2zhw5WLwgAoo2HkfnePopSOP52sJ",
        ),
    ));

    $ocr = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        //echo $ocr;
        $arr = json_decode($ocr, true);
        //print_r($arr);
        echo "<br>";
        array_walk_recursive($arr, function ($item, $key) {
            //echo "$key holds $item"."<br>";
            if ($key == "result") {
                global $menu;
                $menu = $item;
            }
        });
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
//ระบบสรุปความภาษาภาไทย (Thai Text Summarization)

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.aiforthai.in.th/textsummarize',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $cleansing,
        CURLOPT_HTTPHEADER => array(
            "Apikey: etTW2zhw5WLwgAoo2HkfnePopSOP52sJ"
        )
    ));
    
    $Summarization = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
        echo 'cURL Error #:' . $err;
    } else {
        //echo $Summarization;
    }
 
?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <h4>ต้นฉบับ</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">'<?php echo $ocr ?>'</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <h4>ผลลัพธ์</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">'<?php echo $Summarization ?>'</p>
                    </div>
                </div>
            </div>
        </div>
    </div>'
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
