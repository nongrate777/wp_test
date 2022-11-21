<?php
/**
 * Plugin Name: AJAX фильтр для главной страницы
 */

class ajax_filter_objects extends WP_Widget
{

    function __construct()
    {
        $widget_ops = array(
            'classname' => 'ajax_filter_objects_widget',
            'description' => 'Отображает список агентств, по которым производиться фильтрация'
        );

        $control_ops = array(
            'width' => 250,
            'height' => 350,
            'id_base' => 'ajax_filter_objects_widget'
        );

        parent::__construct('ajax_filter_objects_widget', __('Главная: AJAX фильтр недвижимости', 'unite'), $widget_ops, $control_ops);
    }

    function widget($args, $instance)
    {
        if (!is_front_page()) {
            return;
        }

        $agency = new WP_Query([
            'post_type' => 'agency',
            'post_status' => 'publish'
        ]);

        if ($agency->posts) {
            ?>
            <div class="js-ajax-filter filter">
                <div class="filter__title">Фильтр недвижимости по агенству</div>
                <div class="filter__item">
                    <label>
                        <input type="radio" name="agency" value="0" checked>
                        <span>Все агентства</span>
                    </label>
                </div>
                <?php foreach ($agency->posts as $post) { ?>
                    <div class="filter__item">
                        <label>
                            <input type="radio" name="agency" value="<?= $post->ID; ?>">
                            <span><?= $post->post_title; ?></span>
                        </label>
                    </div>
                <?php } ?>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    function ajaxFilter() {
                        const filter = document.querySelector('.js-ajax-filter');
                        if (!filter) {
                            return;
                        }

                        const content = document.querySelector('.site-main');
                        const radios = filter.querySelectorAll('input[type="radio"]');
                        for (let radio of radios) {
                            radio.addEventListener('click', () => {
                                const formData = new FormData();
                                formData.append('action', 'filter');
                                formData.append('id', radio.value);

                                fetch('/wp-admin/admin-ajax.php', {
                                    method: 'POST',
                                    body: formData,
                                    credentials: 'same-origin'
                                })
                                    .then(res => res.json())
                                    .then(res => {
                                        if (res.success) {
                                            content.innerHTML = '';
                                            content.insertAdjacentHTML('beforeend', res.data.content);
                                        }
                                    })
                                ;
                            });
                        }
                    }

                    ajaxFilter();
                });
            </script>
            <?php
        }
    }
}