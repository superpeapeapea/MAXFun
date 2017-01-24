<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;

class MXArtistController extends Controller{
    protected static function responseRestFul($data,$code=0, $message=null ){
        $res=compact('data','code','message');
        return \Response::json($res);
    }
    
    protected function validateInput($rules, $inputMapArr=null, $isThrowError=true){
        if($inputMapArr === null){
            $inputMapArr = Request::input();
        }
        
		$validator = Validator::make($inputMapArr,$rules);
		if ($validator->fails())
		{
		    // The given data did not pass validation
		    if($isThrowError)
		    {
		        //For the sample prject, we are not expanding code everywere. We are not dealing it as exception
		    	throw new \MXError($validator->messages(),\MXGlobal::CODE_INPUT_INVALID);
		    	
		    	//return $this->responseRestFul(null, \MXGlobal::CODE_INPUT_INVALID, $validator->messages());
		    }
		    else
		    {
		    	return false;
		    }
		}
		return true;
    }
    
    public function search(Request $req){
        $input = $req->all();
        $this->validateInput(['search_str'=>'required|max:100|min:2', 'page'=>'min:0'], $input);

        //$raw = $curlIns->curlForContent('https://api.spotify.com/v1/search?q=john&type=artist');
        return $this->responseRestFul(MXSportifyArtistProvider::instance()->searchByName($input['search_str'], $req->get('page',0)));
    }
    
    public function similar(Request $req, $artistId){
        $par = ['artist_id'=>$artistId, 'page'=>$req->get('page',0)];
        $this->validateInput(['artist_id'=>'required|max:100|min:2', 'page'=>'min:0'], $par);
        
        $artistIns = MXSportifyArtistProvider::instance()->getUserById($artistId);

        //Since sportify is not giving good results for multiple genre search, we will search genres one by one
        //Frontend page is mode of genres
        $genCount = count($artistIns['genres']);
        if($genCount == 0){
            $similarArtistArr = [];
        }else{
            $genIndex = $par['page']%$genCount;
            $similarArtistArr = MXSportifyArtistProvider::instance()->searchByGenre([$artistIns['genres'][$genIndex]], $par['page']/$genCount);
            
            foreach($similarArtistArr as $key=>&$similarArtist){
                if($similarArtist['id'] == $artistId){
                    unset($similarArtistArr[$key]);
                }else{
                    //Temporary, use count()
                    $similarArtist['isc_score'] = count(array_intersect($artistIns['genres'], $similarArtist['genres']));
                }
            }
            usort($similarArtistArr, function($aa, $ab){
                return $ab['isc_score'] - $aa['isc_score'];
            });
        }
        
        return $this->responseRestFul($similarArtistArr);
    }
}

/**
 * It is a global constant set
 */
class MXSportifyArtistProvider{
    protected static $theOne = null;
    public static function instance()
    {
        if(self::$theOne == null)
        {
            self::$theOne = new self();
        }
        return self::$theOne;
    }
    
    public $pageLimit = 20;
    /**
     * @ Search by key artist name
     * 
     * Will keep track of searched results
     * Return raw data remporarily
     */
    public function searchByName($q, $page){
        $storageKey = MXKeyStorage::genKey(MXKeyStorage::SPACE_SPOTIFY_NAME_SEARCH, $q, $page);
        $results = MXKeyStorage::get($storageKey, null);
        
        if($results === null){
            $results = $this->launchSearch(['q'=>'artist:'.$q
                , 'offset'=>$page*$this->pageLimit
            ]);
            dispatch(new MXKeyStorageJob([$storageKey=>$results]));
        }
        
        return $results;
    }
    
    public function searchByGenre(array $genres, $page){
        sort($genres);
        $storageKey = MXKeyStorage::genKey(MXKeyStorage::SPACE_SPOTIFY_GENRE_SEARCH, implode(':',$genres), $page);
        $results = MXKeyStorage::get($storageKey, null);
        
        if($results === null){
            $results = $this->launchSearch(
                            ['q'=>'genre:"'.implode(' ', $genres).'"'
                            , 'offset'=>$page*$this->pageLimit
                        ]);
            dispatch(new MXKeyStorageJob([$storageKey=>$results]));
        }
        
        return $results;
    }
    
    
    public function getUserById($id, $fromCache=true){
        $artistInfo = null;
        if($fromCache){
            $artistKey = MXKeyStorage::genKey(MXKeyStorage::SPACE_SPOTIFY_ARTIST, $id);
            $artistInfo = MXKeyStorage::get($artistKey, null);
        }
        
        if($artistInfo === null){
            $curlIns = new MXCurl();
            $raw = $curlIns->curlForContent('https://api.spotify.com/v1/artists/'.$id);
            $artistInfo = json_decode($raw, true);
            
            $this->storeArtists([$artistInfo]);
        }
        return $artistInfo;
    }
    
    protected function launchSearch($parameters){
        $curlIns = new MXCurl();
        $parameters['type'] = 'artist';
        $a = 'https://api.spotify.com/v1/search?'.http_build_query($parameters);
        $raw = $curlIns->curlForContent($a);
        $res = json_decode($raw, true);
        $artistsArr = $res['artists']['items'];
        
        $this->storeArtists($artistsArr);
        return $artistsArr;
    }
    
    /**
     * @artistsArr is raw from spotify
     *
     * Store asyncly.
     */
    protected function storeArtists(array $artistsArr){
            $keyContentMap = [];
            foreach ($artistsArr as $artist){
                $artistKey = MXKeyStorage::genKey(MXKeyStorage::SPACE_SPOTIFY_ARTIST, $artist['id']);
                $keyContentMap[$artistKey] = $artist;
            }
            dispatch(new MXKeyStorageJob($keyContentMap));
    }
}

/**
 * It is a global constant set
 */
use Illuminate\Contracts\Queue\ShouldQueue;
class MXKeyStorageJob implements ShouldQueue{
    protected $keyContentMap;
    public function __construct(array $keyContentMap){
        $this->keyContentMap = $keyContentMap;
    }
    
    public function handle(){
        foreach ($this->keyContentMap as $key=>$content){
            MXKeyStorage::put($key, $content, MXKeyStorage::TIME_ONE_MONTH);
        }
    }
}

/**
 * It is a global constant set
 */
class MXKeyStorage extends \Cache{
    CONST SPACE_SPOTIFY_ARTIST = 1;
    CONST SPACE_SPOTIFY_NAME_SEARCH = 2;
    CONST SPACE_SPOTIFY_GENRE_SEARCH = 3;
    
    CONST TIME_ONE_DAY = 1440;
    CONST TIME_ONE_WEEK = 10080;
    CONST TIME_ONE_MONTH = 43200;
    
    public static function  genKey(){
        $keyChain = func_get_args();
        return implode('.', $keyChain);
    }
}

/**
 * It is a global constant set
 */
class MXGlobal{
    CONST CODE_INPUT_INVALID = -2;
    CONST CODE_CONNECTION_FAILED = -3;
}

/**
 * A base sample error for MX
 */
class MXError extends \Exception{
    protected $debugData;
    
    public function __construct($message, $code=-1, array $debugData=[]){
        $this->debugData = $debugData;
        parent::__construct($message, $code);
    }
    
    public function getDebugData(){
        return $this->debugData;
    }
}

/**
 * It should be in utility for real project
 */
class MXCurl{
    public $options = array(
                    CURLOPT_CONNECTTIMEOUT=>10
                    ,CURLOPT_RETURNTRANSFER=>1);
    
    //Read only
    protected $connection;
    
    public function getCon(){
        return $this->connection;
    }
    
    public function __construct()
    {
        $this->connection=curl_init();
    }
    
    public function __destruct()
    {
        curl_close($this->connection);
    }
    
//     public function setParams($param){
//         $a = http_build_query($param);
//         curl_setopt($this->connection, CURLOPT_POSTFIELDS, http_build_query($param));
//     }
    
    public function curlForContent($url,$notConnectException=true)
    {
        curl_setopt_array($this->connection,$this->options);
        curl_setopt($this->connection,CURLOPT_URL,$url);
        $content=curl_exec($this->connection);
    
        if($content==false && $notConnectException)
        {
            throw new MXError('Faild to connect for '.$url."\n".curl_error($this->connection)
                ,MXGlobal::CODE_CONNECTION_FAILED
                ,array('url'=>$url,'error'=>curl_error($this->connection)));
        }
        return $content;
    }
}