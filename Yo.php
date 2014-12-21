<?php
/**
* YoPHP
* A simple PHP wrapper for YO (www.justyo.co)
*
* @link http://yoapi.justyo.co/ (API documentation)
* @author Bas van Dorst <basvandorst at gmail.com>
*/
class Yo {
   
   /**
    * HTTP methods
    * @var constant
    */
   const HTTP_GET = 'GET',
         HTTP_POST = 'POST';
   
   /**
    * YO API token
    * @var string
    */
   private $token;
   
   /**
    * YO API endpoint
    * @var string 
    */
   private static $endpoint = 'http://api.justyo.co';
   
   /**
    * YO successful HTTP codes
    * @var array
    */
   private static $successfulHttpCodes = array(
       200,
       201
   );
   
   /**
    * Constructor 
    * @param string $token
    * @throws Exception
    */
   public function __construct($token = null) {
       if(isset($token) && $token !== null) {
           $this->token = $token;    
       } else {
           throw new Exception('Please set a valid token..');
       }
   }
   
   /**
    * YO all your subscribers
    * 
    * @return stdClass
    */
   public function all() {
       return $this->allWithLink('');
   }

   /**
    * YO all your subscribers with link
    * 
    * @param string $link
    * @return stdClass
    */
   public function allWithLink($link) {
       if(!empty($link) && !$this->verifyUrl($link)) throw new Exception('Bad url');

       $url = self::$endpoint.'/yoall/';
       
       $params = array(
           'api_token' => $this->token
       );

       if(!empty($link)) $params['link'] = $link;

       $result = $this->call(self::HTTP_POST, $url, $params);
       return $result;
   }
       
   /**
    * YO a specific user
    * 
    * @param string $username
    * @return stdClass
    */
   public function user($username) {
       return $this->userRequest($username, '', '');
   }

   /**
    * YO a specific user with link
    * 
    * @param string $username
    * @param string $link
    * @return stdClass
    * @throws Exception
    */
   public function userWithLink($username, $link) {
       return $this->userRequest($username, $link, '');
   }

   /**
    * YO a specific user with location
    * 
    * @param string $username
    * @param string $location
    * @return stdClass
    * @throws Exception
    */
   public function userWithLocation($username, $location) {
       return $this->userRequest($username, '', $location);
   }

   /**
    * Prepares request to Yo a specific user (with link/location optional)
    * 
    * @param string $username
    * @param string $link
    * @param string $location
    * @return stdClass
    * @throws Exception
    */
   private function userRequest($username, $link, $location) {
       if(!empty($link) && !$this->verifyUrl($link)) throw new Exception('Bad url');
       if(!empty($location) && !$this->verifyLocation($location)) throw new Exception('Bad location format (lat,long)');

       $params = array(
           'api_token' => $this->token,
           'username' => $username
       );
       if(!empty($link)) $params['link'] = $link;
       if(!empty($location)) $params['location'] = $location;

       $result = $this->call(self::HTTP_POST, $url, $params);
       return $result;

   }

   /**
    * Returns the number of subscribers.
    * 
    * @param string $username
    * @return stdClass
    */       
   public function count() {
       $url = self::$endpoint.'/subscribers_count/';
       
       $params = array(
           'api_token' => $this->token,
       );
       $result = $this->call(self::HTTP_GET, $url, $params);
       return $result;
   }

   /**
    * Verifies the validity of an url
    * 
    * @param string $url
    * @return boolean
    */
   private function verifyUrl($url) {
       if(filter_var($url, FILTER_VALIDATE_URL) === false) return false;

       return true;
   }

   /**
    * Verifies the format of a location (lat,long)
    * 
    * @param string $location
    * @return boolean
    */
   private function verifyLocation($location) {
       // Tests if the location contains a comma and not more than 2 dots
       if(substr_count($location, ',') < 1 || substr_count($location, ',') > 1 || substr_count($location, '.') > 2) return false;

       // Tests if the location only contains digits (except dots and comma)
       $location = str_replace(',', '', $location);
       $location = str_replace('.', '', $location);
       if(!ctype_digit($location)) return false;

       return true;
   }
   
   /**
    * Method for handling the API requests to YO
    * 
    * @param string $method Is is this a GET or POST call?!
    * @param string $url full endpoint URL (including path)
    * @param array $params Parameters to push 
    * @return stdClass|null (null in case of failed json_decode)
    * @throws Exception
    */
   private function call($method, $url, $params = array()) {        
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       
       if($method == self::HTTP_POST) {
           curl_setopt($ch, CURLOPT_POST, TRUE);
           curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
           curl_setopt($ch, CURLOPT_URL, $url);
       } else {
           curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
           curl_setopt($ch, CURLOPT_URL, $url. "?" . http_build_query($params));
       }
       
       // Get the HTTP response
       if(($response = curl_exec($ch)) === false) {
           throw new Exception('cURL error: '.curl_error($ch));
       }
       
       // Try to find out if we received a 20x HTTP code.
       $info = curl_getinfo($ch);
       if(!in_array($info['http_code'], self::$successfulHttpCodes)) {
           throw new Exception('Invalid HTTP response code ('.$info['http_code'].'). Response: '.$response);
       }
 
       $response = json_decode($response);
       return $response;
   }
}
