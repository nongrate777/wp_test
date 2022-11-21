<?php

include_once(__DIR__ . "/inc/widgets/widget-ajax-filter-objects.php");
register_widget( 'ajax_filter_objects' );


add_action('wp_ajax_filter', 'filter');
add_action('wp_ajax_nopriv_filter', 'filter');
function filter()
{
    if (empty($_POST)) {
        wp_send_json_error();
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
    $posts = new WP_Query([
        'post_type' => 'flats',
        'post_status' => 'publish',
        'post_parent' => !empty($id) ? $id : '',
        'posts_per_page' => 6,
    ]);

    ob_start();
    if ($posts->posts) {
        global $post;
        foreach ($posts->posts as $post) {
            setup_postdata($post);
            ?>
            <div class="col-sm-12 col-md-6 lighting">
                <?php include(__DIR__ . '/content-main.php'); ?>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo 'У данного агенства нет объектов.';
    }
    $content = ob_get_clean();

    wp_send_json_success([
        'content' => $content
    ]);
}

// добавляем таксономию к объектам недвижимости
add_action('init', 'create_realestate', 0);
function create_realestate()
{
    $args = array(

        'label' => _x('Тип недвижимости', 'taxonomy general name'),
        'labels' => array(
            'name' => _x('Тип недвижимости', 'taxonomy general name'),
            'singular_name' => _x('R', 'taxonomy singular name'),
            'menu_name' => __('Типы недвижимости'),
            'all_items' => __('Все'),
            'edit_item' => __('Изменить тип'),
            'view_item' => __('Просмотреть рубрику'),
            'update_item' => __('Обновить рубрику'),
            'add_new_item' => __('Добавить тип недвижимости'),
            'new_item_name' => __('Название'),
            'parent_item' => __('Родительская'),
            'parent_item_colon' => __('Родительская:'),
            'search_items' => __('Поиск рубрики'),
            'popular_items' => null,
            'separate_items_with_commas' => null,
            'add_or_remove_items' => null,
            'choose_from_most_used' => null,
            'not_found' => __('Рубрик нет таких'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => true,
        'show_in_quick_edit' => true,
        'meta_box_cb' => null,
        'show_admin_column' => false,
        'description' => '',
        'hierarchical' => true,
        'update_count_callback' => '',
        'query_var' => true,
        'rewrite' => array(
            'slug' => '',
            'with_front' => false,
            'hierarchical' => true,
            'ep_mask' => EP_NONE,
        ),
        'sort' => null,
        '_builtin' => false,
    );
    register_taxonomy('realestate', array('flats'), $args);
}

// добавляем тип материала объекты недвижимости
add_action('init', 'register_post_realestate', 0);
function register_post_realestate()
{
    $args = array(
        'label'  => _x('Недвижимость', 'Post Type General Name', 'text_domain'),
        'labels' => array(
            'name' => _x('Недвижимость', 'Post Type General Name', 'text_domain'),
            'singular_name' => _x('Объект недвижимости', 'Post Type Singular Name', 'text_domain'),
            'add_new' => __('Добавить объект недвижимости', 'text_domain'),
            'add_new_item' => __('Добавить объект недвижимости', 'text_domain'),
            'edit_item' => __('Редактировать объект недвижимости', 'text_domain'),
            'new_item' => __('Новый объект', 'text_domain'),
            'view_item' => __('Просмотреть объект', 'text_domain'),
            'search_items' => __('Поиск объектов', 'text_domain'),
            'not_found' => __('Объектов не найдено', 'text_domain'),
            'not_found_in_trash' => __('Объектов в корзине не найдено', 'text_domain'),
            'parent_item_colon' => null,
            'all_items' => __('Все объекты', 'text_domain'),
            'archives' => __('Архивы объектов', 'text_domain'),
            'insert_into_item' => __('Вставить в объект', 'text_domain'),
            'uploaded_to_this_item' => _x('Загружен для:', 'text_domain'),
            'featured_image' => __('Фото объекта', 'text_domain'),
            'set_featured_image' => __('Задать фото', 'text_domain'),
            'remove_featured_image' => __('Удалить фото', 'text_domain'),
            'use_featured_image' => __('Использовать фото', 'text_domain'),
            'menu_name' => __('Объекты недвижимости', 'text_domain'),
            'name_admin_bar' => __('Объект недвижимости', 'text_domain'),
            'items_list' => __('Список объектов', 'text_domain'),
            'items_list_navigation' => __('Постраничная навигация', 'text_domain'),
            'filter_items_list' => __('Фильтр', 'text_domain'),
        ),
        'description' => '',
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'attributes' => 'Шаблон для недвижки',
        'menu_position' => 2,
        'menu_icon' => 'dashicons-building',
        'map_meta_cap' => null,
        'hierarchical' => true,

        'supports' => array(
            'title', // Заголовок объекта типа записи.
            'editor', // Редактор контента.
            'thumbnail', // Миниатюра.
            'custom-fields', // Произвольные поля.
            'comments', // Комментарии.
            'revisions', // Сохраняет версии.

        ),
        'register_meta_box_cb' => null,
        'taxonomies' => array('realestate'),
         

        'permalink_epmask' => EP_PERMALINK,
        'query_var' => true,
        'can_export' => true,
        'delete_with_user' => null,
        'show_in_rest' => false,
        'rest_base' => $post_type,
        '_builtin' => false,
     
    );
    register_post_type('flats', $args);
}

// добавляем тип материала - агентства
add_action('init', 'register_post_agency', 0);
function register_post_agency()
{
    $args = array(
        'label'  => _x('Агентство', 'Post Type General Name', 'text_domain'),
        'labels' => array(
            'name' => _x('Агентство', 'Post Type General Name', 'text_domain'),
            'singular_name' => _x('Агентство', 'Post Type Singular Name', 'text_domain'),
            'add_new' => __('Добавить агентство', 'text_domain'),
            'add_new_item' => __('Добавить агентство', 'text_domain'),
            'edit_item' => __('Редактировать агентство', 'text_domain'),
            'new_item' => __('Новое агентство', 'text_domain'),
            'view_item' => __('Просмотреть агентство', 'text_domain'),
            'search_items' => __('Поиск агентства', 'text_domain'),
            'not_found' => __('Агентство не найдено', 'text_domain'),
            'not_found_in_trash' => __('Объектов в корзине не найдено', 'text_domain'),
            'parent_item_colon' => null,
            'all_items' => __('Все агентства', 'text_domain'),
            'archives' => __('Архивы агентств', 'text_domain'),
            'insert_into_item' => __('Вставить в агентство', 'text_domain'),
            'uploaded_to_this_item' => _x('Загружен для:', 'text_domain'),
            'featured_image' => __('Фото конторы', 'text_domain'),
            'set_featured_image' => __('Задать фото', 'text_domain'),
            'remove_featured_image' => __('Удалить фото', 'text_domain'),
            'use_featured_image' => __('Использовать фото', 'text_domain'),
            'menu_name' => __('Агентства', 'text_domain'),
            'name_admin_bar' => __('Агентство', 'text_domain'),
            'items_list' => __('Список агентств', 'text_domain'),
            'items_list_navigation' => __('Постраничная навигация', 'text_domain'),
            'filter_items_list' => __('Фильтр', 'text_domain'),
        ),
        'description' => '',
        'public' => true,
        'exclude_from_search' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'show_in_menu' => true,
        'show_in_admin_bar' => true,
        'attributes' => 'Шаблон для агентства',
        'menu_position' => 2,
        'menu_icon' => 'dashicons-businesswoman',
        'map_meta_cap' => null,
        'hierarchical' => true,

        'supports' => array(
            'title', // Заголовок объекта типа записи.
            'editor', // Редактор контента.
            'thumbnail', // Миниатюра.
            'custom-fields', // Произвольные поля.
            'comments', // Комментарии.
            'revisions', // Сохраняет версии.

        ),
        'register_meta_box_cb' => null,
        'has_archive' => true,
        'rewrite' => array(
            'with_front' => false,
            'feeds' => false,
            'pages' => true,
            'has_archive' => true,
        ),

       
        'query_var' => true,
        'can_export' => true,
        'delete_with_user' => null,
        'show_in_rest' => false,
        'rest_base' => $post_type,
        '_builtin' => false,
        
    );
    register_post_type('agency', $args);
}

// Связываем два типа материалов - агентства и объекты недвижимости
add_action('add_meta_boxes', function () {
    add_meta_box('agency_ofrealestate', 'Выбор агентства', 'agency_ofrealestate_metabox', 'flats', 'side', 'low');
}, 1);

function agency_ofrealestate_metabox($post)
{
    $agency = get_posts(array('post_type' => 'agency', 'posts_per_page' => -1, 'orderby' => 'post_title', 'order' => 'ASC'));

    if ($agency) {
        echo '
		<div style="max-height:200px; overflow-y:auto;">
			<ul>
		';
        foreach ($agency as $agency) {
            echo '
			<li><label>
				<input type="radio" name="post_parent" value="' . $agency->ID . '" ' . checked($agency->ID, $post->post_parent, 0) . '> ' . esc_html($agency->post_title) . '
			</label></li>
			';
        }
        echo '
			</ul>
		</div>';
    } else
        echo 'Контор пока нет';
}

// удаляем slug ненужный, вызывал конфликты при переходе в конкретную ноду

function custom_post_type_rewrite() {
    global $wp_rewrite;
    
    $wp_rewrite->add_rewrite_tag("%agency%", '([^/]+)', "agency=");
    $wp_rewrite->add_permastruct('agency', '%agency%' );
}
 
add_action( 'init', 'custom_post_type_rewrite');

function custom_rewrite_conflicts( $request ) {
    if(!is_admin())
        $request['post_type'] = array('agency', 'post', 'page');  
    return $request;
}
add_filter( 'request',  'custom_rewrite_conflicts' );

function custom_post_type_rewrite_flats() {
    global $wp_rewrite;
     
    $wp_rewrite->add_rewrite_tag("%flats%", '([^/]+)', "flats=");
    $wp_rewrite->add_permastruct('flats', '%flats%' );
}
 
add_action( 'init', 'custom_post_type_rewrite_flats');

function custom_rewrite_conflicts_flats( $request ) {
    if(!is_admin())
        $request['post_type'] = array('flats', 'post', 'page');  
    return $request;
}
add_filter( 'request',  'custom_rewrite_conflicts_flats' );
