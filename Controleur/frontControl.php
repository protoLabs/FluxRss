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
use modele\AdminModele;

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

        
        try{
            $action=$_POST['action'];
            $TabAdmin=array('ajouterFlux', 'supprimerFlux', 'modifierFlux');
            $a=AdminModele.isAdmin();
            if(inArray($TabAdmin, $action)){
                if($a==null){
                    $_POST['action']="connecter";
                    new userControl();
                }
                else{
                    new adminControl();
                }
            }
        } catch (Exception $ex) {
            $TabErreur = array($ex->getMessage());
            
                echo $template->render(array(
                        'Erreur' => $TabErreur
                    ));           
        }
        


      

        //TODO Dispatcher entre les controleurs => switch sur la var action


    }


}



