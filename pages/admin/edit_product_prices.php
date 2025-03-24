<?php

    if (!current_user_can('manage_options')) {
        return;
    }

    // ذخیره فرم
    if (isset($_POST['submit'])) {
        $product_id = sanitize_text_field($_POST['product_id']);
        $price = sanitize_text_field($_POST['price']);
        $min_price = sanitize_text_field($_POST['min_price']);
        $max_price = sanitize_text_field($_POST['max_price']);

        // به‌روزرسانی قیمت ووکامرس
        update_post_meta($product_id, '_price', $price);

        // به‌روزرسانی فیلدهای متای سفارشی (حداقل و حداکثر قیمت)
        update_post_meta($product_id, '_min_price', $min_price);
        update_post_meta($product_id, '_max_price', $max_price);

        ?>
        <div class="updated">
            <p>قیمت‌ها با موفقیت به‌روزرسانی شدند.</p>
        </div>
        <?php
    }

    // گرفتن لیست محصولات
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    );
    $products = get_posts($args);

    ?>
    <div class="wrap">
        <h1>ویرایش قیمت محصولات</h1>
        <form method="post" >
            <div>
                <label for="product_id">انتخاب محصول:</label>
                <select name="product_id" id="product_id">
                    <option value="">انتخاب محصول</option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo esc_attr($product->ID); ?>">
                            <?php echo esc_html($product->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select><br><br>
            </div>

            <div class="">
                <label for="price">قیمت (ووکامرس):</label>
                <input type="text" name="price" id="price" value=""><br><br>
            </div>

            <div class="">
                <label for="min_price">حداقل قیمت:</label>
                <input type="text" name="min_price" id="min_price" value=""><br><br>
            </div>

            <div class="">
                <label for="max_price">حداکثر قیمت:</label>
                <input type="text" name="max_price" id="max_price" value=""><br><br>
            </div>

            <input type="submit" name="submit" class="button-primary" value="ذخیره">
        </form>
    </div>


<style>

    form {
        background: #ffffff;
        display: flex;
        justify-content: space-between;
        padding: 15px;
        border-radius: 8px;
        flex-wrap: wrap;
    }

    @media (max-width: 425px) {

        form select {
            width: 100%;
        }

        form div {
            width: 100%;
        }

        form input {
            width: 100%;
        }
    }

</style>
