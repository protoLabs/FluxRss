<?php

namespace controleur;

use modele\AdminModele;
use modele\FluxModele;
use modele\ParametreModele;
use utilitaires\Validation;
use Twig_Autoloader;
use Twig_Environment;
use Twig_Loader_Filesystem;
use utilitaires\Boniche;

class adminControl{
    private $twig;
    private $fluxModele;
    private $paramModele;
    private $admin;
    
    public function __construct(){
        global $path;
        
        $this->fluxModele = new FluxModele();
        $this->admin = new AdminModele();
        $this->paramModele = new ParametreModele();
        
        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem('Vue/Templates');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => false
        ));
        
        $action = (isset($_REQUEST['action'])?$_REQUEST['action']:null);
        $a=$this->admin->isAdmin();

        if($a == null){
            switch($action){
                case 'pageConnexion':
                    $this->AfficherPageConnexion();
                    break;
                case 'connexion':
                    $this->connexion();
                    break;
                case 'deconnexion':
                    $this->deconnexion();
                    break;
                default:
                    header("Location: .?action=pageConnexion");
            }
        }
        else{
            switch($action){
                case 'pageConnexion':
                case 'connexion':
                    header("Location: .");
                    break;
                case 'supprimerFlux':
                    $this->supprimerFlux();
                    break;
                case 'deconnexion':
                    $this->deconnexion();
                    break;
                case 'ajouterFlux':
                    $this->ajouterFlux();
                    break;
                case 'setNbAffiche':
                    $this->setNbAffiche();
                    break;

            }
        }

    }

    private function addFlux()
    {
        Validation::existe($_REQUEST['link']);
        Validation::URLValid($_REQUEST['link']);

        $link = $_REQUEST['valid'];

        $fluxMod = new FluxModele();
        $fluxMod->ajouterFlux($link);
    }

    private function AfficherPageConnexion(){
        $fluxs = $this->fluxModele->getPageFlux(1);
        $template = $this->twig->loadTemplate('pageConnexion.twig');
        $adminco = $this->admin->isAdmin();
        
        echo $template->render(array(
            'Fluxs' => $fluxs,
            'msg' => Boniche::NettoyageLOGIN($_REQUEST['msg']),
            'Admin' => $adminco
        ));
    }

    private function connexion(){
        $resultat = $this->admin->connecter($_POST['login'],$_POST['mdp']);
        if($resultat){
            header("Location: .");
        }
        else{
            header("Location: .?action=pageConnexion&msg=Identifiants Inconnus");
        }
    }
    
    private function deconnexion(){
        $this->admin->deconnecter();
        header("Location: .");
    }

    private function ajouterFlux()
    {
        $lien = $_REQUEST['url'];
        $this->fluxModele->ajouterFlux($lien);
        header("Location: .?action=afficherFlux");
    }

    private function supprimerFlux()
    {
        $id = $_REQUEST['id'];
        Validation::existe($id);
        Validation::isNumber($id);
        $this->fluxModele->supprimerFlux($id);
        header("Location: .?action=afficherFlux");
    }



    private function setNbAffiche()
    {
        $nbFlux = $_REQUEST['nbFlux'];
        Validation::isNumber($nbFlux);

        $nbNews = $_REQUEST['nbNews'];
        Validation::isNumber($nbNews);

        $this->paramModele->setParameters('nbFluxPage', $nbFlux);
        $this->paramModele->setParameters('nbNewsPage', $nbNews);

        header("Location: .?action=afficherFlux");

    }
}