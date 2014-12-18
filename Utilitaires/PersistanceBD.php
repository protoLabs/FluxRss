<?php
/**
 * Created by PhpStorm.
 * User: Theo
 * Date: 10/12/2014
 * Time: 21:50
 */

class PersistanceBD extends Persistance {

    public function ajouterFlux($name, $link)
    {
        $bd = BD::getInstance();
        $params = array(
            '1' => array($name, PDO::PARAM_STR),
            '2' => array($link, PDO::PARAM_STR),
        );
        $bd->requete("INSERT INTO tflux VALUES(NULL, ?, ?)",$params);
    }

    public function getNews($page)
    {
        $bd = BD::getInstance();
        $params = array(
            '1' => array($page, PDO::PARAM_INT),
            '2' => array(($page -1)*50, PDO::PARAM_INT),
        );
        //Requete avec jointure, pour recuperer les images
        $result = $bd->lecture("SELECT * FROM tnews ORDER BY datePub LIMIT ?, ?",$params);
        $resultFlux = $bd->lecture("SELECT ");
        $tabNews = array();
        foreach($result as $news){
            $tabNews[] = NEWS::newNews($news['id'],$news[''],,);
        }

        return $tabNews;

    }
}