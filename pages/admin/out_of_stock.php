<?php
$args = [
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => -1
];

$query = new WP_Query($args);

echo '<div class="wrap">';
echo '<h1>محصولات متغییری که قیمت ندارند یا در انبار نیستند</h1>';
echo '<table class="widefat fixed" cellspacing="0">';
echo '<thead><tr><th>نام محصول</th><th>وضعیت</th><th>لینک ویرایش</th></tr></thead>';
echo '<tbody>';

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        $product = wc_get_product(get_the_ID());

        if ($product->is_type('variable')) {
            $variations = $product->get_available_variations();
            $has_price_or_stock = false;

            foreach ($variations as $variation) {
                if (!empty($variation['display_price']) || $variation['is_in_stock']) {
                    $has_price_or_stock = true;
                    break;
                }
            }

            if (!$has_price_or_stock) {
                echo '<tr>';
                echo '<td>' . get_the_title() . '</td>';
                echo '<td>بدون قیمت یا موجودی</td>';
                echo '<td><a href="' . get_edit_post_link(get_the_ID()) . '" target="_blank">ویرایش محصول</a></td>';
                echo '</tr>';
            }
        } else {
            if (!$product->is_in_stock()) {
                echo '<tr>';
                echo '<td>' . get_the_title() . '</td>';
                echo '<td>در انبار موجود نیست</td>';
                echo '<td><a href="' . get_edit_post_link(get_the_ID()) . '" target="_blank">ویرایش محصول</a></td>';
                echo '</tr>';
            }
        }
    }
} else {
    echo '<tr><td colspan="3">هیچ محصولی یافت نشد.</td></tr>';
}

echo '</tbody>';
echo '</table>';
echo '</div>';

wp_reset_postdata();