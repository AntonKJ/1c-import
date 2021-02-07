<?php
//
//"TRUNCATE ` city `"
// TRUNCATE `import`;
// TRUNCATE `offers`;
// TRUNCATE `prices`;
// DROP TABLE `goods`;
//

include 'DB.php';
use Database\DB;

    $dbClass = new DB();
    $db = $dbClass->getDb();

    $count_files_offers = 7; // Количество файлов предложений отсканировать
    $count_files_import = 7; // Количество файлов импорта отсканировать
    $count_offers_in_to_file = 50; // Количество предложений в файле отсканировать

    $status_city = 1;
    $strtotime = date('Y-m-d H:i:s');
    $LIMIT = $count_offers_in_to_file;

    // OFFERS XMLS
    for($io=0; $io<$count_files_offers; $io++) {

        $city_id_1c_xml = '';
        $status = $status_city;

        if (!file_exists("data/offers".$io."_1.xml")){
            echo "data/offers".$io."_1.xml - File dos not exist!" ;
            break;
        }

        //OPEN
        $xml = simplexml_load_file("data/offers".$io."_1.xml");
        //READ
        $i = 0;

        // Сначала город
        foreach ($xml->ПакетПредложений as $city) {

            $strtotime = date('Y-m-d H:i:s');

            $id = (string)$city->ИдКлассификатора ?? '';
            preg_match( '/.*\((.*)\).*/i', $city->Наименование, $matches);
            $reg_name = trim($matches[1]);
            $name = htmlspecialchars(stripcslashes((string)$reg_name)) ?? '';
            $id1c = (string)$city->ИдКлассификатора ?? '';
            $status = $status;
            $city_id = $id ?? '';
/*                  var_dump($name);
                  var_dump($id1c);*/

            $city_id_1c_xml = $city_id;

            $result = $db->query("SELECT COUNT(*) FROM `city` Where id = '{$city_id}'");

            if ((int)$result->fetchColumn() > 0) {
                $idpt = $db->prepare("UPDATE `city` SET id=:id,name=:name,id1c=:id1c,status=:status,datetime=:datetime Where id= '{$city_id}'");
            } else {
                $idpt = $db->prepare("INSERT INTO `city` (id,name,id1c,status,datetime) VALUES (:id,:name,:id1c,:status,:datetime)");
            }
            $idpt->bindParam(':id', $id);
            $idpt->bindParam(':name', $name);
            $idpt->bindParam(':id1c', $id1c);
            $idpt->bindParam(':status', $status);
            $idpt->bindParam(':datetime', $strtotime);
            $idpt->execute();
        }

        // Товары данного города
        foreach ($xml->ПакетПредложений->Предложения->Предложение as $item) {

            $strtotime = date('Y-m-d H:i:s');

            $id_1c = (string)$item->Ид ?? '';
            $name_item = (string)$item->Наименование  ?? '';
            $articul = (string)$item->Артикул  ?? '';
            $base_id = json_encode($item->БазоваяЕдиница)  ?? '';
            $city_id = $city_id_1c_xml  ?? '';
            $code_item = (string)$item->Код ?? '' ;
            $quantity = (string)$item->Количество ?? '' ;
            $datetime = $strtotime;

            $result = $db->query("SELECT COUNT(*) FROM `offers` Where code = '" . $code_item . "' and city_id = '{$city_id}'");

            if ((int)$result->fetchColumn() > 0) {
                $idpo = $db->prepare("UPDATE `offers` SET id1c=:id1c, articul=:articul,
     naimenovanie=:naimenovanie,
     base_id=:base_id,
     city_id=:city_id,
     code=:code,
     quantity=:quantity,
     datetime=:datetime Where code = '{$code_item}' and city_id = '{$city_id}'");

            } else {
                $query = "INSERT INTO offers (id1c, articul, naimenovanie, base_id, city_id, code, quantity, datetime) 
        VALUES ( :id1c, :articul, :naimenovanie, :base_id, :city_id, :code, :quantity, :datetime)";
                $idpo = $db->prepare($query);
            }

            $id_1c_price_offer = $id_1c;
            $idpo->bindParam(':id1c', $id_1c);
            $idpo->bindParam(':articul', $articul);
            $idpo->bindParam(':naimenovanie', $name_item);
            $idpo->bindParam(':base_id', $base_id);
            $idpo->bindParam(':city_id', $city_id);
            $idpo->bindParam(':code', $code_item);
            $idpo->bindParam(':quantity', $quantity);
            $idpo->bindParam(':datetime', $datetime);
            $idpo->execute();

            foreach ($xml->ПакетПредложений->Предложения->Предложение->Цены->Цена as $prices) {

                $strtotime = date('Y-m-d H:i:s');

                $price_type_id = (string)$prices->ИдТипаЦены  ?? '';
                $unit_price = (string)$prices->ЦенаЗаЕдиницу  ?? '';
                $currency = (string)$prices->Валюта  ?? '';
                $performance = (string)$prices->Представление  ?? '';
                $city_id = $city_id_1c_xml  ?? '';
                $с_price = (string)$prices->Коэффициент  ?? '';
                $id_offer = (string)$id_1c_price_offer ?? '';


                $result = $db->query("SELECT COUNT(*) FROM `prices` Where id_offer = '" . $id_offer . "' and price_type_id = '" . $price_type_id . "'");

                if ((int)$result->fetchColumn() > 0) {
                    $idpp = $db->prepare("UPDATE prices SET price_type_id=:price_type_id, unit_price=:unit_price,
     currency=:currency,
     performance=:performance,
     city_id=:city_id,
     coffee=:coffee,
     id_offer=:id_offer,
     datetime=:datetime Where price_type_id = '{$price_type_id}'");
                } else {
                    $query = "INSERT INTO prices (price_type_id, unit_price, currency, performance, city_id, coffee, id_offer, datetime) 
VALUES ( :price_type_id, :unit_price, :currency, :performance, :city_id, :coffee, :id_offer, :datetime)";
                    $idpp = $db->prepare($query);
                }

                $idpp->bindParam(':price_type_id', $price_type_id);
                $idpp->bindParam(':unit_price', $unit_price);
                $idpp->bindParam(':currency', $currency);
                $idpp->bindParam(':performance', $performance);
                $idpp->bindParam(':city_id', $city_id);
                $idpp->bindParam(':coffee', $с_price);
                $idpp->bindParam(':id_offer', $id_offer);
                $idpp->bindParam(':datetime', $strtotime);
                $idpp->execute();

            }

            $i++;
            if ($i > $LIMIT) {
                break;
            }
        }
    }

    // IMPORT XMLS
    for($ii=0; $ii<$count_files_import; $ii++) {

        $city_id_1c_xml = '';
        $status = $status_city;

        if (!file_exists("data/import".$ii."_1.xml")){
            echo "data/import".$ii."_1.xml - File dos not exist!" ;
            break;
        }

        //OPEN
        $xml = simplexml_load_file("data/import".$ii."_1.xml");
        //READ
        $i = 0;

        // Сначало город
        foreach ($xml->Каталог as $city) {

            $strtotime = date('Y-m-d H:i:s');

            $id = (string)$city->Ид ?? '';
            $name = htmlspecialchars(stripcslashes((string)$city->Наименование)) ?? '';
            $id1c = (string)$city->ИдКлассификатора ?? '';
            $status = $status;

            $city_id = $id ?? '';
            /*        var_dump($id);
                    var_dump($name);
                    var_dump($id1c);*/

            $city_id_1c_xml = $city_id;

            $result = $db->query("SELECT COUNT(*) FROM `city` Where id = '{$city_id}'");

            if ((int)$result->fetchColumn() > 0) {
                $idpt = $db->prepare("UPDATE `city` SET id=:id,name=:name,id1c=:id1c,status=:status,datetime=:datetime Where id= '{$city_id}' ");
            } else {
                $idpt = $db->prepare("INSERT INTO `city` (id,name,id1c,status,datetime) VALUES (:id,:name,:id1c,:status,:datetime)");
            }
            $idpt->bindParam(':id', $id);
            $idpt->bindParam(':name', $name);
            $idpt->bindParam(':id1c', $id1c);
            $idpt->bindParam(':status', $status);
            $idpt->bindParam(':datetime', $strtotime);
            $idpt->execute();

            //var_dump($idpt->execute());
        }

        // Товары данного города
        foreach ($xml->Каталог->Товары->Товар as $item) {

            $strtotime = date('Y-m-d H:i:s');

            $id_1c = (string)$item->Ид ?? '';
            $name_item = (string)$item->Наименование  ?? '';
            $shortcode = (string)$item->Штрихкод  ?? '';
            $articul = (string)$item->Артикул  ?? '';
            $base_id = json_encode($item->БазоваяЕдиница)  ?? '';
            $groups_import = json_encode($item->Группы)  ?? '';
            $description  = (string)$item->Описание  ?? '';
            $valuepropertys = json_encode($item->ЗначенияСвойств)  ?? '';
            $tax_rates  = json_encode($item->СтавкиНалогов)  ?? '';
            $value_of_requisites = json_encode($item->ЗначенияРеквизитов) ?? '' ;
            $code_item = (int)$item->Код ?? '' ;
            $city_id = $city_id_1c_xml ?? '';
            $weight_item = (string)$item->Вес ?? '';
            $stamps = (string)$item->Марки ?? '';
            $expected_arrival = (string)$item->ОжидаемыйПриход ?? '';
            $english_title  = (string)$item->АнглийскоеНазвание ?? '';
            $chinese_title = (string)$item->КитайскоеНазвание ?? '';
            $additional_articules = json_encode($item->ДополнительныеАртикулы) ?? '';
            $date_of_expected_arrival = json_encode($item->ДатаОжидаемогоПрихода) ?? '';
            $comment = (string)$item->Комментарий ?? '';
            $datetime = $strtotime;

            $result = $db->query("SELECT COUNT(*) FROM `import` Where code = '" . (string)$item->Код . "'  and city_id = '{$city_id}'");

            if ((int)$result->fetchColumn() > 0) {
                $idpi = $db->prepare("UPDATE `import` SET id1c=:id1c, shortcode=:shortcode,
 articul=:articul,
 naimenovanie=:naimenovanie,
 base_id=:base_id,
 groups_import=:groups_import,
 description=:description,
 valuepropertys=:valuepropertys,
 tax_rates=:tax_rates,
 value_of_requisites=:value_of_requisites,
 code=:code,
 city_id=:city_id,
 weight=:weight,
 stamps=:stamps,
 expected_arrival=:expected_arrival,
 english_title=:english_title,
 chinese_title=:chinese_title,
 additional_articules=:additional_articules,
 date_of_expected_arrival=:date_of_expected_arrival,
 comment=:comment,
 datetime=:datetime Where code = '{$code_item}' and city_id = '{$city_id}'");

            } else {
                $query = "INSERT INTO import (id1c, shortcode, articul, naimenovanie, base_id, groups_import, description, valuepropertys, tax_rates, value_of_requisites, code, city_id, weight,  stamps , expected_arrival, english_title, chinese_title, additional_articules, date_of_expected_arrival, comment, datetime) 
    VALUES ( :id1c, :shortcode, :articul, :naimenovanie, :base_id, :groups_import, :description, :valuepropertys, :tax_rates, :value_of_requisites, :code, :city_id, :weight, :stamps , :expected_arrival, :english_title, :chinese_title, :additional_articules, :date_of_expected_arrival, :comment, :datetime)";
                $idpi = $db->prepare($query);
            }

            $idpi->bindParam(':id1c', $id_1c);
            $idpi->bindParam(':shortcode', $shortcode);
            $idpi->bindParam(':articul', $articul);
            $idpi->bindParam(':naimenovanie', $name_item);
            $idpi->bindParam(':base_id', $base_id);
            $idpi->bindParam(':groups_import', $groups_import);
            $idpi->bindParam(':description', $description);
            $idpi->bindParam(':valuepropertys', $valuepropertys);
            $idpi->bindParam(':tax_rates', $tax_rates);
            $idpi->bindParam(':value_of_requisites', $value_of_requisites);
            $idpi->bindParam(':code', $code_item);
            $idpi->bindParam(':city_id', $city_id);
            $idpi->bindParam(':weight', $weight_item);
            $idpi->bindParam(':stamps', $stamps);
            $idpi->bindParam(':expected_arrival', $expected_arrival);
            $idpi->bindParam(':english_title', $english_title);
            $idpi->bindParam(':chinese_title', $chinese_title);
            $idpi->bindParam(':additional_articules', $additional_articules);
            $idpi->bindParam(':date_of_expected_arrival', $date_of_expected_arrival);
            $idpi->bindParam(':comment', $comment);
            $idpi->bindParam(':datetime', $datetime);
            $idpi->execute();


            $i++;
            if ($i > $LIMIT) {
                break;
            }
        }
    }

    // CREATE VIEW FOR GOODS
    $query = "CREATE OR REPLACE VIEW goods AS SELECT ofs.id, ofs.code as code, ofs.naimenovanie,
     CONCAT((SELECT count(*) FROM offers ofs1 Where ofs1.city_id = imp.city_id and ofs1.code=imp.code),
     (SELECT count(*) FROM import imp1 Where imp1.city_id = imp.city_id and imp1.code=imp.code)) as count_in_city,
     (SELECT pc.unit_price FROM prices pc Where pc.city_id=imp.city_id and pc.id_offer=ofs.id1C LIMIT 1) as price_in_city,
      imp.city_id, imp.weight, ofs.id1c 
                                                FROM offers ofs,import imp
                                                WHERE ofs.code=imp.code
                                                ";
    $db->beginTransaction();
    $db->exec($query);
    $result = $db->commit();
    $status_view = '';
    if ($result) {
        print_r("Create view OK!
        ");
        $status_view = true;
    } else {
        print_r("Create view FALSE
        ");
        $status_view = false;
        $db->rollBack();
    }

if ($status_view) {
    echo "Data import completed successfully!
";
}
