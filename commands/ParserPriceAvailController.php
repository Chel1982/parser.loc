<?php

namespace app\commands;

use app\models\Availability;
use app\models\Curl;
use app\models\CurlAuth;
use app\models\Goods;
use app\models\LogsCurl;
use app\models\LogsPriceAvail;
use app\models\Price;
use app\models\Sites;
use app\models\Xpath;
use Symfony\Component\DomCrawler\Crawler;
use VDB\Spider\Event\SpiderEvents;
use VDB\Spider\EventListener\PolitenessPolicyListener;
use VDB\Spider\Spider;
use yii\console\Controller;
use Exception;

class ParserPriceAvailController extends Controller
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

        $regAvail = Xpath::findOne(['sites_id' => $idSite, 'name_regular_id' => 13]);

        foreach ($urlGoods as $urlG){

            $sites = Sites::findOne($idSite);
            $regPrice = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 10]);

            if ($sites->usleep_stop != 0){
                usleep(rand($sites->usleep_start * 1000000, $sites->usleep_stop * 1000000));
            }

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

                /*Проверяем наличие цены*/
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
                    }

                    $this->actionLogsPriceAvailSuccess($goods->id, 'price');

                }catch (Exception $e) {

                    $goods = Goods::find()->where(['uri_goods' => $urlG['uri_goods']])->with('sites')->one();

                    $this->actionLogsPriceFailed($goods->id, $e, 'price');

                }

                /*Проверяем наличие товара*/
                try {

                    if ($regAvail->regular == '1'){

                        if (Availability::find()->where(['goods_id' => $goods->id])->exists()){
                            $avail = Availability::findOne(['goods_id' => $goods->id]);
                            $avail->availability = '1';
                            $avail->save();

                        }else{
                            $avail = new Availability();
                            $avail->availability = '1';
                            $avail->goods_id = $goods->id;
                            $avail->save();
                        }

                    }else{
                        $availability = $resource->getCrawler()->filterXpath($regAvail->regular)->text();

                        $availability = trim($availability);


                        $availabilityCount = preg_replace("/[^0-9]/", '', $availability);

                        if(stristr($availability, 'шт.') and $availabilityCount > 0){

                            if (Availability::find()->where(['goods_id' => $goods->id])->exists()){

                                $avail = Availability::findOne(['goods_id' => $goods->id]);
                                $avail->availability = '1';
                                $avail->save();

                            }else{

                                $avail = new Availability();
                                $avail->availability = '1';
                                $avail->goods_id = $goods->id;
                                $avail->save();

                            }

                        }elseif (!stristr($availability, 'шт.' or (stristr($availability, 'шт.') and $availabilityCount == 0))){

                            if (Availability::find()->where(['goods_id' => $goods->id])->exists()){

                                $avail = Availability::findOne(['goods_id' => $goods->id]);
                                $avail->availability = '0';
                                $avail->save();

                            }else{
                                $avail = new Availability();
                                $avail->availability = '0';
                                $avail->goods_id = $goods->id;
                                $avail->save();
                            }
                        }
                    }

                    $this->actionLogsPriceAvailSuccess($goods->id, 'availability');

                }catch (Exception $e) {

                    $goods = Goods::find()->where(['uri_goods' => $urlG['uri_goods']])->with('sites')->one();

                    $this->actionLogsPriceAvailFailed($goods->id, $e, 'availability');

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

            $goods = Goods::findOne(['uri_goods' => $urlG['uri_goods']]);

            $regPrice = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 10]);

            $regAvail = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 13]);

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

            $data = $this->actionCurl($curl_arr, $urlG['id'] , $idSite);

            $crawler = new Crawler($data);

            /*Проверяем наличие цены*/
            try {

                $priceSite = $crawler->filterXPath($regPrice->regular)->text();

                $priceSite = trim($priceSite);

                $priceSite = preg_replace("/[^0-9]/", '', $priceSite);

                $priceParser = $goods->prices->price;

                if((int)$priceSite !== (int)$priceParser){
                    $priceCorrect = Price::findOne(['goods_id' => $goods->id]);
                    $priceCorrect->price = $priceSite;
                    $priceCorrect->save();
                }

                $this->actionLogsPriceAvailSuccess($goods->id, 'price');

            }catch (Exception $e) {

                $goods = Goods::find()->where(['uri_goods' => $urlG['uri_goods']])->with('sites')->one();

                $this->actionLogsPriceAvailFailed($goods->id, $e, 'price');

            }

            /*Проверяем наличие товара*/
            try {

                if ($regAvail->regular == '1'){

                    if (Availability::find()->where(['goods_id' => $goods->id])->exists()){
                        $avail = Availability::findOne(['goods_id' => $goods->id]);
                        $avail->availability = '1';
                        $avail->save();

                    }else{
                        $avail = new Availability();
                        $avail->availability = '1';
                        $avail->goods_id = $goods->id;
                        $avail->save();
                    }

                }else{
                    $availability = $crawler->filterXPath($regAvail->regular)->text();

                    $availability = trim($availability);

                    $availabilityCount = preg_replace("/[^0-9]/", '', $availability);

                    if(stristr($availability, 'шт.') and $availabilityCount > 0){

                        if (Availability::find()->where(['goods_id' => $goods->id])->exists()){

                            $avail = Availability::findOne(['goods_id' => $goods->id]);
                            $avail->availability = '1';
                            $avail->save();

                        }else{

                            $avail = new Availability();
                            $avail->availability = '1';
                            $avail->goods_id = $goods->id;
                            $avail->save();

                        }

                    }elseif (!stristr($availability, 'шт.' or (stristr($availability, 'шт.') and $availabilityCount == 0))){

                        if (Availability::find()->where(['goods_id' => $goods->id])->exists()){

                            $avail = Availability::findOne(['goods_id' => $goods->id]);
                            $avail->availability = '0';
                            $avail->save();

                        }else{
                            $avail = new Availability();
                            $avail->availability = '0';
                            $avail->goods_id = $goods->id;
                            $avail->save();
                        }
                    }
                }


                $this->actionLogsPriceAvailSuccess($goods->id, 'availability');

            }catch (Exception $e) {

                $goods = Goods::find()->where(['uri_goods' => $urlG['uri_goods']])->with('sites')->one();

                $this->actionLogsPriceAvailFailed($goods->id, $e, 'availability');

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

    public function actionLogsPriceAvailSuccess($goodsId, $row)
    {
        if ($row == 'price'){

            if (LogsPriceAvail::find()->where(['goods_id' => $goodsId])->exists()){

                $log = LogsPriceAvail::findOne(['goods_id' => $goodsId]);
                $log->price = 'Успешно проверено';
                $log->save();

            }else{

                $log = new LogsPriceAvail();
                $log->price = 'Успешно проверено';
                $log->goods_id = $goodsId;
                $log->save();

            }
        }elseif ($row == 'availability'){

            if (LogsPriceAvail::find()->where(['goods_id' => $goodsId])->exists()){

                $log = LogsPriceAvail::findOne(['goods_id' => $goodsId]);
                $log->availability = 'Успешно проверено';
                $log->save();

            }else{

                $log = new LogsPriceAvail();
                $log->availability = 'Успешно проверено';
                $log->goods_id = $goodsId;
                $log->save();

            }
        }
    }

    public function actionLogsPriceAvailFailed($goodsId, $e, $row)
    {
        if ($row == 'price') {

            if (LogsPriceAvail::find()->where(['goods_id' => $goodsId])->exists()) {
                $log = LogsPriceAvail::findOne(['goods_id' => $goodsId]);
                $log->price = $e->getMessage();
                $log->save();
            } else {
                $log = new LogsPriceAvail();
                $log->price = $e->getMessage();
                $log->goods_id = $goodsId;
                $log->save();
            }

        } elseif ($row == 'availability') {

            if (LogsPriceAvail::find()->where(['goods_id' => $goodsId])->exists()) {
                $log = LogsPriceAvail::findOne(['goods_id' => $goodsId]);
                $log->availability = $e->getMessage();
                $log->save();
            } else {
                $log = new LogsPriceAvail();
                $log->availability = $e->getMessage();
                $log->goods_id = $goodsId;
                $log->save();
            }

        }

    }
}