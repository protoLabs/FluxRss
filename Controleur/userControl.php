<?php
/**
 * Created by PhpStorm.
 * User: Theo
 * Date: 29/11/2014
 * Time: 17:06
 */

namespace controleur;


use modele\AdminModele;
use Twig_Autoloader;
use Twig_Environment;
use Twig_Loader_Filesystem;
use utilitaires\Validation;
use modele\FluxModele;
use modele\NewsModele;
use utilitaires\XMLParser;


class userControl
{
    private $newsModele;
    private $fluxModele;
    private $admin;
    private $twig;

    public function __construct(){
        $this->newsModele = new NewsModele();
        $this->fluxModele = new FluxModele();
        $this->admin = new AdminModele();

        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem('Vue/Templates');
        $this->twig = new Twig_Environment($loader, array(
            'cache' => false
        ));

        $action = (isset($_REQUEST['action'])?$_REQUEST['action']:null);
        switch ($action){
            case null:
            case 'afficherNews':
                $this->afficherNews();
                break;
            case 'afficherFlux':
                $this->afficherFluxs();
                break;
            case 'afficherNewsDe':
                $this->afficherNewsDuFlux();
                break;
        }
    }

    private function afficherNews()
    {
        global $nbNewsPage;
        $page = $this->getPage();

        $newstab = $this->newsModele->getPageNews($page);
        $nbNews = $this->newsModele->getNbNews();
        $tabFlux = $this->fluxModele->getListFlux();
        $adminco = $this->admin->isAdmin();

        $template = $this->twig->loadTemplate('pageAccueil.twig');

        echo $template->render(array(
            'News' => $newstab,
            'Fluxs' => $tabFlux,
            'numpage' => $page,
            'nbNewsPage' => $nbNewsPage,
            'nbTotNews' => $nbNews,
            'Admin' => $adminco
        ));

    }

    private function afficherNewsDuFlux(){
        global $nbNewsPage;
        $fluxid = $_REQUEST['flux'];
        Validation::existe($fluxid);
        Validation::isNumber($fluxid);

        $page = $this->getPage();
        $news = $this->newsModele->getNewsFlux($fluxid,$page);
        $nbNews = $this->fluxModele->getNbNews($fluxid);
        $tabFlux = $this->fluxModele->getListFlux();
        $adminco = $this->admin->isAdmin();


        $flux = $tabFlux[$fluxid];
        if($flux == null){

        }
        $template = $this->twig->loadTemplate('pageNewsDeFlux.twig');

        echo $template->render(array(
            'Fluxs' => $tabFlux,
            'Flux' => $flux,
            'News' => $news,
            'numpage' => $page,
            'nbNewsPage' => $nbNewsPage,
            'nbTotNews' => $nbNews,
            'Admin' => $adminco
        ));

    }

    private function afficherFluxs()
    {
        global $nbFluxPage, $nbNewsPage;
        
        $page = $this->getPage();
        $fluxs = $this->fluxModele->getPageFlux($page);
        $nbFlux = $this->fluxModele->getNbFlux();

        $adminco = $this->admin->isAdmin();
        
        $template = $this->twig->loadTemplate('pageListeFlux.twig');
        echo $template->render(array(
            'Fluxs' => $fluxs,
            'numpage' => $page,
            'nbTotFlux' => $nbFlux,
            'nbFluxPage' => $nbFluxPage,
            'nbNewsPage' => $nbNewsPage,
            'Admin' => $adminco
        ));
    }


    private function getPage(){
        $page = (isset($_REQUEST['page'])?$_REQUEST['page']:1);
        Validation::isNumber($page);
        return $page;
    }

}

