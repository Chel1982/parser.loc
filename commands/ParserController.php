<?php

namespace app\commands;

use AMQPChannel;
use AMQPConnection;
use AMQPExchange;
use AMQPQueue;
use app\models\Curl;
use app\models\CurlAuth;
use app\models\Description;
use app\models\Goods;
use app\models\Groups;
use app\models\Images;
use app\models\Logs;
use app\models\LogsCurl;
use app\models\Manufacturer;
use app\models\Price;
use app\models\ProductAttributes;
use app\models\Sites;
use app\models\Xpath;
use Exception;
use Symfony\Component\DomCrawler\Crawler;
use VDB\Spider\Discoverer\XPathExpressionDiscoverer;
use VDB\Spider\Spider;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ParserController extends Controller
{
    protected $queue;

    public function actionInit()
    {

        $sites = Sites::find()->where(['status' => 1])->asArray()->all();

        foreach ($sites as $site) {
            /**
             * Подключаемся к брокеру и точке обмена сообщениями
             */
            $rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
            $rabbit->connect();
            $channel = new AMQPChannel($rabbit);

            $this->queue = new AMQPExchange($channel);
            $this->queue->setName('amq.direct');

            $this->actionSpider($site['id'], $site['down_url']);

            $rabbit->disconnect();
        }
    }

    public function actionName()
    {
        /**
         * Подключаемся к брокеру и точке обмена сообщениями
         */
        $rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
        $rabbit->connect();

        $channel = new AMQPChannel($rabbit);
        $queue = new AMQPExchange($channel);
        $queue->setName('amq.direct');

        /**
         * Добавляем очередь откуда будем брать страницы
         */
        $q = new AMQPQueue($channel);
        $q->setName('analyze_name');
        $q->declare();
        $q->bind('amq.direct', 'analyze_name');

        while (true) {

            /**
             * Обрабатываем пока в очереди не закончатся сообщения
             */

            $page = $q->get();

            if ($page) {

                $link = $page->getBody();

                $sites = Sites::find()->where(['id' => Goods::find()->select('sites_id')->where(['uri_goods' => $link])])->one();

                $regName = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 5]);

                /* Если использываем curl */

                if (Curl::find()->where(['sites_id' => $sites->id, 'status' => 1])->exists()) {

                    $goodsId = Goods::findOne(['uri_goods' => $link])->id;

                    /* Если нужна задержка в демоне */
                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    $params = Curl::find()->where(['sites_id' => $sites->id])->asArray()->all();

                    $curl_arr = [];

                    foreach ($params as $param) {

                        $key = constant($param['key']);
                        $curl_arr[$key] = $param['value'];

                    }

                    $keyUrl = constant('CURLOPT_URL');
                    $curl_arr[$keyUrl] = $link;

                    $data = $this->actionCurl($curl_arr,'Название товара', $goodsId ,$sites->id);

                    try {

                        $crawler = new Crawler($data);

                        $name = $crawler->filterXPath($regName->regular)->text();

                        $name = trim($name);

                        $goods = Goods::findOne(['uri_goods' => $link]);
                        $goods->name_goods = $name;
                        $goods->save();

                        $this->actionLogsSuccess($goods->id, 'name');

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 5, 'name', $e);

                    }

                }else{

                    /* Если используем обычный спайдер */

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    try {

                        $spiderPage = new Spider($link);

                        $spiderPage->crawl();

                        foreach ($spiderPage->getDownloader()->getPersistenceHandler() as $resource) {

                        $name = $resource->getCrawler()->filterXpath($regName->regular)->text();

                        $name = trim($name);

                        $goods = Goods::findOne(['uri_goods' => $link]);
                        $goods->name_goods = $name;
                        $goods->save();

                        $this->actionLogsSuccess($goods->id, 'name');

                        }

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 5, 'name', $e);

                    }

                }

                $q->ack($page->getDeliveryTag());

            } else {

                sleep(1);

            }

        }

        $rabbit->disconnect();
    }

    public function actionDesc()
    {
        /**
         * Подключаемся к брокеру и точке обмена сообщениями
         */
        $rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
        $rabbit->connect();
        $channel = new AMQPChannel($rabbit);
        $queue = new AMQPExchange($channel);
        $queue->setName('amq.direct');

        /**
         * Добавляем очередь откуда будем брать страницы
         */
        $q = new AMQPQueue($channel);
        $q->setName('analyze_desc');
        $q->declare();
        $q->bind('amq.direct', 'analyze_desc');
        while (true) {

            /**
             * Обрабатываем пока в очереди не закончатся сообщения
             */

            $page = $q->get();

            if ($page) {

                $link = $page->getBody();

                $sites = Sites::find()->where(['id' => Goods::find()->select('sites_id')->where(['uri_goods' => $link])])->one();

                $regDesc = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 6]);



                if (Curl::find()->where(['sites_id' => $sites->id])->exists()) {

                    $goodsId = Goods::findOne(['uri_goods' => $link])->id;

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    $params = Curl::find()->where(['sites_id' => $sites->id])->asArray()->all();

                    $curl_arr = [];

                    foreach ($params as $param) {

                        $key = constant($param['key']);
                        $curl_arr[$key] = $param['value'];

                    }

                    $keyUrl = constant('CURLOPT_URL');
                    $curl_arr[$keyUrl] = $link;

                    $data = $this->actionCurl($curl_arr,'Описание товара', $goodsId, $sites->id);

                    try {

                        $crawler = new Crawler($data);

                        $description = $crawler->filterXPath($regDesc->regular)->html();

                        $description = trim($description);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        if (Description::find()->where(['goods_id' => $goods->id])->exists()) {

                            $desc = Description::findOne(['goods_id' => $goods->id]);
                            $desc->main = $description;
                            $desc->save();

                        } else {

                            $desc = new Description();
                            $desc->main = $description;
                            $desc->goods_id = $goods->id;
                            $desc->save();
                        }

                        $this->actionLogsSuccess($goods->id, 'description');


                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 6, 'description', $e);

                    }

                }else{

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    try {

                        $spiderPage = new Spider($link);

                        $spiderPage->crawl();

                        foreach ($spiderPage->getDownloader()->getPersistenceHandler() as $resource) {
                        $description = $resource->getCrawler()->filterXpath($regDesc->regular)->html();

                        $description = trim($description);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        if (Description::find()->where(['goods_id' => $goods->id])->exists()) {

                            $desc = Description::findOne(['goods_id' => $goods->id]);
                            $desc->main = $description;
                            $desc->goods_id = $goods->id;
                            $desc->save();

                        } else {

                            $desc = new Description();
                            $desc->main = $description;
                            $desc->goods_id = $goods->id;
                            $desc->save();
                        }

                        $this->actionLogsSuccess($goods->id, 'description');

                        }

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 6, 'description', $e);

                    }

                }

                $q->ack($page->getDeliveryTag());

            } else {
                sleep(1);
            }

        }

        $rabbit->disconnect();
    }

    public function actionDescAdd()
    {
        /**
         * Подключаемся к брокеру и точке обмена сообщениями
         */
        $rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
        $rabbit->connect();
        $channel = new AMQPChannel($rabbit);
        $queue = new AMQPExchange($channel);
        $queue->setName('amq.direct');

        /**
         * Добавляем очередь откуда будем брать страницы
         */
        $q = new AMQPQueue($channel);
        $q->setName('analyze_desc_add');
        $q->declare();
        $q->bind('amq.direct', 'analyze_desc_add');
        while (true) {

            /**
             * Обрабатываем пока в очереди не закончатся сообщения
             */

            $page = $q->get();

            if ($page) {

                $link = $page->getBody();

                $sites = Sites::find()->where(['id' => Goods::find()->select('sites_id')->where(['uri_goods' => $link])])->one();

                $regDescAdd = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 7]);

                if (Curl::find()->where(['sites_id' => $sites->id, 'status' => 1])->exists()) {

                    $goodsId = Goods::findOne(['uri_goods' => $link])->id;

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    $params = Curl::find()->where(['sites_id' => $sites->id])->asArray()->all();

                    $curl_arr = [];

                    foreach ($params as $param) {

                        $key = constant($param['key']);
                        $curl_arr[$key] = $param['value'];

                    }

                    $keyUrl = constant('CURLOPT_URL');
                    $curl_arr[$keyUrl] = $link;

                    $data = $this->actionCurl($curl_arr, 'Дополнительное описание', $goodsId, $sites->id);

                    try {

                        $crawler = new Crawler($data);

                        $descAdd = $crawler->filterXPath($regDescAdd->regular)->html();

                        $descAdd = trim($descAdd);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        if (Description::find()->where(['goods_id' => $goods->id])->exists()) {

                            $desc = Description::findOne(['goods_id' => $goods->id]);
                            $desc->additional = $descAdd;
                            $desc->save();

                        } else {

                            $desc = new Description();
                            $desc->additional = $descAdd;
                            $desc->goods_id = $goods->id;
                            $desc->save();
                        }

                        $this->actionLogsSuccess($goods->id, 'desc_add');


                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 7, 'desc_add', $e);

                    }

                }else{

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    try {
                        $spiderPage = new Spider($link);

                        $spiderPage->crawl();

                        foreach ($spiderPage->getDownloader()->getPersistenceHandler() as $resource) {

                        $descAdd = $resource->getCrawler()->filterXpath($regDescAdd->regular)->html();

                        $descAdd = trim($descAdd);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        if (Description::find()->where(['goods_id' => $goods->id])->exists()) {

                            $desc = Description::findOne(['goods_id' => $goods->id]);
                            $desc->additional = $descAdd;
                            $desc->save();

                        } else {

                            $desc = new Description();
                            $desc->additional = $descAdd;
                            $desc->goods_id = $goods->id;
                            $desc->save();
                        }

                        $this->actionLogsSuccess($goods->id, 'desc_add');
                        }

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 7, 'desc_add', $e);

                    }
                }

                $q->ack($page->getDeliveryTag());

            } else {

                sleep(1);

            }

        }

        $rabbit->disconnect();
    }

    public function actionImages()
    {
        /**
         * Подключаемся к брокеру и точке обмена сообщениями
         */
        $rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
        $rabbit->connect();
        $channel = new AMQPChannel($rabbit);
        $queue = new AMQPExchange($channel);
        $queue->setName('amq.direct');

        /**
         * Добавляем очередь откуда будем брать страницы
         */
        $q = new AMQPQueue($channel);
        $q->setName('analyze_img');
        $q->declare();
        $q->bind('amq.direct', 'analyze_img');
        while (true) {

            /**
             * Обрабатываем пока в очереди не закончатся сообщения
             */

            $page = $q->get();

            if ($page) {

                $link = $page->getBody();

                $sites = Sites::find()->where(['id' => Goods::find()->select('sites_id')->where(['uri_goods' => $link])])->one();

                $regImages = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 8]);

                $uriNoImages = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 9]);

                $baseUrl = $sites->url;

                $idGoods = Goods::findOne(['uri_goods' => $link]);

                $id = $idGoods->id;

                if (Curl::find()->where(['sites_id' => $sites->id, 'status' => 1])->exists()) {

                    $goodsId = Goods::findOne(['uri_goods' => $link])->id;

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    $params = Curl::find()->where(['sites_id' => $sites->id])->asArray()->all();

                    $curl_arr = [];

                    foreach ($params as $param) {

                        $key = constant($param['key']);
                        $curl_arr[$key] = $param['value'];

                    }

                    $keyUrl = constant('CURLOPT_URL');
                    $curl_arr[$keyUrl] = $link;

                    $data = $this->actionCurl($curl_arr, 'Загрузка изображения', $goodsId,$sites->id);

                    try {

                        $crawler = new Crawler($data);

                        $images = $crawler->filterXPath($regImages->regular);

                        $path = \Yii::$app->basePath . '/web/uploads/images/' . $id;

                        mkdir($path, 0777, TRUE);

                        foreach ($images as $item) {

                            if (isset($uriNoImages->regular) && $item->value !== $uriNoImages->regular) {

                                $item->value = str_replace(' ', '%20', $item->value);

                                $lastPos = strripos($item->value, '/');

                                $nameFile = substr($item->value, $lastPos + 1);

                                if (file_put_contents($path . $nameFile, file_get_contents($baseUrl . $item->value))) {

                                    $image = new Images();
                                    $image->name = $nameFile;
                                    $image->goods_id = $id;
                                    $image->save();

                                    $this->actionLogsSuccess($id, 'image');

                                }

                            } elseif (isset($uriNoImages->regular) && $item->value == $uriNoImages->regular) {

                                    $log = Logs::findOne(['goods_id' => $id]);
                                    $log->image = 'Изображение отсутствует';
                                    $log->save();

                            } else {

                                $item->value = str_replace(' ', '%20', $item->value);

                                $lastPos = strripos($item->value, '/');

                                $nameFile = substr($item->value, $lastPos + 1);

                                if (file_put_contents($path . $nameFile, file_get_contents($baseUrl . $item->value))) {

                                    $image = new Images();
                                    $image->name = $nameFile;
                                    $image->goods_id = $id;
                                    $image->save();

                                    $this->actionLogsSuccess($id, 'image');
                                }
                            }
                        }

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 8, 'image', $e);

                    }

                }else{

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    try {

                        $spiderPage = new Spider($link);

                        $spiderPage->crawl();

                        foreach ($spiderPage->getDownloader()->getPersistenceHandler() as $resource) {

                            $images = $resource->getCrawler()->filterXpath($regImages->regular);

                            $path = \Yii::$app->basePath . '/web/uploads/images/' . $id ;

                            mkdir($path, 0777, TRUE);

                            foreach ($images as $item) {

                                if (isset($uriNoImages->regular) && $item->value !== $uriNoImages->regular) {

                                    $item->value = str_replace(' ', '%20', $item->value);

                                    $lastPos = strripos($item->value, '/');

                                    $nameFile = substr($item->value, $lastPos + 1);

                                    if (file_put_contents($path . $nameFile, file_get_contents($baseUrl . $item->value))) {

                                        $image = new Images();
                                        $image->name = $nameFile;
                                        $image->goods_id = $id;
                                        $image->save();

                                        $this->actionLogsSuccess($id, 'image');

                                    }

                                } elseif (isset($uriNoImages->regular) && $item->value == $uriNoImages->regular) {

                                    $log = Logs::findOne(['goods_id' => $id]);
                                    $log->image = 'Изображение отсутствует';
                                    $log->save();

                                } else {

                                    $item->value = str_replace(' ', '%20', $item->value);

                                    $lastPos = strripos($item->value, '/');

                                    $nameFile = substr($item->value, $lastPos + 1);

                                    if (file_put_contents($path . $nameFile, file_get_contents($baseUrl . $item->value))) {

                                        $image = new Images();
                                        $image->name = $nameFile;
                                        $image->goods_id = $id;
                                        $image->save();

                                        $this->actionLogsSuccess($id, 'image');
                                    }
                                }
                            }
                        }

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 8, 'image', $e);

                    }
                }

                $q->ack($page->getDeliveryTag());

            } else {

                sleep(1);

            }

        }

        $rabbit->disconnect();
    }

    public function actionPrice()
    {
        /**
         * Подключаемся к брокеру и точке обмена сообщениями
         */
        $rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
        $rabbit->connect();

        $channel = new AMQPChannel($rabbit);
        $queue = new AMQPExchange($channel);
        $queue->setName('amq.direct');

        /**
         * Добавляем очередь откуда будем брать страницы
         */
        $q = new AMQPQueue($channel);
        $q->setName('analyze_price');
        $q->declare();
        $q->bind('amq.direct', 'analyze_price');

        while (true) {

            /**
             * Обрабатываем пока в очереди не закончатся сообщения
             */

            $page = $q->get();

            if ($page) {

                $link = $page->getBody();

                $sites = Sites::find()->where(['id' => Goods::find()->select('sites_id')->where(['uri_goods' => $link])])->one();

                $regPrice = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 10]);

                if (Curl::find()->where(['sites_id' => $sites->id, 'status' => 1])->exists()) {

                    $goodsId = Goods::findOne(['uri_goods' => $link])->id;

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    $params = Curl::find()->where(['sites_id' => Goods::find()->select('sites_id')->where(['uri_goods' => $link])->one()])->asArray()->all();

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

                    $keyUrl = constant('CURLOPT_URL');
                    $curl_arr[$keyUrl] = $link;

                    $data = $this->actionCurl($curl_arr,'Загрузка цены', $goodsId, $sites->id);

                    try {

                        $crawler = new Crawler($data);

                        $price = $crawler->filterXPath($regPrice->regular)->text();

                        $price = trim($price);

                        $price = preg_replace("/[^0-9]/", '', $price);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        $pr = new Price();
                        $pr->price = $price;
                        $pr->goods_id = $goods->id;
                        $pr->save();

                        $this->actionLogsSuccess($goods->id, 'price');

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 10, 'price', $e);

                    }

                }else{

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    try {
                        $spiderPage = new Spider($link);

                        $spiderPage->crawl();

                        foreach ($spiderPage->getDownloader()->getPersistenceHandler() as $resource) {

                        $price = $resource->getCrawler()->filterXpath($regPrice->regular)->text();

                        $price = trim($price);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        $pr = new Price();
                        $pr->price = $price;
                        $pr->goods_id = $goods->id;
                        $pr->save();

                        $this->actionLogsSuccess($goods->id, 'price');
                        }
                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 10, 'price', $e);

                    }
                }

                $q->ack($page->getDeliveryTag());

            } else {

                sleep(1);

            }

        }

        $rabbit->disconnect();
    }

    public function actionManuf()
    {
        /**
         * Подключаемся к брокеру и точке обмена сообщениями
         */
        $rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
        $rabbit->connect();

        $channel = new AMQPChannel($rabbit);
        $queue = new AMQPExchange($channel);
        $queue->setName('amq.direct');

        /**
         * Добавляем очередь откуда будем брать страницы
         */
        $q = new AMQPQueue($channel);
        $q->setName('analyze_manufacturer');
        $q->declare();
        $q->bind('amq.direct', 'analyze_manufacturer');

        while (true) {

            /**
             * Обрабатываем пока в очереди не закончатся сообщения
             */

            $page = $q->get();

            if ($page) {

                $link = $page->getBody();

                $sites = Sites::find()->where(['id' => Goods::find()->select('sites_id')->where(['uri_goods' => $link])])->one();

                $regManuf = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 11]);

                if (Curl::find()->where(['sites_id' => $sites->id, 'status' => 1])->exists()) {

                    $goodsId = Goods::findOne(['uri_goods' => $link])->id;

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    $params = Curl::find()->where(['sites_id' => $sites->id])->asArray()->all();

                    $curl_arr = [];

                    foreach ($params as $param) {

                        $key = constant($param['key']);
                        $curl_arr[$key] = $param['value'];

                    }

                    $keyUrl = constant('CURLOPT_URL');
                    $curl_arr[$keyUrl] = $link;

                    $data = $this->actionCurl($curl_arr, 'Загрузка названия производителя', $goodsId, $sites->id);

                    try {

                        $crawler = new Crawler($data);

                        $manufacturer = $crawler->filterXPath($regManuf->regular)->text();

                        $manufacturer = trim($manufacturer);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        $man = new Manufacturer();
                        $man->name = $manufacturer;
                        $man->goods_id = $goods->id;
                        $man->save();

                        $this->actionLogsSuccess($goods->id, 'manufactured');

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 11, 'manufactured', $e);

                    }

                }else{

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    try {
                        $spiderPage = new Spider($link);

                        $spiderPage->crawl();

                        foreach ($spiderPage->getDownloader()->getPersistenceHandler() as $resource) {

                        $manufacturer = $resource->getCrawler()->filterXpath($regManuf->regular)->text();

                        $manufacturer = trim($manufacturer);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        $man = new Manufacturer();
                        $man->name = $manufacturer;
                        $man->goods_id = $goods->id;
                        $man->save();

                        $this->actionLogsSuccess($goods->id, 'manufactured');
                        }
                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 11, 'manufactured', $e);

                    }
                }

                $q->ack($page->getDeliveryTag());

            } else {
                sleep(1);
            }

        }

        $rabbit->disconnect();
    }

    public function actionProdAttr()
    {
        /**
         * Подключаемся к брокеру и точке обмена сообщениями
         */
        $rabbit = new AMQPConnection(array('host' => '127.0.0.1', 'port' => '5672', 'login' => 'guest', 'password' => 'guest'));
        $rabbit->connect();

        $channel = new AMQPChannel($rabbit);
        $queue = new AMQPExchange($channel);
        $queue->setName('amq.direct');

        /**
         * Добавляем очередь откуда будем брать страницы
         */
        $q = new AMQPQueue($channel);
        $q->setName('analyze_prod_attr');
        $q->declare();
        $q->bind('amq.direct', 'analyze_prod_attr');

        while (true) {

            /**
             * Обрабатываем пока в очереди не закончатся сообщения
             */

            $page = $q->get();

            if ($page) {

                $link = $page->getBody();

                $sites = Sites::find()->where(['id' => Goods::find()->select('sites_id')->where(['uri_goods' => $link])])->one();

                $regAttr = Xpath::findOne(['sites_id' => $sites->id, 'name_regular_id' => 12]);

                if (Curl::find()->where(['sites_id' => $sites->id, 'status' => 1])->exists()) {

                    $goodsId = Goods::findOne(['uri_goods' => $link])->id;

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    $params = Curl::find()->where(['sites_id' => $sites->id])->asArray()->all();

                    $curl_arr = [];

                    foreach ($params as $param) {

                        $key = constant($param['key']);
                        $curl_arr[$key] = $param['value'];

                    }

                    $keyUrl = constant('CURLOPT_URL');
                    $curl_arr[$keyUrl] = $link;

                    $data = $this->actionCurl($curl_arr, 'Загрузка атрибутов продукции', $goodsId, $sites->id);

                    try {

                        $crawler = new Crawler($data);

                        $prodAttr = $crawler->filterXPath($regAttr->regular)->html();

                        $prodAttr = trim($prodAttr);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        $prod = new ProductAttributes();
                        $prod->content = $prodAttr;
                        $prod->goods_id = $goods->id;
                        $prod->save();


                        $this->actionLogsSuccess($goods->id, 'prod_attr');

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 12, 'prod_attr', $e);

                    }

                }else{

                    if ($sites->usleep_stop != 0){
                        usleep(rand($sites->usleep_start, $sites->usleep_stop));
                    }

                    try {

                        $spiderPage = new Spider($link);

                        $spiderPage->crawl();

                        foreach ($spiderPage->getDownloader()->getPersistenceHandler() as $resource) {

                        $prodAttr = $resource->getCrawler()->filterXpath($regAttr->regular)->html();

                        $prodAttr = trim($prodAttr);

                        $goods = Goods::findOne(['uri_goods' => $link]);

                        $prod = new ProductAttributes();
                        $prod->content = $prodAttr;
                        $prod->goods_id = $goods->id;
                        $prod->save();


                        $this->actionLogsSuccess($goods->id, 'prod_attr');
                        }

                    } catch (Exception $e) {

                        $goods = Goods::find()->where(['uri_goods' => $link])->with('sites')->one();

                        $this->actionLogsFailed($goods->id, $goods->sites->id, 12, 'prod_attr', $e);

                    }
                }



                $q->ack($page->getDeliveryTag());

            } else {

                sleep(1);

            }

        }

        $rabbit->disconnect();
    }

    public function actionSpider($idSite, $seed)
    {
        $baseUrl = Sites::findOne($idSite)->url;

        // Create Spider
        $spider = new Spider($seed);

        $firstBlock = Xpath::findOne(['sites_id' => $idSite, 'name_regular_id' => 1]);
        $nameGroup = Xpath::findOne(['sites_id' => $idSite, 'name_regular_id' => 2]);
        $href = Xpath::findOne(['sites_id' => $idSite, 'name_regular_id' => 3]);
        $pagination = Xpath::findOne(['sites_id' => $idSite, 'name_regular_id' => 4]);
        $sites = Sites::findOne($idSite);

        $spider->getDiscovererSet()->set(new XPathExpressionDiscoverer($firstBlock->regular));

        if ($sites->queue != 0){
            $spider->getQueueManager()->maxQueueSize = $sites->queue;
        }

        // Execute crawl
        $spider->crawl();

        $resources = $spider->getDownloader()->getPersistenceHandler();

        foreach ($resources as $resource) {

            $uri = $resource->getCrawler()->getUri();

            if ($sites->down_url == $uri){
                continue;
            }

            try {

                $groupGoodsName = $resource->getCrawler()->filterXpath($nameGroup->regular)->text();

                $groupGoodsName = trim($groupGoodsName);

                if (!Groups::find()->where(['url_group' => $uri])->exists()) {
                    $group = new Groups();
                    $group->name = $groupGoodsName;
                    $group->url_group = $uri;
                    $group->save();
                } else {
                    $group = Groups::findOne(['url_group' => $uri]);
                }

            } catch (Exception $e) {

                if (!Groups::find()->where(['url_group' => $uri])->exists()) {

                    /* если отсутствует имя группы товаров, то имени присваиваем ссылку на группы товаров */

                    $group = new Groups();
                    $group->name = $uri;
                    $group->url_group = $uri;
                    $group->save();

                } else {

                    $group = Groups::findOne(['url_group' => $uri]);

                }
            }

            /* Производим запись кууков, что бы в дальнейшем не аутентифицироваться каждый раз в деманах */
            if (CurlAuth::find()->where(['sites_id' => $idSite, 'status' => 1])->exists()) {

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

                $this->actionCurl($curl_arr, 'Создание кууков',null, $idSite);
            }

            $hrefCount = $resource->getCrawler()->filterXpath($href->regular)->count();

            if ($hrefCount > 0) {

                $marker = [];

                foreach ($resource->getCrawler()->filterXpath($href->regular) as $item) {

                    $uriGoods = $baseUrl . $item->value;
                    $marker[] = $item->value;

                    if (!Goods::find()->where(['uri_goods' => $uriGoods])->exists()) {

                        $link = $baseUrl . $item->value;

                        echo "Scanning: $link\n";
                        /**
                         * Создаем товар в БД
                         */

                        $good = new Goods();
                        $good->uri_goods = $link;
                        $good->sites_id = $idSite;
                        $good->groups_id = $group->id;
                        $good->save();

                        $log = new Logs();
                        $log->goods_id = $good->id;
                        $log->save();

                        $this->queue->publish($link, 'analyze_name');

                        $this->queue->publish($link, 'analyze_desc');

                        $this->queue->publish($link, 'analyze_desc_add');

                        $this->queue->publish($link, 'analyze_img');

                        $this->queue->publish($link, 'analyze_price');

                        $this->queue->publish($link, 'analyze_manufacturer');

                        $this->queue->publish($link, 'analyze_prod_attr');

                    }
                }
            }

            $i = 0;

            while ($i = $i + 1) {

                /*Проверяем на существование пагинации*/
                if (isset($pagination->regular)) {

                    $uriPagin = $uri . $pagination->regular . $i;

                } else {

                    continue 2;

                }

                /*Проверяем сайты которые возвращают HTTP/1.1 404 Not Found и выходим из цикла*/
                try {
                    if (get_headers($uriPagin)[0] === 'HTTP/1.1 404 Not Found') {
                        continue 2;
                    }
                } catch (Exception $e) {
                    echo $e;
                }

                $spiderPage = new Spider($uriPagin);

                $spiderPage->crawl();

                foreach ($spiderPage->getDownloader()->getPersistenceHandler() as $resourcePage) {

                    foreach ($resourcePage->getCrawler()->filterXpath($href->regular) as $itemPage) {

                        /*Для сайтов без заголовков HTTP/1.1 404 Not Found, что бы не вылетали после первой страницы*/
                        if ($i !== 1 && $marker[0] === $itemPage->value) {
                            continue 4;
                        }

                        $uriGoodPage = $baseUrl . $itemPage->value;

                        if (!Goods::find()->where(['uri_goods' => $uriGoodPage])->exists()) {

                            $link = $baseUrl . $itemPage->value;

                            echo "Scanning: $link\n";
                            /**
                             * Создаем товар в БД
                             */
                            $good = new Goods();
                            $good->uri_goods = $link;
                            $good->sites_id = $idSite;
                            $good->groups_id = $group->id;
                            $good->save();

                            $log = new Logs();
                            $log->goods_id = $good->id;
                            $log->save();

                            $this->queue->publish($link, 'analyze_name');

                            $this->queue->publish($link, 'analyze_desc');

                            $this->queue->publish($link, 'analyze_desc_add');

                            $this->queue->publish($link, 'analyze_img');

                            $this->queue->publish($link, 'analyze_price');

                            $this->queue->publish($link, 'analyze_manufacturer');

                            $this->queue->publish($link, 'analyze_prod_attr');

                        }
                    }
                }
            }
        }

        return true;
    }

    public function actionLogsSuccess($id, $row)
    {
            $log = Logs::findOne(['goods_id' => $id]);
            $log->$row = 'Успешно скачано';
            $log->save();

    }

    public function actionLogsFailed($goodsId, $sitesId, $xpathId, $row, $e)
    {
        if (Xpath::find()->where(['sites_id' => $sitesId, 'name_regular_id' => $xpathId])->exists()) {

                $log = Logs::findOne(['goods_id' => $goodsId]);
                $log->$row = $e->getMessage();
                $log->save();

        } else {

                $log = Logs::findOne(['goods_id' => $goodsId]);
                $log->$row = 'Не задано в XPath выражениях';
                $log->save();

        }
    }

    public function actionCurl($curl_arr, $nameDemons, $idGoods ,$idSite){

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
            $logsCurl->name_daemons = $nameDemons;
            $logsCurl->goods_id = $idGoods;
            $logsCurl->sites_id = $idSite;
            $logsCurl->save();

        }

    }

}
