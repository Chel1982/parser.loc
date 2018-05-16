<?php

namespace app\commands;

use app\models\Curl;
use app\models\CurlAuth;
use app\models\LogsCurl;
use app\models\Sites;
use app\models\Xpath;
use Symfony\Component\DomCrawler\Crawler;
use yii\console\Controller;
use Exception;

class ParserCatPriceController extends Controller
{
    public function actionInit()
    {

        $sites = Sites::find()->where(['status_cat_price' => 1])->asArray()->all();

        foreach ($sites as $site) {

            $this->actionCurlSpider($site['id']);

        }
    }

    public function actionCurlSpider($idSite)
    {
        $ch = curl_init();
        curl_setopt ($ch, CURLOPT_URL, 'http://gastrorag.ru/dealers/cabinet/');

// I changed UA here
        curl_setopt ($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');

        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt ($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $html = curl_exec($ch);

// I added this
        file_put_contents('test.txt', print_r($html, 1));die();
        die();

        $regCat = Xpath::findOne(['sites_id' => $idSite, 'name_regular_id' => 1]);
        $sites = Sites::findOne($idSite);

        /* Производим запись кууков, что бы в дальнейшем не аутентифицироваться каждый раз в деманах */
        if (Sites::find()->where(['id' => $idSite,'status_cat_price' => 1])->exists()) {

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

        $curl_arr = [];

        $params = Curl::find()->where(['sites_id' => $idSite])->asArray()->all();

        foreach ($params as $param) {

            $key = constant($param['key']);
            $curl_arr[$key] = $param['value'];

        }

        /*Получаем DOM дерево*/
        $data = $this->actionCurl($curl_arr, false , $idSite);

        $crawler = new Crawler($data);

        /* Получаем узел с ссылками на скачиваемые файлы*/
        $catalogs = $crawler->filterXPath($regCat->regular);

        /*Задаем путь куда сохранять каталоги*/
        $path = \Yii::$app->basePath . '/web/uploads/catalogs_price/' . $sites->id . '/';

        $downUrl = $sites->down_url;

        foreach ($catalogs as $catalog){

            if (!file_exists($path)){
                mkdir($path, 0777, TRUE);
                chmod($path,0777);
            }

            /*Удаляем ненужные символы из названия*/
            $nameFile = $catalog->parentNode->nodeValue;
            $nameFile = substr($nameFile, 0, strpos($nameFile, "&nbsp"));

            /*Задаем имя файла*/
            $nameFile = $nameFile . '.xls';

            $nameFile = trim($nameFile);

            /*Задаем нужную константу с ссылкой*/
            $keyUrl = constant('CURLOPT_URL');
            $curl_arr[$keyUrl] = $downUrl . $catalog->nodeValue;

            /* Скачиваем файлы при помощи cURL'a*/

            $file = $this->actionCurl($curl_arr, false , $idSite);

            /*Сохраняем файлы в проект*/
            file_put_contents($path . $nameFile, $file);

        }
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

}