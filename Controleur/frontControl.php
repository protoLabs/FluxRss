<?php
/**
 * Created by PhpStorm.
 * User: Theo
 * Date: 10/12/2014
 * Time: 21:30
 */
namespace controleur;
use config\SplClassLoader;
use controleur\adminControl;
use controleur\userControl;
use metier\Flux;
use utilitaires\XMLParser;

require_once(__DIR__ ."/../Config/config.php");

class frontControl{


    public function __construct()
    {

        global $path;

        require_once ($path."Config/spLClassLoader.php");


        $myAutoLoader = new SplClassLoader("config",$path);
        $myAutoLoader->register();
        $myAutoLoader = new SplClassLoader("controleur", $path);
        $myAutoLoader->register();
        $myAutoLoader = new SplClassLoader("metier",$path);
        $myAutoLoader->register();
        $myAutoLoader = new SplClassLoader("modele",$path);
        $myAutoLoader->register();
        $myAutoLoader = new SplClassLoader("utilitaires",$path);
        $myAutoLoader->register();
        $myAutoLoader = new SplClassLoader("Twig",$path."lib/");
        $myAutoLoader->setNamespaceSeparator("_");
        $myAutoLoader->register();




        switch($_REQUEST['action']){
            case 'afficherNews':
            case 'afficherFlux':
            case 'afficherNewsDe':
            case null:
                $userCtr = new userControl();
                break;

            case 'ajouterFlux':
            case 'supprimerFlux':
            case 'modifierFlux':
            case 'rafraichirNews':
                $admin = new adminControl();
                break;
        }
      

        //TODO Dispatcher entre les controleurs => switch sur la var action


    }


}



