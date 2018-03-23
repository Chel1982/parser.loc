<?php
/* @var $this yii\web\View */

use Symfony\Component\DomCrawler\Crawler;
use VDB\Spider\Spider;

$this->title = 'Parser API';
?>

<div class="site-index">

    <div class="jumbotron">
        <h1>Добро пожаловать в Админку</h1>
    </div>

    <div>
        <h4>Здесь вы сможете проверить работосопосбность Xpath выражений. Выражения работают только на сайтах, на котрых не надо проходит аутентификацию.</h4>
    </div>
    <div>
        <h5>Выражение скачивает text</h5>
        <form name="text" action="" method="post">

            <input style="width: 500px" name="text_url"> Введите url сайта<br>
            <input style="width: 500px" name="text_xpath"> Введите Xpath выражение
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <p><input type="submit"></p>

        </form>
    </div>

    <div>
        <h5>Выражение скачивает html</h5>
        <form name="html" action="" method="post">

            <input style="width: 500px" name="html_url"> Введите url сайта<br>
            <input style="width: 500px" name="html_xpath"> Введите Xpath выражение
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <p><input type="submit"></p>

        </form>
    </div>
    <div>
        <h5>Проверяем работу proxy</h5>
        <form name="html" action="" method="post">

            <input style="width: 500px" name="proxy"> Введите proxy url<br>
            <input style="width: 500px" name="proxy_url"> Введите url сайта<br>
            <input style="width: 500px" name="proxy_xpath"> Введите Xpath выражение text
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <p><input type="submit"></p>

        </form>
    </div>

</div>

<?php

if (Yii::$app->request->post()){

    if (Yii::$app->request->post('text_url') && Yii::$app->request->post('text_xpath')){

        $spider = new Spider(Yii::$app->request->post('text_url'));

        $spider->crawl();

        foreach ($spider->getDownloader()->getPersistenceHandler() as $resource) {

            try {

                $data = $resource->getCrawler()->filterXpath(Yii::$app->request->post('text_xpath'))->text();

                echo 'Результат запроса text: ' . '<br>';
                echo '<b>' . $data . '</b>';

            } catch (Exception $e) {
                echo 'Результат запроса text: ' . '<br>';
                echo $e->getMessage();

            }

        }


    }
    if (Yii::$app->request->post('html_url') && Yii::$app->request->post('html_xpath')){

        $spider = new Spider(Yii::$app->request->post('html_url'));

        $spider->crawl();

        foreach ($spider->getDownloader()->getPersistenceHandler() as $resource) {

            try {

                $data = $resource->getCrawler()->filterXpath(Yii::$app->request->post('html_xpath'))->html();

                echo 'Результат запроса html: ' . '<br>';
                echo '/**********************************************************************************************/' . '<br>';
                echo '<b>' . $data . '</b>' . '<br>';
                echo '/**********************************************************************************************/';

            } catch (Exception $e) {
                echo 'Результат запроса html: ' . '<br>';
                echo $e->getMessage();

            }

        }
    }

    if (\Yii::$app->request->post('proxy_url') && \Yii::$app->request->post('proxy_xpath') && \Yii::$app->request->post('proxy')){
        $ch = curl_init();
        $timeout = 10;
        curl_setopt($ch,CURLOPT_URL, \Yii::$app->request->post('proxy_url'));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_PROXY, \Yii::$app->request->post('proxy'));
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $data = curl_exec($ch);

        echo 'Результат запроса proxy: ' . '<br>';

        if($data === false)
        {
            echo '<b>' . 'Curl error: ' . curl_error($ch) . '</b><br>';
        }
        curl_close($ch);

        try{
            $crawler = new Crawler($data);

            $name = $crawler->filterXPath(\Yii::$app->request->post('proxy_xpath'))->text();

            echo '<b>' . $name . '</b>';

            }catch (Exception $e) {

            echo 'Результат запроса proxy: ' . '<br>';
            echo $e->getMessage();

        }

    }
}
?>