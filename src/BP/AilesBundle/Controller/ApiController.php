<?php

namespace BP\AilesBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use \BP\AilesBundle\Entity\Membres;
use Symfony\Component\Validator\Constraints\DateTime;



class ApiController extends Controller
{
public function nationalNewsAction(){
        header("Access-Control-Allow-Origin: *");
        $url = "https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&q=http://www.le360.ma/fr/rss/soci%C3%A9t%C3%A9&num=10";
        
        $ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
        
        $news = json_decode($data,true);
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
        JsonEncoder()));
        $newsjson = $serializer->serialize(array("results"=>$news["responseData"]["feed"]["entries"]), 'json');
        
        $response = new Response();
        $response->setContent($newsjson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
public function internationalNewsAction(){
        header("Access-Control-Allow-Origin: *");
        $url = "https://ajax.googleapis.com/ajax/services/feed/load?v=1.0&q=https://fr.news.yahoo.com/rss/world&num=30";
        
        $ch = curl_init();
	//$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
        
        $news = json_decode($data,true);
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
        JsonEncoder()));
        $internjson = $serializer->serialize(array("results"=>$news["responseData"]["feed"]["entries"]), 'json');
        
        $response = new Response();
        $response->setContent($internjson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
  public function trainAction($depart, $arrivee, $day, $month, $year){
        
        header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $response = array();
        $horraires = array();
        
        $CodeRD = explode("|",$depart)[0];
        $CodeGD = explode("|",$depart)[1];
        $CodeRA = explode("|",$arrivee)[0];
        $CodeGA = explode("|",$arrivee)[1];
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile('http://www.oncf.ma/Pages/ResultatsHoraire.aspx?CodeRD='.$CodeRD.'&CodeGD='.$CodeGD.'&'
                . 'CodeRA='.$CodeRA.'&CodeGA='.$CodeGA.'&heure=0000&date='.$day.'/'.$month.'/'.$year.'');
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//tr[@bgcolor]');
        
        foreach($rows as $row){

            $horraires[] = array("depart" => $row->getElementsByTagName("td")->item(0)->nodeValue,
                                "arrivee" => $row->getElementsByTagName("td")->item(1)->nodeValue,
                                "correspondance" => $row->getElementsByTagName("td")->item(2)->nodeValue);

        }

        $response = array("status"=>"OK","result"=>200,"content"=>$horraires);
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
        JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    } 
    
    public function villePharmaAction()
    {
        header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile('http://www.telecontact.ma/services/pharmacies-de-garde.html');
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//ul[@id="pharmacie_ville_ul"]');
        $content = array();
        foreach($rows as $row){
            $arr = $row->getElementsByTagName("a");
            foreach($arr as $a){
                $href = $a->getAttribute("href");
                $titre= $a->getElementsByTagName("li")->item(0)->nodeValue;
            $content[] = array("url"=>"http://www.telecontact.ma".$href,
                                "ville" =>$titre );
        }
        
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }
    
    public function PharmacieAction(){
        
         header("Access-Control-Allow-Origin: *");
        $url = $_GET["url"];
        $doctrine = $this->getDoctrine();
        
        $response = array();
        $pharma = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
		
		// liste des titres
		$titles = [];
		$columns = $xp->query(CssSelector::toXPath('section#engine-results a[title="+ d\'infos"]'));
		
		foreach ( $columns as $col ) {
			$titles[] = $col->nodeValue;
		}
		
		// liste des addresses
		$addresses = [];
		$adColumns = $xp->query(CssSelector::toXPath('section#engine-results div.results-adress span span')); 
		foreach ( $adColumns as $col ) {
			$addresses[] = $col->nodeValue;
		}
		
		// liste des telephonnes
		$phones = [];
		$telColumns = $xp->query(CssSelector::toXPath('section#engine-results div.results-telephone div.tel')); 
		foreach ( $telColumns as $col ) {
			$phones[] = $col->nodeValue;
		}
		
		// return value as JSON Response
		$content = [];
		for ( $offset = 0; $offset < count($phones); $offset++ ) {
			$content[] = [
				'title'   => $titles[ $offset ],
				'address' => $addresses[ $offset ],
				'phone'   => $phones[ $offset ],
			];
		}
		
		
        // $rows = $xp->query('//div[@class="pharmacies_elements_cls"]');
        // $i = 0;
        // $content = array();
        // foreach($rows as $row) {
             
            // $content[] = array("title"=> utf8_decode($row->getElementsByTagName("div")->item(0)->getElementsByTagName("span")->item(0)->nodeValue),
            //                "address"=>  utf8_decode($row->getElementsByTagName("div")->item(0)->getElementsByTagName("span")->item(1)->nodeValue),
            //                "phone"=>$row->getElementsByTagName("div")->item(1)->getElementsByTagName("div")->item(0)->nodeValue);
			//
        // }
     
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
           
        }
        
        public function santeAction() {
            
            header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile('http://www.femmesdumaroc.com/guide-pratique/sante');
        libxml_clear_errors(); 
        
        $xp = new \DOMXPath($doc); 
        $rows = $xp->query('//div[@class="columns small-12 art-single"]');
        $content = array();
        
        foreach($rows as $row){
            $img = "http://www.femmesdumaroc.com".$row->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->getElementsByTagName("img")->item(0)->getAttribute("src");
            $content[] = array("url"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->getAttribute("href"),
                                "img"=>substr($img,0,-12),  
                                "titre"=>  $row->getElementsByTagName("div")->item(1)->getElementsByTagName("h2")->item(0)->getElementsByTagName("a")->item(0)->nodeValue,
                                "descr"=>  $row->getElementsByTagName("div")->item(1)->getElementsByTagName("a")->item(1)->getElementsByTagName("h3")->item(0)->nodeValue
                
                    );
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
        }
        
        public function articleSanteAction()
        {
            
             header("Access-Control-Allow-Origin: *");
             $url = $_GET["url"];
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//div[@class="columns small-8 article"]');
        $content = null;
        foreach($rows as $row){
            
            $content = array(  
                                "titre"=>  $row->getElementsByTagName("h1")->item(0)->nodeValue,
                                "img"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("img")->item(0)->getAttribute("src"),
                                "text1"=>  $row->getElementsByTagName("h3")->item(1)->nodeValue,
                                "text2"=>  $row->getElementsByTagName("div")->item(2)->nodeValue
                    );
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
            
        }
        
        public function loginAction($rib){
        header("Access-Control-Allow-Origin: *");
        $response = FALSE;
        
        $doctrine = $this->getDoctrine();
        $userExist = $doctrine->getRepository('BPAilesBundle:Membres')
                                     ->findOneBy(array('radical' => $rib));
        if($userExist){
            $response = array('state'=>TRUE, 'ribs'=>$userExist->getRibs());
        }
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
        JsonEncoder()));
        $responseJson = $serializer->serialize(array("results"=>$response), 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        }
        
        public function profilAction($rib) {
            
           header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $response = FALSE;
        
        $user = $doctrine->getRepository('BPAilesBundle:Membres')
                                                    ->findOneBy(array('ribs' => $rib));
        
        if($user){
            $response = $user;
        }
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
        JsonEncoder()));
        $responseJson = $serializer->serialize(array("content"=>$response), 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;  
    
        }
        
        public function updateprofilAction($rib, $titre, $intitule, $sigle, $dtNais, $gsm, $mail) {
            
           header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        $em= $this->getDoctrine()->getManager();
        
        $response = FALSE;
        
        $user = $doctrine->getRepository('BPAilesBundle:Membres')
                                                    ->findOneBy(array('ribs' => $rib));
        
        if($user){
            
            $user->setTitre($titre);
            $user->setIntitule($intitule);
            $user->setSigle($sigle);
            $user->setDtNais($dtNais);
            $user->setGsm($gsm);
            $user->setMail($mail);
            $em->persist($user); 
            $em->flush();
            
        }
        $response  = array($rib);
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
        JsonEncoder()));
        $responseJson = $serializer->serialize(array("content"=>$response), 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;  
    
        }
        
        public function beauteAction() {
            
            header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile('http://www.femmesdumaroc.com/beaute/pratique');
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//div[@class="columns small-12 art-single"]');
        $content = array();
        foreach($rows as $row){
            
            $content[] = array("url"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->getAttribute("href"),
                                "img"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->getElementsByTagName("img")->item(0)->getAttribute("src"),  
                                "titre"=>  $row->getElementsByTagName("div")->item(1)->getElementsByTagName("h2")->item(0)->getElementsByTagName("a")->item(0)->nodeValue,
                                "descr"=>  $row->getElementsByTagName("div")->item(1)->getElementsByTagName("a")->item(1)->getElementsByTagName("h3")->item(0)->nodeValue
                
                    );
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
        }
        
        public function articleBeauteAction()
        {
            
             header("Access-Control-Allow-Origin: *");
             $url = $_GET["url"];
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//div[@class="columns small-8 article"]');
        $content = null;
        foreach($rows as $row){
            
            $content = array(  
                                "titre"=>  $row->getElementsByTagName("h1")->item(0)->nodeValue,
                                "img"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("img")->item(0)->getAttribute("src"),
                                "text1"=>  $row->getElementsByTagName("h3")->item(1)->nodeValue,
                                "text2"=>  $row->getElementsByTagName("div")->item(2)->nodeValue
                    );
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
            
        }
        
         public function modeAction() {
            
            header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile('http://www.journaldesfemmes.com/mode/actualites');
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//li[@class="jContents"]');
        $content = array();
        foreach($rows as $row){
             $img = $row->getElementsByTagName("div")->item(1)->getElementsByTagName("a")->item(0)->getAttribute("style");;
            $content[] = array("url"=>"http://www.journaldesfemmes.com".$row->getElementsByTagName("div")->item(1)->getElementsByTagName("a")->item(0)->getAttribute("href"),
                                 "img"=>substr($img,21,-1),
                                "titre"=> $row->getElementsByTagName("div")->item(2)->getElementsByTagName("h4")->item(0)->getElementsByTagName("a")->item(0)->nodeValue,
                                "descr"=>  $row->getElementsByTagName("div")->item(2)->getElementsByTagName("p")->item(0)->nodeValue
                
                    );
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
        }
        
        public function articleModeAction()
        {
            
             header("Access-Control-Allow-Origin: *");
             $url = $_GET["url"];
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//div[@class="columns small-8 article"]');
        $content = null;
        foreach($rows as $row){
            
            $content = array(  
                                "titre"=>  $row->getElementsByTagName("h1")->item(0)->nodeValue,
                                "img"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("img")->item(0)->getAttribute("src"),
                                "text1"=>  $row->getElementsByTagName("h3")->item(1)->nodeValue,
                                "text2"=>  $row->getElementsByTagName("div")->item(2)->nodeValue
                    );
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
            
        }
        
        public function cuisineAction() {
            
            header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile('http://www.femmesdumaroc.com/guide-pratique/cuisine');
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//div[@class="columns small-12 art-single"]');
        $content = array();
        foreach($rows as $row){
            
            $content[] = array("url"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->getAttribute("href"),
                                "img"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("div")->item(0)->getElementsByTagName("a")->item(0)->getElementsByTagName("img")->item(0)->getAttribute("src"),  
                                "titre"=>  $row->getElementsByTagName("div")->item(1)->getElementsByTagName("h2")->item(0)->getElementsByTagName("a")->item(0)->nodeValue,
                                "descr"=>  $row->getElementsByTagName("div")->item(1)->getElementsByTagName("a")->item(1)->getElementsByTagName("h3")->item(0)->nodeValue
                
                    );
            
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
        }
         
         public function articleCuisineAction()
        {
            
             header("Access-Control-Allow-Origin: *");
             $url = $_GET["url"];
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//div[@class="columns small-8 article"]');
        $content = null;
        foreach($rows as $row){
            
            $content = array(  
                                "titre"=>  $row->getElementsByTagName("h1")->item(0)->nodeValue,
                                "img"=>"http://www.femmesdumaroc.com".$row->getElementsByTagName("img")->item(0)->getAttribute("src"),
                                "text1"=>  $row->getElementsByTagName("h3")->item(1)->nodeValue,
                                "text2"=>  $row->getElementsByTagName("div")->item(2)->nodeValue
                    );
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
            
        }
        
      public function transactionAction($rib){
            
       header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $pois = $doctrine->getRepository('BPAilesBundle:Partenaireailes')
                                                    ->cashback($rib);
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize(array("content"=>$pois), 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }
    
     public function partenaireAction(){
            
       header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $pois = $doctrine->getRepository('BPAilesBundle:Partenaireailes')
                                                    ->findAll();
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize(array("content"=>$pois), 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }
        public function poisCategoriesAction(){
        header("Access-Control-Allow-Origin: *");
        $response = array("status"=>"OK", "result"=>"200", "content" =>
            array(
                array("name"=>"Restaurant","icon"=>"http://simo.2wls.com/boAppMobile/images/restaurant.png"),
                array("name"=>"Hopital","icon"=>"http://simo.2wls.com/boAppMobile/images/hospital.png"),
                array("name"=>"Station d'essence","icon"=>"http://simo.2wls.com/boAppMobile/images/gas_station.png"),
                array("name"=>"Cinema","icon"=>"http://simo.2wls.com/boAppMobile/images/cinema.png"),
                array("name"=>"Centres commerciaux","icon"=>"http://simo.2wls.com/boAppMobile/images/shop.png"),
                array("name"=>"Pharmacie","icon"=>"http://simo.2wls.com/boAppMobile/images/pharmacy.png")
                
                )
            );
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
        JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function poisAction($category,$latitude,$longitude){
        header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $pois = $doctrine->getRepository('BPAilesBundle:Geoloc')
                                                    ->getPOIS(addslashes($category),$latitude,$longitude);
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new 
        JsonEncoder()));
        $responseJson = $serializer->serialize(array("content"=>$pois), 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
    
    public function bladiAction(){
        
        $username = '2000998';
        $password = '12345678';
        $loginUrl = 'http://bladifidelite.ma/index/login';
        
        //init curl
        $ch = curl_init();

        //Set the URL to work with
        curl_setopt($ch, CURLOPT_URL, $loginUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // ENABLE HTTP POST
        curl_setopt($ch, CURLOPT_POST, 1);

        //Set the post parameters
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'login-submit=1&login='.$username.'&password='.$password.'&goTo=%2F');

        //Handle cookies for the login
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'session/cookieBladi.txt');

        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);  
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));
        //execute the request (the login)
        $store = curl_exec($ch);
        
        
        //Set the URL to work with
        curl_setopt($ch, CURLOPT_URL, "http://bladifidelite.ma/member");

        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);  
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec ($ch); 
        $error = curl_error($ch);     
       
        
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTML($data);
        libxml_clear_errors();  
       // echo $doc->saveHTML();

        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//*[@class="connect"]')->item(0);
        $content = $rows->nodeValue ;
      
        /*foreach($rows as $row){
     
            $content[] = array("url"=> $row->getElementsByTagName("div")->item(0)->getElementsByTagName("h2")->item(0)->nodeValue

                    );
        }*/
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    
        
    }
    
    public function urlAction(){
        
        header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        $response = array();
        
        $doc = new \DOMDocument();
        
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile('http://bladifidelite.ma');
        libxml_clear_errors();  
        
        $xp = new \DOMXPath($doc);
        $rows = $xp->query('//div[@class="connect"]');
        $content = array();
        foreach($rows as $row){
            
            $content[] = array("url"=> $row->getElementsByTagName("p")->item(0)->getElementsByTagName("a")->item(1)->nodeValue

                    );
            
            }
      
        $response = array("status"=>"OK","result"=>200,"content"=>$content);
           $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize($response, 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
        
    }
    

    
    public function cashbackAction($rib, $month, $year){
            
       header("Access-Control-Allow-Origin: *");
        $doctrine = $this->getDoctrine();
        
        if($month=="default" || $year=="default"){
         $month= \date("m");
         $year = \date("Y");  
         $pois = $doctrine->getRepository('BPAilesBundle:Partenaireailes')
                                                    ->cashback2($rib,$month,$year);        
        }elseif($month=="0*"){
        
        $pois = $doctrine->getRepository('BPAilesBundle:Partenaireailes')
                                                    ->allcashback($rib,$year);
        }else{
            $pois = $doctrine->getRepository('BPAilesBundle:Partenaireailes')
                                                    ->cashback2($rib,$month,$year);
        }
        
        $serializer = new Serializer(array(new GetSetMethodNormalizer()), array('json' => new JsonEncoder()));
        $responseJson = $serializer->serialize(array("content"=>$pois), 'json');
        
        $response = new Response();
        $response->setContent($responseJson);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
        
    }
    
    
    
    } 
