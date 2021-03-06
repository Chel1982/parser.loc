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
                    ['label' => 'Скачанные производители', 'url' => ['/manufacturer/index']],
                    ['label' => 'Наценки товаров', 'url' => ['/mark-up-goods/index']],
                    ['label' => 'Настройка конфига', 'url' => ['/config/index']],
                    ['label' => 'Логи парсинга', 'url' => ['/logs/index']],
                    ['label' => 'Логи цены и наличия', 'url' => ['/logs-price-avail/index']],
                ],
            ]
        ) ?>

    </section>

</aside>
