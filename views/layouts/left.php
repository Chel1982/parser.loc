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
                    ['label' => 'Логи парсинга', 'url' => ['/logs/index']],
                    [
                        'label' => 'Some tools',
                        'icon' => 'share',
                        'url' => '#',
                        'items' => [
                            ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii'],],
                            ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug'],],
                            [
                                'label' => 'Level One',
                                'icon' => 'circle-o',
                                'url' => '#',
                                'items' => [
                                    ['label' => 'Level Two', 'icon' => 'circle-o', 'url' => '#',],
                                    [
                                        'label' => 'Level Two',
                                        'icon' => 'circle-o',
                                        'url' => '#',
                                        'items' => [
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                            ['label' => 'Level Three', 'icon' => 'circle-o', 'url' => '#',],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ) ?>

    </section>

</aside>
