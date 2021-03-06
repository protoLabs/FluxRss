<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace utilitaires;
use Exception;
use PDO;
use PDOException;

/**
 * Description of BD
 *
 * @author Charlotte DELAIN, Théo DEPRESLE
 */
class BD {
    private $pdo = null;

    
    private function __construct() {
        try{
            global $db_host,$db_name, $db_password,$db_user;
            $params = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET Names UTF8");
            $this->pdo = new PDO("mysql:host=".$db_host.";dbname=".$db_name,$db_user,$db_password,$params);
            //Léve une exception en cas d'erreur
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (Exception $ex) {
            throw new Exception("Erreur connexion base de donnée");
            //die("Erreur connexion");
        }
        
    }

    private static $instance = null;
    
    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new self;
        }
        return self::$instance;
    }


    public function lecture($req, $params = array()){
       try{
            $requete = $this->pdo->prepare($req);
            $i=0;
            foreach($params as $param){
                $i++;
                $requete->bindParam($i, $param[0],$param[1]);
            }
            $requete->execute();
            $result = $requete->fetchall();
            $requete->closeCursor();
            $requete = null;
            return $result;
       }
       catch (PDOException $e){
            throw new Exception("Erreur de lecture BD, requete invalide ?");
       }
   }
   
   public function requete($req, $params){
       try{
            $requete = $this->pdo->prepare($req);
            $i=0;
            foreach($params as $param){
                $i++;
                $requete->bindParam($i, $param[0],$param[1]);
            }
            $requete->execute();
            $requete->closeCursor();
            $requete=null;
       }
        catch (PDOException $e){
            $tabErreur[] = "Erreur de requete BD";
        }
       return;
   }
}
