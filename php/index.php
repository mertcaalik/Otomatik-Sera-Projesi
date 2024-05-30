<!DOCTYPE HTML>
<html>
  <head>
    <title>AKILLI SERA PROJESİ</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link rel="icon" href="data:,">
    <style>
      html {font-family: Arial; display: inline-block; text-align: center;}
      p {font-size: 1.2rem;}
      h4 {font-size: 0.8rem;}
      body {margin: 0;}
      .topnav {overflow: hidden; background-color: #0c6980; color: white; font-size: 1.2rem;}
      .content {padding: 5px; }
      .card {background-color: white; box-shadow: 0px 0px 10px 1px rgba(140,140,140,.5); border: 1px solid #0c6980; border-radius: 15px;}
      .card.header {background-color: #0c6980; color: white; border-bottom-right-radius: 0px; border-bottom-left-radius: 0px; border-top-right-radius: 12px; border-top-left-radius: 12px;}
      .cards {max-width: 700px; margin: 0 auto; display: grid; grid-gap: 2rem; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));}
      .reading {font-size: 1.3rem;}
      .packet {color: #bebebe;}
      .temperatureColor {color: #fd7e14;}
      .humidityColor {color: #1b78e2;}
      .statusreadColor {color: #702963; font-size:12px;}
      .LEDColor {color: #183153;}
      
      .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
      }

      .switch input {display:none;}

      .sliderTS {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #D3D3D3;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 34px;
      }

      .sliderTS:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: #f7f7f7;
        -webkit-transition: .4s;
        transition: .4s;
        border-radius: 50%;
      }

      input:checked + .sliderTS {
        background-color: #00878F;
      }

      input:focus + .sliderTS {
        box-shadow: 0 0 1px #2196F3;
      }

      input:checked + .sliderTS:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
      }

      .sliderTS:after {
        content:'OFF';
        color: white;
        display: block;
        position: absolute;
        transform: translate(-50%,-50%);
        top: 50%;
        left: 70%;
        font-size: 10px;
        font-family: Verdana, sans-serif;
      }

      input:checked + .sliderTS:after {  
        left: 25%;
        content:'ON';
      }

      input:disabled + .sliderTS {  
        opacity: 0.3;
        cursor: not-allowed;
        pointer-events: none;
      }
    </style>
  </head>
  
  <body>
    <div class="topnav">
      <h3>SERA KONTROL</h3>
    </div>
    
    <br>

    <div class="content">
      <div class="cards">
        
        <div class="card">
          <div class="card header">
            <h3 style="font-size: 1rem;">SICAKLIK VE NEM</h3>
          </div>
          
          <h4 class="temperatureColor"><i class="fas fa-thermometer-half"></i> SICAKLIK</h4>
          <p class="temperatureColor"><span class="reading"><span id="ESP32_01_Temp"></span> &deg;C</span></p>
          <h4 class="humidityColor"><i class="fas fa-tint"></i> NEM</h4>
          <p class="humidityColor"><span class="reading"><span id="ESP32_01_Humd"></span> &percnt;</span></p>
          
          <p class="statusreadColor"><span> DHT11 SENSÖR DURUMU : </span><span id="ESP32_01_Status_Read_DHT11"></span></p>
        </div>
        
        <div class="card">
          <div class="card header">
            <h3 style="font-size: 1rem;">TORPAK NEM</h3>
          </div>
          <br>
          <br>
          <br>
          <h4 class="humidityColor"><i class="fas fa-tint"></i> NEM</h4>
          <p class="humidityColor"><span class="reading"><span id="ESP32_01_HUMD"></span> &percnt;</span></p>
          
        </div>  
        
      </div>
    </div>
    
    <br>
    
    <div class="content">
      <div class="cards">
        
      <div class="card">
  <div class="card header">
    <h3 style="font-size: 1rem;">FAN KONTROL</h3>
  </div>
          <br>
          <br>
  <form id="referans-form" action="fanreferans.php" method="POST">
            <label for="referans">Referans Sayısı:</label>
            <input type="number" id="referans" name="referans" required>
            <br>
            <br>
            <button type="submit">Kaydet</button>
        </form>
          <br>
          <br>
</div>


        <div class="card"> <!-- Su pompasının çalışması için gereken toprak nem referans değerinin Yazılacağı yer -->
          <div class="card header">
            <h3 style="font-size: 1rem;">SU KONTROL</h3>
          </div>
          <br>
          <br>
  <form id="referans-form" action="sureferans.php" method="POST">
            <label for="referans">Referans Sayısı:</label>
            <input type="number" id="referans" name="referans" required>
            <br>
            <br>
            <button type="submit">Kaydet</button>
        </form>
          <br>
          <br>
          
        </div>  
        
      </div>
    </div>
    
    <br>
    
    <div class="content">
      <div class="cards">
        <div class="card header" style="border-radius: 15px;">
            <h3 style="font-size: 0.7rem;"> DHT11'den ALINAN SON VERİ [ <span id="ESP32_01_LTRD"></span> ]</h3>
            <button onclick="window.open('kayittablosu.php', '_blank');">SICAKLIK VE NEM KAYIT TABLOSU</button>
            <h3 style="font-size: 0.7rem;"></h3>
        </div>
      </div>
    </div>

    <script>
            document.getElementById("ESP32_01_HUMD").innerHTML = "NN";

            Get_Data("1");
            
            setInterval(myTimer, 5000);
            
            function myTimer() {
              Get_Data("1");
            }

            function Get_Data(id) {
    var xhttp;
    if (window.XMLHttpRequest) {
        xhttp = new XMLHttpRequest();
    } else {
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            const myObj = JSON.parse(this.responseText);
            if (myObj.id == "1") {
                document.getElementById("ESP32_01_HUMD").innerHTML = myObj.humidity; // Nem değerine yüzde işareti ekledim
            }
        }
    };
    xhttp.open("POST", "topraknem.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id=" + id);
}
// Belirli aralıklarla veri çekme
setInterval(function() {
    Get_Data("1");
}, 5000); // 5 saniyede bir güncelle

          
    </script>
    
    <script>
      document.getElementById("ESP32_01_Temp").innerHTML = "NN"; 
      document.getElementById("ESP32_01_Humd").innerHTML = "NN";
      document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = "NN";
      document.getElementById("ESP32_01_LTRD").innerHTML = "NN";
      
      Get_Data("esp32_01");
      
      setInterval(myTimer, 5000);
      
      
      function myTimer() {
        Get_Data("esp32_01");
      }
      
      function Get_Data(id) {
				if (window.XMLHttpRequest) {
          xmlhttp = new XMLHttpRequest();
        } else {
          xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            const myObj = JSON.parse(this.responseText);
            if (myObj.id == "esp32_01") {
              document.getElementById("ESP32_01_Temp").innerHTML = myObj.temperature;
              document.getElementById("ESP32_01_Humd").innerHTML = myObj.humidity;
              document.getElementById("ESP32_01_Status_Read_DHT11").innerHTML = myObj.status_read_sensor_dht11;
              document.getElementById("ESP32_01_LTRD").innerHTML = "Time : " + myObj.ls_time + " | Date : " + myObj.ls_date + " (dd-mm-yyyy)";
            }
          }
        };
        xmlhttp.open("POST","verial.php",true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("id="+id);
			}

            
    </script>
  </body>
</html>