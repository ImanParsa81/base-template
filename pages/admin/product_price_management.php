<?php
$posts_per_page = 10;

// شماره صفحه جاری
$paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
$category_filter = isset($_GET['category_filter']) ? intval($_GET['category_filter']) : '';
$supplier_filter = isset($_GET['supplier_filter']) ? intval($_GET['supplier_filter']) : '';
$search_filter = isset($_GET['search_filter']) ? sanitize_text_field($_GET['search_filter']) : '';

$args = array(
    'post_type'      => 'product',
    'posts_per_page' => -1, // دریافت همه محصولات (صفحه‌بندی بعداً روی آرایه نهایی انجام می‌شود)
    'tax_query'      => !empty($category_filter) ? array(
        array(
            'taxonomy' => 'product_cat',
            'field'    => 'ID',
            'terms'    => $category_filter
        )
    ) : array(),
    'meta_query'     => !empty($supplier_filter) ? array(
        array(
            'key'     => 'selected_supplier', // جایگزین با کلید متای خاص موردنظر
            'value'   => "$supplier_filter", // مقدار متا
            'compare' => '='
        )
    ) : array(),
);
if (!empty($search_filter)) {
    $args['s'] = $search_filter;
}

$products_query = new WP_Query($args);

// ایجاد آرایه‌ای از محصولات برای نمایش
$final_product_ids = array();

if ($products_query->have_posts()) :
    while ($products_query->have_posts()) :
        $products_query->the_post();
        $product_id = get_the_ID();
        $product = wc_get_product($product_id);

        if ($product->is_type('variable')) {
            // محصول متغیر است، متغیرهای آن را اضافه کنیم و خود محصول اصلی را حذف کنیم
            $variations = $product->get_children(); // دریافت IDهای متغیرها
            if (!empty($variations)) {
                foreach ($variations as $variation_id) {
                    $final_product_ids[] = $variation_id;
                }
            }
        } else {
            // محصول ساده است، خودش را اضافه کنیم
            $final_product_ids[] = $product_id;
        }
    endwhile;
endif;

wp_reset_postdata();

// تعداد کل محصولات برای صفحه‌بندی
$total_products = count($final_product_ids);
$total_pages = ceil($total_products / $posts_per_page);
$current_page = max(1, $paged);
$offset = ($current_page - 1) * $posts_per_page;

// **اعمال صفحه‌بندی روی آرایه نهایی**
$paged_product_ids = array_slice($final_product_ids, $offset, $posts_per_page);

?>

<div class="wrap">
    <h2>مدیریت قیمت محصولات</h2>

    <form method="get" style="margin-bottom: 25px">


        <input type="hidden" name="page" value="b2wall_menus">
        <div class="nirweb_space_betwin_1"></div>

        <div class="filter_label_import">

            <!-- جستو جوی محصول -->
            <div>
                <label for="search_filter">جستجو محصول : </label>
                <input type="text" name="search_filter" id="search_filter" placeholder="جستجو کنید .. " value="<?= $search_filter ?? '' ?>">
            </div>

            <!-- فیلتر تأمین‌کنندگان -->
            <div>
                <label for="supplier_filter">تامین کننده: </label>
                <select name="supplier_filter" id="supplier_filter">
                    <option value="">همه</option>
                    <?php
                    // دریافت لیست تأمین‌کنندگان
                    $suppliers_query = new WP_Query([
                        'post_type'      => 'supplier',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish'
                    ]);

                    if ($suppliers_query->have_posts()) {
                        while ($suppliers_query->have_posts()) {
                            $suppliers_query->the_post();
                            $supplier_id   = get_the_ID();
                            $supplier_name = get_the_title();
                            $selected      = ($supplier_id == $supplier_filter) ? 'selected' : '';
                            echo '<option value="' . esc_attr($supplier_id) . '" ' . $selected . '>' . esc_html($supplier_name) . '</option>';
                        }
                        wp_reset_postdata();
                    }
                    ?>
                </select>
            </div>


            <!-- فیلتر دسته‌بندی‌ها -->
            <div>
                <label for="category_filter">دسته بندی: </label>
                <select name="category_filter" id="category_filter">

                    <div class="nirweb_space_betwin_2"></div>

                    <option value="">همه</option>
                    <?php

                    $categories = get_terms(['taxonomy' => 'product_cat', 'hide_empty' => false]);
                    if (!empty($categories) && !is_wp_error($categories)) {
                        foreach ($categories as $category) {
                            $selected = ($category->term_id == $category_filter) ? 'selected' : '';
                            echo '<option value="' . esc_attr($category->term_id) . '" ' . $selected . '>' . esc_html($category->name) . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>



        </div>

        <input type="submit" value="فیلتر کردن" class="button">
    </form>

    <div class="table_container">

        <table class="widefat fixed" cellspacing="0">
        <thead>
        <tr class="nirweb_tabel_manage_options_tr">

            <th>نام محصول</th>
            <th>تامین کنندگان</th>
            <th>دسته بندی</th>
            <th>متغییر ها</th>
            <th>قیمت</th>
            <th> ویرایش</th>

        </tr>
        </thead>
        <tbody>
        <?php if ($paged_product_ids ) : ?>
            <?php foreach ($paged_product_ids as $product_id) { ?>
                <?php
                $parent_id = wp_get_post_parent_id($product_id);
                $get_the_id = $parent_id ? $parent_id : $product_id;

                $product = wc_get_product($product_id);

                $supplier_id = get_post_meta($get_the_id, 'selected_supplier', true);
                $supplier_name = $supplier_id ? get_the_title($supplier_id) : 'ندارد';


                ?>


                <tr class="nirweb_tabel_manage_options_td">

                    <td>
                        <a href="<?= get_edit_post_link($get_the_id) ?>"><?= get_the_title($product_id) ?></a>
                    </td>
                    <td>
                        <?php


                        ?>
                        <div class="show_name_supplier active">
                            <span class="supplier-name-text"><?= esc_html($supplier_name); ?></span>
                            <button class="edit-supplier-button">ویرایش</button>
                        </div>
                        <div class="seach_supplier">
                            <div class="seach_supplier_container">
                                <input placeholder="جستوجو ...">
                                <button class="but_seach_supplier_close">بستن</button>
                            </div>
                            <div product_id="<?= $get_the_id ?>"
                                 class="List_search_supplier_container"></div>
                        </div>
                        <?php

                        ?>
                    </td>
                    <td><?php
                        $terms = get_the_terms($get_the_id, 'product_cat'); // دریافت دسته‌بندی محصول والد
                        if ($terms && !is_wp_error($terms)) :
                            $categories = array();
                            foreach ($terms as $term) {
                                echo $term->name;
                            }

                        endif;
                        ?></td>
                    <td><?php
                        if ($product->is_type('variation')) {
                            $variation_attributes = $product->get_variation_attributes();
                            if (!empty($variation_attributes)) {
                                $i= 0;
                                foreach ($variation_attributes as $attribute_name => $attribute_value) {
                                    echo $i ? ' - ': '';
                                    echo esc_html(urldecode($attribute_value));
                                    $i++;
                                }
                            }
                        }

                        ?></td>
                    <td><input class="nirweb_new_price" type="text"
                               value="<?= is_numeric($product->get_price()) ? number_format($product->get_price()) : '' ?>">
                        تومان
                    </td>
                    <td>
                        <button class="nirweb_cheng_price_in_admin"
                                get_id_moteghayer="
                        <?= $product_id ?>"
                                witch_mode="<?= $parent_id ? 1 : 2 ?>"
                                class="nirweb_manage_options_edite_price"> ویرایش قیمت محصول
                        </button>
                    </td>

                </tr>
            <?php } ?>

        <?php else: ?>
            <tr>
                <td colspan="6">هیچ موردی یافت نشد.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    </div>

    <div class="pagination">
        <?php
        echo paginate_links(array(
            'base'      => add_query_arg('paged', '%#%'),
            'format'    => '',
            'prev_text' => __('&laquo;'),
            'next_text' => __('&raquo;'),
            'total'     => $total_pages,
            'current'   => $current_page
        ));
        ?>
    </div>
</div>





<style>



    form {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        flex-wrap: wrap;
    }

    .filter_label_import {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .button {
        margin-top: 20px !important;
    }




    .nirweb_new_price {
        width: 100%;
        line-height: 0 !important;
        min-height: 0 !important;
        padding: 2px 5px !important;
    }
    
    .pagination{
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 25px;
    }
    .pagination > *{
        width: 35px;
        height: 35px;
        background: #ffffff;
        border: solid 1px #e57a10;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        color: #e57a10;
    }
    .pagination > .current{
        background: #e57a10;
        color: #ffffff;
    }
    /* تغییر رنگ ردیف‌ها */
    .widefat tbody tr:nth-child(odd) {
        background-color: #faf7f4;
    }

    .widefat tbody tr:nth-child(even) {
        background-color: #fff;
    }

    /* تغییر رنگ در صورت hover روی ردیف‌ها */
    .widefat tbody tr:hover {
        background-color: #e0e0e0;
    }

    .nirweb_tabel_manage_options_tr th {
        text-align: center;
    }

    .nirweb_tabel_manage_options_tr_td td {
        text-align: center;
        border: 1px solid rgba(21, 21, 21, 0.11);
    }

    .nirweb_cheng_price_in_admin {
        border: none;
        outline: none;
        background-color: #ff7545;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }

    .nirweb_tabel_manage_options_td td {
        border: 1px solid rgba(0, 0, 0, 0.15);
        text-align: center;
    }

    .nirweb_space_betwin_1 {
        margin-top: 10px;
    }

    .show_name_supplier {
        display: none;
    }

    .seach_supplier {
        display: none;
    }

    .show_name_supplier.active {
        display: block;
    }

    .seach_supplier.active {
        display: block;
    }

    .List_search_supplier_container {
        position: relative;
    }

    .List_search_supplier {
        background-color: #ff7545;
        /*position: absolute;*/
        top: 0px;
        width: 100%;
        display: flex;
        justify-content: start;
        flex-direction: column;
        align-items: start;
        border-radius: 5px;
        z-index: 50;
    }

    .List_search_supplier .items_ {
        color: white;
        width: 100%;
        border-bottom: 1px solid white;
        text-align: start;
        padding: 8px 4px 5px 4px;
        margin: 0px;
    }

    .List_search_supplier .items_:hover {
        cursor: pointer;
    }

    .seach_supplier_container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        flex-direction: row;
        flex-wrap: nowrap;
        margin-bottom: 7px;
    }

    .seach_supplier_container input {
        width: 100%;
    }

    .nirweb_supplier_louding {
        display: none;
    }

    .nirweb_supplier_louding.active {
        display: block;
    }

    .List_search_supplier {
        height: 100%;
        max-height: 200px;
        overflow-y: auto;
    }

    .show_name_supplier {
        display: flex !important;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }

    .show_name_supplier .supplier-name-text {
        font-size: 15px;
    }

    .table_container td {
        text-align: center;
    }

    @media (max-width: 782px) {
        .table_container {
            width: 100vw;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table_container table {
            border-collapse: collapse;
            table-layout: fixed;
        }
        .table_container th,
        .table_container td {
            width: 200px;
            min-width: 200px;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
    }

    @media (max-width: 425px) {

        .table_container th,
        .table_container td {
            width: 150px;
            min-width: 150px;
            border: 1px solid #ccc;
            padding: 8px;
            font-size: 12px;
        }

        .filter_label_import div {
            width: 100%;
        }

        .filter_label_import div input{
            width: 100%;
        }

        .filter_label_import div select{
            width: 100%;
        }
    }


</style>





