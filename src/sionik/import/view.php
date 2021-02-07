<?php
namespace ViewSource;

require_once 'DB.php';
use Database\DB;

/**
 * Class CView
 * @package ViewSource
 */
class CView
{
    /**
     * CView constructor.
     * @param $s
     */
    public function __construct ($s)
    {
        $dbClass = new DB();
        $db = $dbClass->getDb();

        // CREATE VIEW FOR GOODS
        $query = "CREATE OR REPLACE VIEW goods AS SELECT ofs.key_map as id, ofs.code as code, ofs.naimenovanie,
     ((SELECT count(*) FROM offers ofs1 Where ofs1.city_id = imp.city_id and ofs1.code=imp.code) +
     (SELECT count(*) FROM import imp1 Where imp1.city_id = imp.city_id and imp1.code=imp.code)) as count_in_city,
     (SELECT pc.unit_price FROM prices pc Where pc.city_id=ofs.city_id and pc.id_offer=ofs.id1C GROUP BY price_type_id LIMIT 1) as price_in_city,
      imp.city_id, imp.weight, ofs.id1c 
                                                FROM offers ofs,import imp
                                                WHERE ofs.code=imp.code 
                                                GROUP BY code, city_id
                                                ";
        $db->beginTransaction();
        $db->exec($query);
        $result = $db->commit();
        $status_view = '';
        if ($result) {
            print_r("Create view OK!\n");
            $status_view = true;
        } else {
            print_r("Create view FALSE\n");
            $status_view = false;
            $db->rollBack();
        }

        return $status_view;
    }
}

parse_str($argv[1]);

if ($create = 'ok') {
    new CView(true);
}