<?php 

namespace App\Controllers;

use App\Models\CampaignAgency;
use Core\Controller;
use Exception;

class CampaignController extends Controller {
    public $title = 'Campaign';

    private $link;

    private $game;

    public function __construct() {
        $this->game = $_GET['game'] ?? NULL;

        switch($this->game){
            case "yp":
                $this->link = "https://ng-app.com/SEVENTIETHPRECINCT/yellofc-24-yes-23410220000025878-web?trfsrc=70thprecinct&service=outcomepredictor&trxId=";
                break;
            case "tg":
                $this->link = "https://ng-app.com/SEVENTIETHPRECINCT/yellofc-24-yes-23410220000025877-web?trfsrc=70thprecinct&service=goalspredictor&trxId=";
                break;
            case "cs":
                $this->link = "https://ng-app.com/SEVENTIETHPRECINCT/yellofc-24-yes-23410220000025879-web?trfsrc=70thprecinct&service=scorepredictor&trxId=";
                break;
            default: 
                $this->link = "https://ng-app.com/SEVENTIETHPRECINCT/yellofc-24-yes-23410220000025877-web?trfsrc=70thprecinct&service=goalspredictor&trxId=";
                break;
        }
    }

    public function index($code) {
        $campaign = CampaignAgency::where('code', $code)->first();
        $params = [];
        foreach($campaign->params as $param){
            if(empty($_GET[$param])) {
                throw new Exception("Missing parameter $param");
            } else {
                $params[$param] = $_GET[$param];
            }
        }
        $trxID = strtoupper($code).uniqid();
        print_r($this->link.$trxID);
    }
}