<?php

    $city = 'Dhaka';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // collect value of input field
        $name = $_POST['city'];

        if (!empty($name)) {
            $city = $name;
        } 
        $city = ucfirst($city);
    }
   
    $current_url = 'https://api.openweathermap.org/data/2.5/weather?q='.$city.'&appid=1263aad1b7a60e9ff33ae7b49fa42568';
    $cur_json_data = file_get_contents($current_url);
    $cur_weather_data = json_decode($cur_json_data);
   
    $url = 'https://api.openweathermap.org/data/2.5/forecast?q='.$city.'&appid=1263aad1b7a60e9ff33ae7b49fa42568';
    $json_data  = file_get_contents($url);
    $response_data = json_decode($json_data);

    // All the users data exists in 'data' object
    $forecastData = $response_data->list;
    // It cuts the long data into small & select only the first 5 records.
    $forecastData2 = array_slice($forecastData, 1, 5);
   
    //$forecastData[0]->dt_txt

    function getDateFormat(string $date){
        $date = explode(' ',$date)[0];
        $date_timestamp = strtotime($date);
        $newDate = date('l, F d',strtotime($date));
        return $newDate;
    }

    function getCelsius(float $kelvin){
       return $kelvin - 273.15.' C';
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather App</title>
    <!-- bootstrap cdn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- bootstrap icon cdn -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <!-- stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-gray-light">
    <div class="container">
        <div class="bg-white my-5 row g-0 border-radius">
            <div class="col-12 col-lg-6 bg-image">
                <nav
                    class="bg-transparent text-white d-flex justify-content-between align-items-center px-2 px-lg-5 py-3 py-md-5">
                    <span class="fs-4 fw-bold">Weather App</span>
                    <span class="opacity-sm rounded-3 px-1 px-lg-3 py-1">
                        <i class="bi bi-geo-alt-fill"></i> 
                        <?php echo $city.', '.$cur_weather_data->sys->country;?>
                    </span>
                </nav>
                <div class="my-auto">
                    <h3 class="text-center text-white">Know Before You Go</h3>
                    <hr class="w-25 mx-auto border border-3 border-white rounded-pill">
                    <form action="index.php" method="post" class="d-flex w-75 pt-3 pb-5 pb-lg-0 mx-auto">
                        <input type="text" name="city" class="form-control opacity-lg border-0" id="exampleInputCity"
                            placeholder="Enter city name">
                        <button type="submit" class="btn btn-primary ms-3">Submit</button>
                    </form>                    
                </div>
            </div>

            <!-- display data -->           
            <div class="col-12 col-lg-6 px-4 py-5">
                <h2 class="fw-bold mb-3"><?php echo $city.', '.$cur_weather_data->sys->country;?></h2>
                <div class="row gx-5 shadow-lg rounded-3 mx-1 py-3">
                    <div class="col-6 border-end border-2">
                        <p class="fs-1 fw-bold mb-1">
                            <?php  echo(getCelsius($cur_weather_data->main->temp));?>
                        </p>
                        <p class="fs-3 mb-0">
                            <?php  echo($cur_weather_data->weather[0]->main);?>
                        </p>
                        <p><?php
                           echo getDateFormat($forecastData[0]->dt_txt).', 2023';
                        ?></p>
                    </div>
                    <div class="col-6">
                        <p>
                            Feels like: <?php  echo(getCelsius($forecastData[0]->main->feels_like));?>
                        </p>
                        <p>
                            Min-temperature:
                            <?php  echo(getCelsius($forecastData[0]->main->temp_min));?>
                        </p>
                        <p>Max-temperature:
                        <?php  echo(getCelsius($forecastData[0]->main->temp_max));?>
                        </p>
                        <p>Pressure:
                        <?php  echo($forecastData[0]->main->pressure);?>
                        </p>
                        <p>Humidity:
                        <?php  echo($forecastData[0]->main->humidity);?>
                        </p>
                        <p>Wind speed: 
                        <?php  echo($forecastData[0]->wind->speed);?>
                        </p>
                    </div>
                </div>
                <h4 class="fw-bold mt-4 my-3">Daily</h4>
                <div class="scroll d-flex">
                    <?php  
                        foreach ($forecastData as $forecast) {
                            echo '
                                <div class="shadow rounded-3 p-3 mx-2">
                                    <p class="fs-2 fw-bold mb-1">'.getCelsius($forecast->main->temp).'</p>
                                    <p class="fs-3 mb-0">'.$forecast->weather[0]->main.'</p>
                                    <small>'.getDateFormat($forecast->dt_txt).'</small>
                                </div>
                            ';
                        }
                    ?>
                </div>
            </div>

        </div>
    </div>

    <!-- bootstrap cdn -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>