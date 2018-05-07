<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <div class="pull-left info">

                    <p style="color: white "><?= Yii::$app->user->identity->username ?></p>

                </div>
            </div>

        </div>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget'=> 'tree'],
                'items' => [
                    ['label' => 'Сайты для парсинга', 'url' => ['/sites/index']],
                    ['label' => 'XPath выражения', 'url' => ['/xpath/index']],
                    [
                        'label' => 'cURL раздел',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Значения curl\'а', 'url' => ['/curl/index']],
                            ['label' => 'Аутентификация curl\'ом', 'url' => ['/curl-auth/index']],
                            ['label' => 'Логи curl\'а', 'url' => ['/logs-curl/index']],
                        ],
                    ],
                    ['label' => 'Скачанные товары', 'url' => ['/goods/index']],
                    ['label' => 'Скачанные группы товаров', 'url' => ['/groups/index']],
                    ['label' => 'Наценка на товары', 'url' => ['/mark-up-goods/index']],
                    ['label' => 'Логи парсинга', 'url' => ['/logs/index']],
                    ['label' => 'Логи проверки цены', 'url' => ['/logs-price/index']],
                ],
            ]
        ) ?>

    </section>

</aside>
