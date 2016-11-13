<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Получение адреса</title>
</head>
<body>


<form action="" method="GET">

    <p>
       Адрес клуба: <input type="text" name="address" >

        <input type="submit" value="Получить">
    </p>
</form>

</body>
</html>


<?php

if (!empty($_GET["address"]))
{  
$address = "Moscow ".$_GET["address"]; 

 /* функция для различных запросов к Яндексу */
function request($address,$kind,$results,$cikl)
{

 $response = json_decode(file_get_contents("https://geocode-maps.yandex.ru/1.x/?geocode=$address&format=json$kind$results"));


    if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)    //на всякий случай пусть каждый раз проверяет, вдруг с Яндексом что случится)
        { 


             if ($address == "Moscow ".$_GET["address"] //
                && $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos!="37.617635 55.755814"
                )  
             // координаты нулевого километра, если искать Воскресенские ворота, будут другие
                    {
                        $date[0] = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
                        return $date;
                    }
             elseif ($response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos!="37.617635 55.755814")
                    {       
                         for ($x = 0; $x < $cikl; $x++)
                              {
                                echo $response->response->GeoObjectCollection->featureMember[$x]->GeoObject->name;
                                $date[]=$response->response->GeoObjectCollection->featureMember[$x]->GeoObject->name; // по тз нужен массив, все результаты сохраняем туда
                                echo "<br>";
                              }
                     return $date;  
                    }
                   else   echo " в Москве нет такой  улицы ";

   
            
        }
   else
        {
        echo " Ошибка во время обработки запроса";
        }
     


}


/* геокодирование*/
 
$date=request($address,'','&results=1',1);


/* по координатам осуществляем поиск  */


    if (!empty($date[0])) 
    {


    $address=$date[0];
   
    //Улица

    $date= array_merge($date, request($address,'&kind=street','&results=1',1));

    //Район

    $date= array_merge($date, request($address,'&kind=district','&results=1',1));

    //Получаем станции метро

    $date= array_merge($date, request($address,'&kind=metro','&results=5',5));

    }


}
