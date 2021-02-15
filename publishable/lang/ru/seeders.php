<?php

return [
    'data_rows'  => [
        'author'           => 'Автор',
        'avatar'           => 'Аватар',
        'body'             => 'Содержимое',
        'category'         => 'Категория',
        'created_at'       => 'Дата создания',
        'display_name'     => 'Отображаемое имя',
        'email'            => 'Email',
        'excerpt'          => 'Отрывок',
        'featured'         => 'Рекомендовано',
        'id'               => 'ID',
        'key'              => 'Ключ',
        'meta_description' => 'Meta Description',
        'meta_keywords'    => 'Meta Keywords',
        'name'             => 'Имя',
        'order'            => 'Сортировка',
        'page_image'       => 'Изображение Страницы',
        'parent'           => 'Родитель',
        'password'         => 'Пароль',
        'post_image'       => 'Изображение Статьи',
        'remember_token'   => 'Токен восстановления',
        'role'             => 'Роль',
        'seo_title'        => 'SEO Название',
        'slug'             => 'Slug (ЧПУ)',
        'status'           => 'Статус',
        'table_name'       => 'Название таблицы',
        'title'            => 'Название',
        'updated_at'       => 'Дата обновления',
    ],
    'data_types' => [
        'category' => [
            'singular' => 'Категория',
            'plural'   => 'Категории',
        ],
        'menu'     => [
            'singular' => 'Меню',
            'plural'   => 'Меню',
        ],
        'page'     => [
            'singular' => 'Страница',
            'plural'   => 'Страницы',
        ],
        'permission'     => [
            'singular' => 'Разрешение',
            'plural'   => 'Разрешения',
        ],
        'post'     => [
            'singular' => 'Статья',
            'plural'   => 'Статьи',
        ],
        'role'     => [
            'singular' => 'Роль',
            'plural'   => 'Роли',
        ],
        'user'     => [
            'singular' => 'Пользователь',
            'plural'   => 'Пользователи',
        ],
    ],
    'menu_items' => [
        'bread'        => 'BREAD',
        'categories'   => 'Категории',
        'compass'      => 'Compass',
        'dashboard'    => 'Панель управления',
        'database'     => 'База данных',
        'media'        => 'Медиа',
        'menu_builder' => 'Конструктор Меню',
        'modules'      => 'Модули',
        'pages'        => 'Страницы',
        'permissions'  => 'Разрешения',
        'posts'        => 'Статьи',
        'roles'        => 'Роли',
        'roles_and_users'   => 'Роли и пользователи',
        'settings'     => 'Настройки',
        'tools'        => 'Инструменты',
        'users'        => 'Пользователи',
    ],
    'roles'      => [
        'admin' => 'Администратор',
        'user'  => 'Обычный Пользователь',
    ],
    'settings'   => [
        'admin' => [
            'background_image'           => 'Фоновое Изображение для Админки',
            'description'                => 'Описание Админки',
            'description_value'          => 'Добро пожаловать в Voyager. Пропавшую Админку для Laravel',
            'google_analytics_client_id' => 'Google Analytics Client ID (используется для панели администратора)',
            'icon_image'                 => 'Иконка Админки',
            'loader'                     => 'Загрузчик Админки',
            'title'                      => 'Название Админки',
        ],
        'site'  => [
            'description'                  => 'Описание Сайта',
            'google_analytics_tracking_id' => 'Google Analytics Tracking ID',
            'logo'                         => 'Логотип Сайта',
            'title'                        => 'Название Сайта',
        ],
    ],
];
