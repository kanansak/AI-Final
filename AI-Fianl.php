<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- ติดตั้ง Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <title>AI Final</title>
</head>

<body>
<!-- รับข้อความ -->
<div class="container mt-5">
    <h1>AI การวิเคราะห์ข้อความ</h1>
    <form action="" method="GET">
        <div class="form-group">
            <label for="message">ข้อความ (ไม่เกิน 1000 ตัวอักษร)</label>
            <textarea class="form-control" id="message" name="message" rows="3" maxlength="1000"></textarea>
            <p class="text-muted">จำนวนตัวอักษรที่ใส่ไป: <span id="charNum">0</span>/1000</p>
        </div>
        <button type="submit" class="btn btn-primary">ส่งข้อความ</button>
    </form>
</div>
<!-- อัพโหลดรูป -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <form action="up.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
          <label for="fileToUpload">Select image to upload:</label>
          <input type="file" class="form-control-file" id="fileToUpload" name="fileToUpload">
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-primary">Upload</button>
        </div>
      </form>
    </div>
  </div>
</div>


<script>
    // เพิ่ม event listener ให้กับ textarea เพื่อนับจำนวนตัวอักษรที่ใส่ไป
    document.getElementById("message").addEventListener("input", function() {
        document.getElementById("charNum").textContent = this.value.length;
    });
</script>
    <!-- ติดตั้ง JavaScript ของ Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script> <!--เป็นการเรียกใช้ไลบรารี jQuery เวอร์ชัน slim -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script><!--เป็นการเรียกใช้ไลบรารี Popper.js ที่ใช้สำหรับให้การแสดงผลแบบ responsive  -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script><!--เป็นการเรียกใช้ไลบรารี Bootstrap เวอร์ชัน 4.0.0 -->

    
<?php

$Apikey = "etTW2zhw5WLwgAoo2HkfnePopSOP52sJ";
global $spell_correction;
//ระบบแปลงภาพเอกสารให้เป็นข้อความ ( Optical Character Recognition: T-OCR )
    if(isset($_GET["imgName"])) { //เช็คว่ามีการส่งข้อมูลชื่อไฟล์ภาพมาหรือไม่
        $imgName = $_GET["imgName"]; // set the value of $imgName from the URL parameter
        $curl = curl_init(); //เริ่มต้นการใช้งาน cURL library
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
                "apikey: $Apikey"
            )
        ));

        $OCR = curl_exec($curl); //ส่ง request ไปยัง API และเก็บ response ที่ได้กลับมาเก็บไว้ในตัวแปร $OCR
        $err = curl_error($curl); //เก็บ error message หากมี error ในการส่ง request ไปยัง API

        curl_close($curl); // ปิดการเชื่อมต่อ cURL

        if ($err) { //ตรวจสอบว่ามี error หรือไม่ ถ้ามีก็แสดง error message ออกมา
            echo "cURL Error #:" . $err;
        } else {
            //echo $OCR;
            $obj_OCR = json_decode($OCR); // แปลง response ที่ได้รับมาจาก API จาก JSON string เป็น object เพื่อให้ง่ายต่อการเข้าถึงข้อมูล
            //echo '<img src="'.$imgName.'">';
            $spell_correction = $obj_OCR->Spellcorrection; // เก็บค่า Spellcorrection จาก response
            //echo $spell_correction; // display Spellcorrection
        }
    }

//EmoNews บริการทำนายอารมณ์ของผู้อ่าน หลังจากอ่านหัวข้อข่าว โดยใช้เทคนิค fastText
    if (isset($_GET["message"]) || $spell_correction) {    
        $message = isset($_GET["message"]) ? $_GET["message"] : ""; 
        if (isset($spell_correction)) {
            $message = $spell_correction;
        }else {
            $spell_correction = "";
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.aiforthai.in.th/emonews/prediction?text=" . urlencode($message),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Apikey:$Apikey",
                //"Spellcorrection:$spell_correction" // add Spellcorrection header
            ),
        ));

        $EmoNews = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $EmoNews;
            //$json_str = $EmoNews;
            $obj_EmoNews = json_decode($EmoNews ,true);
            echo '<script>console.log(' . json_encode($EmoNews) . ');</script>';
            $surprise_score = $obj_EmoNews['result']['surprise'];
            $neutral_score = $obj_EmoNews['result']['neutral'];
            $sadness_score = $obj_EmoNews['result']['sadness'];
            $pleasant_score = $obj_EmoNews['result']['pleasant'];
            $fear_score = $obj_EmoNews['result']['fear'];
            $anger_score = $obj_EmoNews['result']['anger'];
            $joy_score = $obj_EmoNews['result']['joy'];
            // Calculate total score
            $total_score = $surprise_score + $neutral_score + $sadness_score
            + $pleasant_score + $fear_score + $anger_score + $joy_score;
            // Calculate percentages
            $surprise_percentage = round(($surprise_score / $total_score) * 100, 2);
            $neutral_percentage = round(($neutral_score / $total_score) * 100, 2);
            $sadness_percentage = round(($sadness_score / $total_score) * 100, 2);
            $pleasant_percentage = round(($pleasant_score / $total_score) * 100, 2);
            $fear_percentage = round(($fear_score / $total_score) * 100, 2);
            $anger_percentage = round(($anger_score / $total_score) * 100, 2);
            $joy_percentage = round(($joy_score / $total_score) * 100, 2);
            echo '<div class="container-fluid">
                <div class="container mt-5 mx-auto">
                    <h4>ผลลัพธ์ </h4>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text"> '.$message.'</p>
                        </div>
                    </div>
                </div>
            </div>';
            echo '<div class="container-fluid">
                <div class="container mt-5 mx-auto">
                    <h4>ผลลัพธ์ EmoNews</h4>
                    <div class="card">
                        <div class="card-body">
                            <p class="card-text">Result:</p>
                            <ul class="list-group">
                            <li class="list-group-item">Surprise: ' . $surprise_percentage . '%</li>
                            <li class="list-group-item">Neutral: ' . $neutral_percentage . '%</li>
                            <li class="list-group-item">Sadness: ' . $sadness_percentage . '%</li>
                            <li class="list-group-item">Pleasant: ' . $pleasant_percentage . '%</li>
                            <li class="list-group-item">Fear: ' . $fear_percentage . '%</li>
                            <li class="list-group-item">Anger: ' . $anger_percentage . '%</li>
                            <li class="list-group-item">Joy: ' . $joy_percentage . '%</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }

//เอสเซนส์ ระบบวิเคราะห์ความคิดเห็นจากข้อความ (Social Sensing: SSENSE)
    if (isset($_GET["message"]) || $spell_correction) {    
        $message = isset($_GET["message"]) ? $_GET["message"] : ""; 
        if (isset($spell_correction)) {
            $message = $spell_correction;
        }else {
            $spell_correction = "";
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.aiforthai.in.th/ssense?text=" . urlencode($message),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Apikey: $Apikey",
            ),
        ));

        $SSENSE  = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            //echo $SSENSE ;
            echo '<script>console.log(' . json_encode($SSENSE) . ');</script>';
            $obj_SSENSE = json_decode($SSENSE ,true);
        //sentiment : ผลวิเคราะห์ความคิดเห็นว่าเป็นเชิงบวกหรือลบ
            $sentiment_score = $obj_SSENSE['sentiment']['score'];
            $sentiment_polarity = $obj_SSENSE['sentiment']['polarity'];
            $sentiment_polarity_neg = $obj_SSENSE['sentiment']['polarity-neg'];
            $sentiment_polarity_pos = $obj_SSENSE['sentiment']['polarity-pos'];
        //intention : ผลวิเคราะห์จุดประสงค์ของข้อความ
            $intention_request = $obj_SSENSE['intention']['request'];
            $intention_sentiment = $obj_SSENSE['intention']['sentiment'];
            $intention_question = $obj_SSENSE['intention']['question'];
            $intention_announcement = $obj_SSENSE['intention']['announcement'];
        //preprocess : ผลลัพธ์การจัดการข้อความก่อนวิเคราะห์
            $input = $obj_SSENSE['preprocess']['input'];
            $neg = ($obj_SSENSE['preprocess']['neg']);
            $pos = ($obj_SSENSE['preprocess']['pos']);
            $segmented = $obj_SSENSE['preprocess']['segmented'];
            $keyword = $obj_SSENSE['preprocess']['keyword'];
        //alert : array ของข้อความที่แสดงการแจ้งเตือน
            $alert = $obj_SSENSE['alert'];
        //comparative: ผลวิเคราะห์ข้อความที่มีการเปรียบเทียบแบรนด์/สินค้า
            $comparative = $obj_SSENSE['comparative'];
        //associative: ผลวิเคราะห์ข้อความที่มีความคิดเห็นต่อแบรนด์/สินค้า
            $associative = $obj_SSENSE['associative'];
            if ($sentiment_polarity_neg == 0) {
                $sentiment_polarity_neg_text = "false ";
            } elseif ($sentiment_polarity_neg == 1) {
                $sentiment_polarity_neg_text = "true";
            }
            if ($sentiment_polarity_pos == 0) {
                $sentiment_polarity_pos_text = "false ";
            } elseif ($sentiment_polarity_pos == 1) {
                $sentiment_polarity_pos_text = "true";
            }
            echo '<div class="container-fluid">
                    <div class="container mt-5 mx-auto">
                        <h4>ผลลัพธ์ SSENSE</h4>
                        <div class="card">
                            <div class="card-body">
                                <p class="card-text">Result: ผลวิเคราะห์ความคิดเห็นว่าเป็นเชิงบวกหรือลบ</p>
                                <ul class="list-group">
                                <li class="list-group-item">score : ' . $sentiment_score . '</li>
                                <li class="list-group-item">polarity : ' . $sentiment_polarity . '</li>
                                <li class="list-group-item">มีข้อความเชิงลบใช่หรือไม่ : ' . $sentiment_polarity_neg_text. '</li>
                                <li class="list-group-item">มีข้อความเชิงบวกใช่หรือไม่ : ' . $sentiment_polarity_pos_text . '</li>
                                <li class="list-group-item">ข้อความแสดงความคิดเห็น : ' . $intention_sentiment . '</li>
                                </ul>
                                <p class="card-text">Result: ผลวิเคราะห์จุดประสงค์ของข้อความ</p>
                                <ul class="list-group">
                                    <li class="list-group-item">ข้อความในเชิงร้องขอ: ' . $intention_request . '</li>
                                    <li class="list-group-item">ข้อความในเชิงคําถาม: ' . $intention_question . '</li>
                                    <li class="list-group-item">ข้อความประกาศหรือโฆษณา: ' . $intention_announcement . '</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>';
            }
    }
//ระบบตรวจสอบการแสดงความคิดเห็นที่มีลักษณะการรังแกในโลกไซเบอร์ ( Cyber Bully Expression Detector )
/*    if (isset($_GET["message"]) || $spell_correction) {    
        $message = isset($_GET["message"]) ? $_GET["message"] : ""; 
        if (isset($spell_correction)) {
            $message = $spell_correction;
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.aiforthai.in.th/bully?text=". urlencode($message),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => array(
            "Apikey: $Apikey"
        )
        ));
        
        $Cyber = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);
        
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
        //echo $Cyber;
        //echo '<script>console.log(' . json_encode($Cyber) . ');</script>';
        $obj_Cyber = json_decode($Cyber, true); // แปลงข้อมูลที่ได้รับเป็น JSON ให้กลายเป็น associative array
        
        if (is_array($obj_Cyber)) {
            $bully_word = $obj_Cyber['bully_word'][0]; // ใส่ index 0 เพื่อให้ได้ค่าเป็น string เดียว
            $bully_type = $obj_Cyber['bully_type'][0]; // ใส่ index 0 เพื่อให้ได้ค่าเป็น integer เดียว
        } else {
            echo "Error: Invalid response format";
        }
            if ($bully_type == 0) {
                $bully_type_text = "ข้อความทั่วไปซึ่งไม่มีลักษณะของการรังแก";
            } elseif ($bully_type == 1) {
                $bully_type_text = "ข้อความที่มีการกล่าวถึงบุคลิก รูปลักษณ์ และ พฤติกรรม";
            } elseif ($bully_type == 2) {
                $bully_type_text = "ข้อความที่เป็นคำด่า คำหยาบคาย";
            } elseif ($bully_type == 3) {
                $bully_type_text = "ข้อความคุกคามเกี่ยวกับทางเพศ";
            } elseif ($bully_type == 4) {
                $bully_type_text = "ข้อความที่กล่าวถึง เชื้อชาติ กำเนิด และการใช้ชีวิต";
            } elseif ($bully_type == 5) {
                $bully_type_text = "ข้อความที่กล่าวถึงสติปัญญา และความฉลาด";
            } elseif ($bully_type == 6) {
                $bully_type_text = "ข้อความที่มีการใช้ถ้อยคำรุนแรง การข่มขู่";
            } else {
                $bully_type_text = "ไม่สามารถตรวจหาผลลัพธ์ได้";
            }
            echo '<div class="container-fluid">
            <div class="container mt-5 mx-auto">
                <h4>ผลลัพธ์ Cyber Bully</h4>
                <div class="card">
                    <div class="card-body">
                        <p class="card-text">Result: </p>
                        <ul class="list-group">
                        <li class="list-group-item">ผลลัพธ์ : ' . $bully_word    . '</li>
                        <li class="list-group-item">ประเภท : ' . $bully_type_text    . '</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>';
        }
    }   */
?>
</body>
<footer class="bg-light py-3 ">
  <div class="container ">
    <div class="row">
      <div class="col-md-6 text-center">
        <p>© 2023 Kanansak Sujaree 163404140001</p>
      </div>
    </div>
  </div>
</footer>
</html>