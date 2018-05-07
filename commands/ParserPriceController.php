<?php

namespace app\commands;

use app\models\Curl;
use app\models\CurlAuth;
use app\models\Goods;
use app\models\Groups;
use app\models\Logs;
use app\models\LogsCurl;
use app\models\LogsPrice;
use app\models\Price;
use app\models\Sites;
use app\models\Xpath;
use Symfony\Component\DomCrawler\Crawler;
use VDB\Spider\Discoverer\XPathExpressionDiscoverer;
use VDB\Spider\Event\SpiderEvents;
use VDB\Spider\EventListener\PolitenessPolicyListener;
use VDB\Spider\Spider;
use yii\console\Controller;
use Exception;

class ParserPriceController extends Controller
{
    public function actionInit()
    {

        $sites = Sites::find()->where(['status_price' => 1])->asArray()->all();

        foreach ($sites as $site) {

            if (Curl::find()->where(['sites_id' => $site['id']])->exists()){

               $this->actionCurlSpider($site['id']);

            }else{

                $this->actionSpider($site['id']);

            }
        }
    }

    public function actionSpider($idSite)
    {
        $urlGoods = Goods::find()->where(['sites_id' => $idSite])->asArray()->all();

        foreach ($urlGoods as $urlG){

            $sites = Sites::findOne($idSite);
            $regPrice = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 10]);

            if ($sites->usleep_stop != 0){
                usleep(rand($sites->usleep_start * 1000000, $sites->usleep_stop * 1000000));
            }

            echo "Scanning: " . $urlG['uri_goods'] . "\n";

            $spider = new Spider($urlG['uri_goods']);

            if ($sites->delay_parsing != 0){
                $politenessPolicyEventListener = new PolitenessPolicyListener($sites->delay_parsing  * 1000);
                $spider->getDownloader()->getDispatcher()->addListener(
                    SpiderEvents::SPIDER_CRAWL_PRE_REQUEST,
                    array($politenessPolicyEventListener, 'onCrawlPreRequest')
                );
            }

            $spider->crawl();

            foreach ($spider->getDownloader()->getPersistenceHandler() as $resource) {

                try {

                    $priceSite = $resource->getCrawler()->filterXpath($regPrice->regular)->text();

                    $priceSite = trim($priceSite);

                    $priceSite = preg_replace("/[^0-9]/", '', $priceSite);

                    $goods = Goods::findOne(['uri_goods' => $urlG['uri_goods']]);
                    $priceParser = $goods->prices->price;

                    if((int)$priceSite !== (int)$priceParser){
                        $priceCorrect = Price::findOne(['goods_id' => $goods->id]);
                        $priceCorrect->price = $priceSite;
                        $priceCorrect->save();

                        $this->actionLogsPriceSuccess($goods->id);
                    }


                }catch (Exception $e) {

                    $goods = Goods::find()->where(['uri_goods' => $urlG['uri_goods']])->with('sites')->one();

                    $this->actionLogsPriceFailed($goods->id, $e);

                }
            }
        }

        return true;
    }

    public function actionCurlSpider($idSite)
    {
        /* Производим запись кууков, что бы в дальнейшем не аутентифицироваться каждый раз в деманах */
        if (CurlAuth::find()->where(['sites_id' => $idSite])->exists()) {

            $params = CurlAuth::find()->where(['sites_id' => $idSite])->asArray()->all();

            $curl_arr = [];

            foreach ($params as $param) {

                if (stristr($param['value'], '=>')) {

                    $array1 = [];
                    $array2 = explode(',', $param['value']);

                    foreach ($array2 as $str) {

                        list($key, $value) = explode('=>', $str);
                        $array1[$key] = $value;

                    }

                    $key = constant($param['key']);
                    $curl_arr[$key] = $array1;

                } else {

                    $key = constant($param['key']);
                    $curl_arr[$key] = $param['value'];

                }
            }

            $this->actionCurl($curl_arr,null, $idSite);
        }

        $urlGoods = Goods::find()->where(['sites_id' => $idSite])->asArray()->all();

        foreach ($urlGoods as $urlG){

            $sites = Sites::findOne($idSite);
            $regPrice = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 10]);

            if ($sites->usleep_stop != 0){
                usleep(rand($sites->usleep_start * 1000000, $sites->usleep_stop * 1000000));
            }

            $params = Curl::find()->where(['sites_id' => $idSite])->asArray()->all();

            $curl_arr = [];

            foreach ($params as $param) {

                $key = constant($param['key']);
                $curl_arr[$key] = $param['value'];

            }

            $keyUrl = constant('CURLOPT_URL');

            $curl_arr[$keyUrl] = $urlG['uri_goods'];

            echo "Scanning: " . $urlG['uri_goods'] . "\n";

            $data = $this->actionCurl($curl_arr, $urlG['id'] , $idSite);

            $crawler = new Crawler($data);

            try {

                $priceSite = $crawler->filterXPath($regPrice->regular)->text();

                $priceSite = trim($priceSite);

                $priceSite = preg_replace("/[^0-9]/", '', $priceSite);

                $goods = Goods::findOne(['uri_goods' => $urlG['uri_goods']]);
                $priceParser = $goods->prices->price;

                if((int)$priceSite !== (int)$priceParser){
                    $priceCorrect = Price::findOne(['goods_id' => $goods->id]);
                    $priceCorrect->price = $priceSite;
                    $priceCorrect->save();

                    $this->actionLogsPriceSuccess($goods->id);
                }


            }catch (Exception $e) {

                $goods = Goods::find()->where(['uri_goods' => $urlG['uri_goods']])->with('sites')->one();

                $this->actionLogsPriceFailed($goods->id, $e);

            }
        }

        return true;
    }

    public function actionCurl($curl_arr, $idGoods ,$idSite){

        try{

            $ch = curl_init();

            if (FALSE === $ch)
                throw new Exception('failed to initialize');

            curl_setopt_array($ch, $curl_arr);
            $data = curl_exec($ch);

            if (FALSE === $data)
                throw new Exception(curl_error($ch));

            curl_close($ch);

            return $data;

        }catch(Exception $e) {

            $logsCurl = new LogsCurl();
            $logsCurl->name = $e->getMessage();
            $logsCurl->goods_id = $idGoods;
            $logsCurl->sites_id = $idSite;
            $logsCurl->save();

        }

    }

    public function actionLogsPriceSuccess($goodsId)
    {
        $log = new LogsPrice();
        $log->log = 'Цена изменена';
        $log->goods_id = $goodsId;
        $log->save();

    }

    public function actionLogsPriceFailed($goodsId, $e)
    {
        $log = new LogsPrice();
        $log->log = $e->getMessage();
        $log->goods_id = $goodsId;
        $log->save();

    }
}