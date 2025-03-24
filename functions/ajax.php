<?php
// تابع برای واکشی اطلاعات قیمت محصولات
function get_product_prices() {
    if (isset($_POST['product_id'])) {
        $product_id = sanitize_text_field($_POST['product_id']);

        // واکشی قیمت محصول و متاهای مرتبط
        $price = get_post_meta($product_id, '_price', true);
        $min_price = get_post_meta($product_id, '_min_price', true);
        $max_price = get_post_meta($product_id, '_max_price', true);

        // ارسال داده‌ها به‌صورت JSON
        $response = array(
            'price' => $price,
            'min_price' => $min_price,
            'max_price' => $max_price
        );

        echo json_encode($response);
    }

    wp_die(); // پایان درخواست AJAX
}
// اضافه کردن اکشن برای AJAX
add_action('wp_ajax_get_product_prices', 'get_product_prices');



/******************************/
// Ajax برای ذخیره فرم‌ها

function save_form_data() {
    global $wpdb;

    // اطلاعات فرم مشاوره
    if ($_POST['form_type'] === 'counseling') {
        $name = sanitize_text_field($_POST['name']);
        $phone = sanitize_text_field($_POST['phone']);
        $wpdb->insert($wpdb->prefix . 'nirweb_counseling_form', array('name' => $name, 'phone' => $phone));
    }

    // اطلاعات فرم استعلام قیمت
    if ($_POST['form_type'] === 'price_inquiry') {

        $id_product = sanitize_text_field($_POST['id_product']);
        $name = sanitize_text_field($_POST['name']);
        $phone = sanitize_text_field($_POST['phone']);
        $title = sanitize_text_field($_POST['title']);
        $description = sanitize_textarea_field($_POST['description']);
        $id_user = sanitize_textarea_field($_POST['id_user']);

        $wpdb->insert($wpdb->prefix . 'nirweb_price_inquiry_form', array('name' => $name, 'phone' => $phone, 'title' => $title, 'description' => $description , "product_id" => $id_product , "user_id" => $id_user));
    }

    // اطلاعات فرم خرید
    if ($_POST['form_type'] === 'shopping') {

        $user_id = NULL;
        if( is_user_logged_in() ) {
            $user_id = get_current_user_id();
        }

        $id_product =  sanitize_text_field($_POST['product']);
        $title = sanitize_text_field($_POST['title']);
        $description = sanitize_textarea_field($_POST['description']);
        $phone = sanitize_textarea_field($_POST['phone_number']);

        $wpdb->insert($wpdb->prefix . 'nirweb_shopping_form', array('title' => $title, 'description' => $description , 'user_id' => $user_id , 'product_id' => $id_product , 'phone_number' => $phone , 'status' => 0 ));
    }

    // فرم نیاز به مشاورزه
    if ($_POST['form_type'] === 'Need_advice') {

        $user_id = NULL;
        if( is_user_logged_in() ) {
            $user_id = get_current_user_id();
        }

        $id_product =  sanitize_text_field($_POST['product']);
        $title = sanitize_text_field($_POST['title']);
        $description = sanitize_textarea_field($_POST['description']);
        $phone = sanitize_textarea_field($_POST['phone_number']);

        $wpdb->insert($wpdb->prefix . 'nirweb_need_advice', array('title' => $title, 'description' => $description , 'user_id' => $user_id , 'product_id' => $id_product , 'phone_number' => $phone , 'status' => 0 ));

    }

    echo 'ذخیره شد';
    wp_die();
}

add_action('wp_ajax_save_form', 'save_form_data');
add_action('wp_ajax_nopriv_save_form', 'save_form_data');


/************ save question ***************/
function nirweb_save_question() {
    global $wpdb;

    $product_id = intval($_POST['product_id']);
    $user_id = intval($_POST['user_id']);
    $question = sanitize_textarea_field($_POST['question']);

    if ($product_id && $user_id && $question) {
        $table_name = $wpdb->prefix . 'product_questions';

        $wpdb->insert(
            $table_name,
            array(
                'product_id' => $product_id,
                'user_id' => $user_id,
                'question' => $question,
            )
        );

        wp_send_json_success('سوال با موفقیت ذخیره شد.');
    } else {
        wp_send_json_error('لطفا تمام فیلدها را پر کنید.');
    }

    wp_die();
}
add_action('wp_ajax_nirweb_save_question', 'nirweb_save_question');
add_action('wp_ajax_nopriv_nirweb_save_question', 'nirweb_save_question');

/******************************/
/******************************/








add_action('wp_ajax_my_custom_action', 'filter_farmer_function');
add_action('wp_ajax_nopriv_my_custom_action', 'filter_farmer_function');

function filter_farmer_function() {
    // دریافت داده‌های ارسالی
    $filter_data = isset($_GET['filter_data']) ? $_GET['filter_data'] : [];

    $arraySelect_farmer = isset($_GET['arraySelect_farmer']) ? $_GET['arraySelect_farmer'] : [];

    // بررسی و تبدیل داده‌های ارسال شده
    $meta_query = ['relation' => 'AND'];
    $tax_query = ['relation' => 'AND'];

    foreach ($filter_data as $filter) {
        if (!empty($filter['name']) && !empty($filter['value'])) {
            if ($filter['name'] === 'farmer_category') {
                // اضافه کردن شرط برای دسته‌بندی
                $tax_query[] = [
                    'taxonomy' => 'farmer_category',
                    'field' => 'name',
                    'terms' => $filter['value'],
                ];
            } else {
                // اضافه کردن شرط برای فیلترهای متا
                $meta_query[] = [
                    'key' => $filter['name'],
                    'value' => $filter['value'],
                    'compare' => 'LIKE', // می‌توانید مقایسه را تغییر دهید
                ];
            }
        }
    }




    if(count($arraySelect_farmer) != 0)
    {
        $args = [
            'post_type' => 'farmer',
            'posts_per_page' => -1,
            'post__in'   => $arraySelect_farmer,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
        ];
    } else {
        $args = [
            'post_type' => 'farmer',
            'posts_per_page' => -1,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
        ];
    }






    $query = new WP_Query($args);

    ?>
    <div class="row">

        <?php
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();

                $farmer_score = get_post_meta(get_the_ID(), '_farmer_Score', true);
                $farmer_role = get_post_meta(get_the_ID(), '_farmer_role', true);
                $farmer_address = get_post_meta(get_the_ID(), '_farmer_addres', true);
                $farmer_achievement = get_post_meta(get_the_ID(), '_farmer_outstanding_achievement', true);
                $farmer_pro = get_post_meta(get_the_ID(), '_farmer_pro_farmer', true);
                $_farmer_profile = get_post_meta(get_the_ID(), '_farmer_profile', true);

                ?>
                <div class="col-12 col-md-6 col-lg-6 nirweb_list_farmers_box">

<!--                    <p> --><?php //var_dump();  ?><!-- </p>-->

                    <div class="nirweb_list_farmers_box_container">
                        <div class="nirweb_list_farmers_box_part_profile_name_show_more">
                            <div class="nirweb_list_farmers_box_part_profile_name">
                                <div class="nirweb_list_farmers_box_part_profile_backGround">
                                    <img src="<?= $_farmer_profile['url'] ?>" alt="">
                                </div>
                                <div class="nirweb_list_farmers_box_part_name">

                                    <h2 class="nirweb_list_farmers_box_part_name_text"> <?= the_title() ?> </h2>

                                    <?php if ($farmer_pro != "") { ?>
                                        <p class="nirweb_list_farmers_box_part_pro"> <?= $farmer_pro ?? '' ?> </p>
                                    <?php } ?>

                                </div>
                            </div>

                            <a href="<?php echo esc_url(get_permalink(get_the_id())); ?>" class="nirweb_list_farmers_box_show_more">
                                <p class="d-none d-lg-block">مشاهده بیشتر</p>
                                <span class="nirweb_arrow_left"></span>
                            </a>

                        </div>




                        <div class="nirweb_list_farmers_box_info">
                            <ul>

                                <?php
                                if ( !empty($farmer_score) ) {
                                    ?>
                                    <li class="nirweb_list_farmers_box_info_star">
                                        <p> <?php echo "امتیاز" . " (" . $farmer_score . ")" ?> </p>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.4" d="M8.65152 2.0757L10.1358 5.05855C10.2452 5.27466 10.4539 5.42474 10.6946 5.45809L14.0287 5.94367C14.2234 5.97102 14.4001 6.07373 14.5194 6.23048C14.6375 6.38523 14.6881 6.58133 14.6595 6.77409C14.6361 6.93417 14.5608 7.08225 14.4454 7.19564L12.0296 9.5375C11.8529 9.70091 11.7729 9.94304 11.8155 10.1798L12.4103 13.4722C12.4737 13.8697 12.2103 14.2446 11.8155 14.3199C11.6528 14.3459 11.4861 14.3186 11.3394 14.2439L8.36546 12.6944C8.14474 12.583 7.88402 12.583 7.66331 12.6944L4.68933 14.2439C4.32392 14.438 3.87116 14.3059 3.66778 13.9457C3.59243 13.8023 3.56576 13.6389 3.59043 13.4795L4.18523 10.1865C4.2279 9.95037 4.14722 9.70691 3.97118 9.5435L1.55533 7.20298C1.26793 6.9255 1.25927 6.4686 1.53599 6.18112C1.54199 6.17512 1.54866 6.16845 1.55533 6.16178C1.67002 6.04505 1.82072 5.97102 1.98342 5.95167L5.31747 5.46543C5.55752 5.43141 5.76623 5.28267 5.87626 5.06522L7.30723 2.0757C7.43459 1.81957 7.69865 1.66016 7.98538 1.66683H8.07473C8.32345 1.69684 8.54016 1.85092 8.65152 2.0757" fill="#3293BB"/>
                                            <path d="M7.99533 12.6114C7.86619 12.6154 7.74039 12.6501 7.62723 12.7121L4.6678 14.2581C4.30569 14.4309 3.87236 14.2968 3.66934 13.9505C3.59412 13.809 3.56683 13.6469 3.59213 13.4881L4.18321 10.2021C4.22315 9.9632 4.14327 9.72033 3.96954 9.55219L1.55261 7.21228C1.26572 6.93138 1.26039 6.47034 1.54129 6.18277C1.54529 6.17876 1.54862 6.17543 1.55261 6.17209C1.6671 6.05866 1.81487 5.98394 1.97396 5.96058L5.3108 5.46951C5.55243 5.43882 5.76211 5.28803 5.86861 5.06919L7.31904 2.04204C7.45682 1.79784 7.72108 1.65239 8.00065 1.66773C7.99533 1.86589 7.99533 12.4766 7.99533 12.6114" fill="#3293BB"/>
                                        </svg>                                    </li>
                                    <?php
                                }
                                ?>

                                <?php
                                if ( !empty($farmer_role) ) {
                                    ?>
                                    <li class="nirweb_list_farmers_box_info_star">
                                        <p> <?php echo "نقش" . " (" . $farmer_role . ")" ?> </p>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.99735 10.1165C5.12202 10.1165 2.66602 10.5698 2.66602 12.3831C2.66602 14.1971 5.10668 14.6665 7.99735 14.6665C10.8727 14.6665 13.3287 14.2138 13.3287 12.3998C13.3287 10.5858 10.8887 10.1165 7.99735 10.1165" fill="#3293BB"/>
                                            <path opacity="0.4" d="M7.99675 8.38913C9.95542 8.38913 11.5247 6.81913 11.5247 4.86113C11.5247 2.90313 9.95542 1.33313 7.99675 1.33313C6.03875 1.33313 4.46875 2.90313 4.46875 4.86113C4.46875 6.81913 6.03875 8.38913 7.99675 8.38913" fill="#3293BB"/>
                                        </svg>
                                    </li>
                                    <?php
                                }
                                ?>

                                <?php
                                if ( !empty($farmer_address) ) {
                                    ?>
                                    <li class="nirweb_list_farmers_box_info_star">
                                        <p> <?php echo $farmer_address ?> </p>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M5.6884 1.95789C7.14497 1.11156 8.93534 1.12635 10.3782 1.99664C11.807 2.88465 12.6753 4.46949 12.6672 6.17434C12.634 7.868 11.7028 9.46004 10.539 10.6908C9.86721 11.4043 9.11573 12.0353 8.29988 12.5707C8.21585 12.6193 8.12382 12.6518 8.02831 12.6667C7.93638 12.6628 7.84686 12.6356 7.76782 12.5877C6.52227 11.7831 5.42954 10.7561 4.54221 9.55602C3.79971 8.55428 3.37787 7.34405 3.33399 6.08965C3.33302 4.38154 4.23183 2.80422 5.6884 1.95789ZM6.53009 6.79656C6.77511 7.4006 7.35344 7.7946 7.99505 7.79461C8.41539 7.79763 8.81944 7.62926 9.11719 7.32703C9.41494 7.02479 9.58164 6.6138 9.58015 6.18563C9.58239 5.53206 9.19762 4.94158 8.60549 4.68989C8.01337 4.4382 7.33068 4.57494 6.8762 5.03627C6.42171 5.4976 6.28508 6.19252 6.53009 6.79656Z" fill="#3293BB"/>
                                            <ellipse opacity="0.4" cx="8.0013" cy="14" rx="3.33333" ry="0.666667" fill="#3293BB"/>
                                        </svg>                                    </li>
                                    <?php
                                }
                                ?>

                                <?php
                                if ( !empty($farmer_achievement) ) {
                                    ?>
                                    <li class="nirweb_list_farmers_box_info_star">
                                        <p> <?php echo $farmer_achievement ?> </p>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.4" d="M14.1673 8.98426C13.62 8.98426 13.1747 8.54299 13.1747 8.00066C13.1747 7.45767 13.62 7.0164 14.1673 7.0164C14.3 7.0164 14.4273 6.96422 14.5207 6.87174C14.6147 6.7786 14.6673 6.65243 14.6673 6.52097L14.6667 4.7361C14.6667 3.22735 13.4273 2 11.9047 2H4.09665C2.57398 2 1.33465 3.22735 1.33465 4.7361L1.33398 6.57844C1.33398 6.7099 1.38665 6.83607 1.48065 6.92921C1.57398 7.02169 1.70132 7.07387 1.83398 7.07387C2.39998 7.07387 2.82665 7.4722 2.82665 8.00066C2.82665 8.54299 2.38132 8.98426 1.83398 8.98426C1.55798 8.98426 1.33398 9.20621 1.33398 9.47969V11.2632C1.33398 12.772 2.57265 14 4.09598 14H11.9053C13.4287 14 14.6673 12.772 14.6673 11.2632V9.47969C14.6673 9.20621 14.4433 8.98426 14.1673 8.98426" fill="#3293BB"/>
                                            <path d="M10.2885 7.72571L9.50252 8.49104L9.68852 9.57304C9.72052 9.76037 9.64519 9.94504 9.49119 10.0557C9.33852 10.1677 9.13919 10.1817 8.97119 10.0924L8.00119 9.58237L7.02919 10.093C6.95719 10.131 6.87852 10.151 6.80052 10.151C6.69852 10.151 6.59785 10.119 6.51119 10.0564C6.35785 9.94504 6.28252 9.76037 6.31452 9.57304L6.49985 8.49104L5.71385 7.72571C5.57785 7.59371 5.53052 7.39971 5.58919 7.21904C5.64852 7.03904 5.80185 6.91104 5.98919 6.88437L7.07319 6.72637L7.55919 5.74171C7.64385 5.57237 7.81319 5.46704 8.00119 5.46704H8.00252C8.19119 5.46771 8.36052 5.57304 8.44385 5.74237L8.92985 6.72637L10.0159 6.88504C10.2012 6.91104 10.3545 7.03904 10.4132 7.21904C10.4725 7.39971 10.4252 7.59371 10.2885 7.72571" fill="#3293BB"/>
                                        </svg>                                    </li>
                                    <?php
                                }
                                ?>

                            </ul>
                        </div>

                        <p class="nirweb_list_farmers_box_description"><?= get_the_content(); ?></p>

                        <div class="nirweb_list_farmers_box_category">
                            <?php
                            $terms = get_the_terms(get_the_ID(), 'farmer_category');
                            if ($terms && !is_wp_error($terms)) {
                                foreach ($terms as $term) {
                                    ?>
                                        <div class="nirweb_list_farmers_box_category_"> <?= $term->name ?> </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
            wp_reset_postdata();
        } else {
            echo 'هیچ پستی یافت نشد.';
        }
        ?>
    </div>
    <?php
    exit();
}


/************************** save product ***************************/

// save کردن محصولات

add_action('wp_ajax_save_product', 'save_product'); // برای کاربران لاگین شده
add_action('wp_ajax_nopriv_save_product', 'save_product'); // برای کاربران مهمان

function save_product() {
    global $wpdb;

    // نام جدول
    $table_name = $wpdb->prefix . "user_product_mapping_save_product";


    // شناسه کاربر و محصول مورد نظر
    $user_id = get_current_user_id();
    $product_id = isset($_GET['data']) ? intval($_GET['data']) : 0;

    // اطمینان از مقداردهی صحیح
    if ($product_id <= 0) {
        wp_send_json_error(array(
            'status'  => 'error',
            'message' => 'شناسه محصول معتبر نیست.',
        ));
    }

    // بررسی وجود داده
    $query = $wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND product_id = %d",
        $user_id,
        $product_id
    );
    $exists = $wpdb->get_var($query);

    // تصمیم‌گیری بر اساس وجود یا عدم وجود داده
    if ($exists > 0) {
        // حذف رکورد
        $result = $wpdb->delete(
            $table_name,
            array(
                'user_id' => $user_id,
                'product_id' => $product_id,
            ),
            array('%d', '%d')
        );

        if ($result) {
            wp_send_json_success(array(
                'message' => 'delete',
                'save_number_arr' => $_GET["save_number_arr"]
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'delete',
                'save_number_arr' => $_GET["save_number_arr"]
            ));
        }
    } else {
        // درج داده در جدول
        $result = $wpdb->insert(
            $table_name,
            array(
                'user_id' => $user_id,
                'product_id' => $product_id,
                'created_at' => current_time('mysql'),
            ),
            array('%d', '%d', '%s')
        );

        if ($result) {
            wp_send_json_success(array(
                'message' => 'insert',
                'save_number_arr' => $_GET["save_number_arr"]
            ));
        } else {
            wp_send_json_error(array(
                'message' => 'insert',
                'save_number_arr' => $_GET["save_number_arr"]
            ));
        }
    }
}





//----------------------
// تغییر status استعلام قیمت

add_action('wp_ajax_change_status_queries', 'change_status_queries'); // برای کاربران لاگین شده
add_action('wp_ajax_nopriv_change_status_queries', 'change_status_queries'); // برای کاربران مهمان
function change_status_queries() {
    // چک کردن اینکه متغیر data_id ارسال شده باشد
    if (!isset($_POST["data_id"])) {
        wp_send_json_error("Missing data_id.");
        return;
    }

    $data_id = intval($_POST["data_id"]); // گرفتن مقدار data_id و تبدیل آن به عدد صحیح برای امنیت

    global $wpdb;
    $table_name = $wpdb->prefix . "nirweb_shopping_form"; // نام جدول دیتابیس

    // بروزرسانی فیلد status برای ردیفی که data_id برابر است با مقدار داده شده
    $update_result = $wpdb->update(
        $table_name, // نام جدول
        [ 'status' => 1 ], // فیلدهایی که باید بروزرسانی شوند
        [ 'id' => $data_id ], // شرط بروزرسانی
        [ '%d' ], // فرمت داده‌های بروزرسانی شده
        [ '%d' ] // فرمت داده‌های شرط
    );

    if ($update_result !== false) {
        wp_send_json_success("Status updated successfully.");
    } else {
        wp_send_json_error("Failed to update status.");
    }
}


// تغییر status نیاز به مشاوره

add_action('wp_ajax_need_advice_change_status_queries', 'need_advice_change_status_queries'); // برای کاربران لاگین شده
add_action('wp_ajax_nopriv_need_advice_change_status_queries', 'need_advice_change_status_queries'); // برای کاربران مهمان
function need_advice_change_status_queries() {

    if (!isset($_POST["data_id"])) {
        wp_send_json_error("Missing data_id.");
        return;
    }

    $data_id = intval($_POST["data_id"]);
    global $wpdb;
    $table_name = $wpdb->prefix . "nirweb_need_advice";

    $update_result = $wpdb->update(
        $table_name,
        [ 'status' => 1 ],
        [ 'id' => $data_id ],
        [ '%d' ],
        [ '%d' ]
    );

    if ($update_result !== false) {
        wp_send_json_success("Status updated successfully.");
    } else {
        wp_send_json_error("Failed to update status.");
    }
}


//---------------------------------------------------------------------------------------

// پاک کردن ردیف استعلام قیمت
add_action('wp_ajax_delete_row_Price_inquiry', 'delete_row_Price_inquiry'); // برای کاربران لاگین شده
add_action('wp_ajax_nopriv_delete_row_Price_inquiry', 'delete_row_Price_inquiry'); // برای کاربران مهمان
function delete_row_Price_inquiry() {

    global $wpdb; // دسترسی به شیء $wpdb
    $id_row = $_POST['id_row']; // مقدار ID را به صورت دینامیک تنظیم کنید
    $table_name = $wpdb->prefix . 'nirweb_shopping_form';

    $delete_result = $wpdb->delete(
        $table_name, // نام جدول
        array('id' => $id_row), // شرط حذف (ستون id باید برابر $id_row باشد)
        array('%d') // نوع داده (در اینجا عدد صحیح)
    );

    if ($delete_result !== false) {
        echo 'Row successfully deleted.';
    } else {
        echo 'Failed to delete the row.';
    }
}




// پاک کردن ردیف نیاز به شماوره
add_action('wp_ajax_need_advice_delete_row_Price_inquiry', 'need_advice_delete_row_Price_inquiry'); // برای کاربران لاگین شده
add_action('wp_ajax_nopriv_need_advice_delete_row_Price_inquiry', 'need_advice_delete_row_Price_inquiry'); // برای کاربران مهمان
function need_advice_delete_row_Price_inquiry() {

    global $wpdb; // دسترسی به شیء $wpdb
    $id_row = $_POST['id_row']; // مقدار ID را به صورت دینامیک تنظیم کنید
    $table_name = $wpdb->prefix . 'nirweb_need_advice';

    $delete_result = $wpdb->delete(
        $table_name, // نام جدول
        array('id' => $id_row), // شرط حذف (ستون id باید برابر $id_row باشد)
        array('%d') // نوع داده (در اینجا عدد صحیح)
    );

    if ($delete_result !== false) {
        echo 'Row successfully deleted.';
    } else {
        echo 'Failed to delete the row.';
    }
}



//----------------------
// ذخیره کردن شماره تماس ها برای تخفیف


add_action('wp_ajax_popup_discount_function', 'popup_discount_function'); // برای کاربران لاگین شده
add_action('wp_ajax_nopriv_popup_discount_function', 'popup_discount_function'); // برای کاربران مهمان

function popup_discount_function() {
    global $wpdb;

    // دریافت شماره تماس از POST
    $get_phone_number = $_POST["phone_number"];

    // نام جدول
    $table_name = $wpdb->prefix . "phoneNumber_discount";

    // بررسی اینکه آیا شماره تماس در دیتابیس وجود دارد یا خیر
    $existing_phone = $wpdb->get_var($wpdb->prepare("SELECT phone_number FROM $table_name WHERE phone_number = %d", $get_phone_number));

    if ($existing_phone) {
        // شماره تماس قبلاً موجود است
        wp_send_json_success(array('message' => 'This phone number already exists.'));
    } else {
        // شماره تماس جدید است، آن را در دیتابیس وارد کن
        $wpdb->insert(
            $table_name,
            array(
                'phone_number' => $get_phone_number,
                'date' => current_time('mysql') // تاریخ و زمان فعلی
            ),
            array('%d', '%s') // نوع داده‌ها: شماره تماس عددی و تاریخ به صورت رشته
        );
        wp_send_json_success(array('message' => 'Phone number has been added successfully.'));
    }
}



//----------------------
//    پیشنهاد دادن محصول در هنگام سرج
//----------------------

// ثبت اکشن‌های AJAX برای کاربران وارد شده و مهمان
add_action('wp_ajax_nopriv_product_search_suggestions', 'product_search_suggestions');
add_action('wp_ajax_product_search_suggestions', 'product_search_suggestions');

function product_search_suggestions()
{
    // بررسی nonce برای امنیت (اختیاری)
    check_ajax_referer('product_search_nonce', 'nonce');

    $search_query = isset($_GET['term']) ? sanitize_text_field($_GET['term']) : '';

    $suggestions = array();

    if (!empty($search_query)) {
        // WP_Query برای جستجوی محصولات
        $args = array(
            'post_type' => 'product',
            's' => $search_query,
            'posts_per_page' => 15,  // تعداد نتایج پیشنهادی
        );

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                // اضافه کردن عنوان محصول به لیست پیشنهادات
                $suggestions[] = array(
                    'label' => get_the_title(),
                    'url' => get_permalink()
                );
            }
            wp_reset_postdata();
        }
    }

    // بازگرداندن نتایج به صورت JSON
    wp_send_json($suggestions);
}




//----------------------
//    تغییر قیمت محصول
//----------------------

function cheng_price_admin() {

    $witch_mode = $_POST["witch_mode"];
    $get_id_moteghayer = $_POST["get_id_moteghayer"];
    $find_value = $_POST["find_value"];
    $find_value = str_replace(',','',$find_value);

    if ($witch_mode == 1) {

        change_price_by_variation_id($get_id_moteghayer, $find_value);

    } else {
        $product_id = $get_id_moteghayer;
        $new_price = $find_value;
        $product = wc_get_product( $product_id );
        $product->set_regular_price( $new_price );
        $product->save();
    }

    $response = array(
        'message' => 'سلام، این پاسخ AJAX شماست!',
        'witch_mode' => $witch_mode,
        'get_id_moteghayer' => $get_id_moteghayer,
        'find_value' => $find_value,
    );
    wp_send_json_success( $response );
}

add_action( 'wp_ajax_cheng_price_admin', 'cheng_price_admin' );
add_action( 'wp_ajax_nopriv_cheng_price_admin', 'cheng_price_admin' );


// تابع برای تغییر قیمت متغیر با ID و قیمت داده‌شده
function change_price_by_variation_id($variation_id, $new_price) {
    // بررسی اینکه ID محصول و قیمت وارد شده معتبر باشد
    if (empty($variation_id) || empty($new_price)) {
        return 'لطفاً ID و قیمت معتبر وارد کنید.';
    }

    update_post_meta($variation_id, '_price', $new_price);
    update_post_meta($variation_id, '_regular_price', $new_price);
}



//----------------------
// دریافت محصولات تامین کننده
//----------------------

function get_supplier() {
    $name_supplier = $_POST["supplier"];

    // آرگومان‌های مربوط به WP_Query
    $args = array(
        'post_type'      => 'supplier',
        'posts_per_page' => 10,
    );

    // اگر نام تامین‌کننده ارسال شده باشد، از آن برای جستجو استفاده می‌کنیم
    if ( !empty($name_supplier) ) {
        $args['s'] = $name_supplier;
    }

    $query = new WP_Query($args);

    // از ob_start برای ذخیره محتوای خروجی در بافر استفاده می‌کنیم
    ob_start();

    if ( $query->have_posts() ) : ?>
        <ul class="List_search_supplier">
            <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                <li id_supplier="<?php echo get_the_ID(); ?>" class="items_">
                    <?php the_title(); ?>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else:
        echo 'هیچ آیتمی یافت نشد!';
    endif;

    wp_reset_postdata();
    // محتوای HTML تولید شده را در یک متغیر ذخیره می‌کنیم
    $html = ob_get_clean();
    // آماده‌سازی پاسخ و ارسال به صورت JSON
    wp_send_json_success( array( 'html' => $html ) );
}

// ثبت اکشن‌های AJAX (برای یوزر لاگین و یوزر لاگین‌نشده)
add_action( 'wp_ajax_get_supplier', 'get_supplier' );
add_action( 'wp_ajax_nopriv_get_supplier', 'get_supplier' );



/**************
  ویرایش تامین کننده
 **************/

function set_supplier_product() {

    $id_supplier = $_POST["id_supplier"];
    $product_id = $_POST["product_id"];



    update_post_meta( $product_id, 'selected_supplier', $id_supplier );

    $response = array(
        'message' => 'true',
        'name_of_supplier' => get_the_title($id_supplier),
    );

    wp_send_json_success( $response );

}

add_action( 'wp_ajax_set_supplier_product', 'set_supplier_product' );
add_action( 'wp_ajax_nopriv_set_supplier_product', 'set_supplier_product' );

/*********
get farms for product
 *********/
function get_farms_for_product()
{
//    $ids = $_POST["farmer_ids"];
//var_dump($ids);
    $args = array(
        'post_type' => 'farmer',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );
    $post_ids = get_posts($args);
    $product_counts = []; // آرایه شمارش محصولات
    foreach ($post_ids as $post_id) {
        $get_repeater = get_post_meta($post_id, "_single_page_farmer_buy_product", true);

        if (!empty($get_repeater) && is_array($get_repeater)) {
            foreach ($get_repeater as $repeater) {
                if (isset($repeater['_single_page_farmer_buy_product_selected_product'])) {
                    $product_id = $repeater['_single_page_farmer_buy_product_selected_product'];

                    // اگر محصول قبلاً در آرایه وجود ندارد، مقدار اولیه را تنظیم کنیم
                    if (!isset($product_counts[$product_id])) {
                        $product_counts[$product_id] = 0;
                    }

                    // افزایش تعداد کشاورزان برای این محصول
                    $product_counts[$product_id]++;
                }
            }
        }
    }

// تبدیل داده‌ها به فرمت مورد نظر
    global $output;
    foreach ($product_counts as $product_id => $farmer_count) {
        $output[] = [
            'prodact_id' => $product_id,
            'farmer_count' => $farmer_count
        ];
    }
    wp_send_json(['data' => $output]);
    exit();
}
add_action( 'wp_ajax_get_farms_for_product', 'get_farms_for_product' );
add_action( 'wp_ajax_nopriv_get_farms_for_product', 'get_farms_for_product' );



/*********
logout user
 *********/

function nirweb_logout_user() {

    wp_clear_auth_cookie();
    $response = array(
        'message' => 'true',
    );
    wp_send_json_success( $response );

}
add_action( 'wp_ajax_nirweb_logout_user', 'nirweb_logout_user' );
add_action( 'wp_ajax_nopriv_nirweb_logout_user', 'nirweb_logout_user' );



/*************************************************************************************************************************
 * import farmer admin
 *************************************************************************************************************************/

// show in tabel

function import_farmer_toSite_show() {


    $file = $_FILES["file"]["tmp_name"];

    if (($handle = fopen($file, "r")) !== FALSE) {
        $counter = 0;
        $data_array = array();


        while (($csv = fgetcsv($handle, 10000000, ",")) !== FALSE) {

            $name =               $csv[0];
            $description =        $csv[1];
            $role =               $csv[2];
            $address =            $csv[3];
            $achievement_barez =  $csv[4];
            $label =              $csv[5];
            $phone_number =       $csv[6];
            $product =            $csv[7];

            $counter++;

            // ذخیره داده‌ها برای ساخت HTML Table
            $data_array[] = array(
                'name' => $name,
                'description' => $description,
                'role' => $role,
                'address' => $address,
                'achievement_barez' => $achievement_barez,
                'label' => $label,
                'phone_number' => $phone_number,
                'product' => $product,
            );
        }
        fclose($handle);


        // ساخت جدول HTML جهت نمایش مقادیر وارد شده
        $html_table = '<table border="1" cellpadding="5" cellspacing="0">';
        $html_table .= '<thead><tr>
        <th>نام</th>
        <th>توضیحات</th>
        <th>نقش</th>
        <th>ادرس</th>
        <th>دستاورد بارز</th>
        <th>لیبل</th>
        <th>شماره تماس</th>
        <th>محصوالات</th>
        </tr></thead>';

        $html_table .= '<tbody>';
        foreach ($data_array as $row) {
            $html_table .= '<tr>';

            $html_table .= '<td>' . esc_html($row['name']) . '</td>';
            $html_table .= '<td>' . esc_html($row['description']) . '</td>';
            $html_table .= '<td>' . esc_html($row['role']) . '</td>';
            $html_table .= '<td>' . esc_html($row['address']) . '</td>';
            $html_table .= '<td>' . esc_html($row['achievement_barez']) . '</td>';
            $html_table .= '<td>' . esc_html($row['label']) . '</td>';
            $html_table .= '<td>' . esc_html($row['phone_number']) . '</td>';
            $html_table .= '<td>' . esc_html($row['product']) . '</td>';
            $html_table .= '</tr>';
        }
        $html_table .= '</tbody></table>';

        wp_send_json_success(array(
            'message'       => 'مقادیر با موفقیت وارد شدند',
            'html_table'    => $html_table,
            'rows_imported' => $counter,
            'raw_data'      => $data_array,
        ));
    } else {
        wp_send_json_error(array(
            'message' => 'خطا در باز کردن فایل'
        ));
    }

    wp_die();

}
add_action( 'wp_ajax_import_farmer_toSite_show', 'import_farmer_toSite_show' );
add_action( 'wp_ajax_nopriv_import_farmer_toSite_show', 'import_farmer_toSite_show' );

//---------------------------------------------------------------------------------------------------------- import data

function import_comment_upload_import_farmer_file() {


    $get_defalte_profile = $_POST["get_defalte_profile"];
    $farmer_select_defalte_background_page_single = $_POST["farmer_select_defalte_background_page_single"];

    if ( isset( $_POST['valueComment'] ) ) {
        $valueComment = $_POST['valueComment'];

        $name              = $valueComment['name'];
        $description       = $valueComment['description'];
        $role              = $valueComment['role'];
        $address           = $valueComment['address'];
        $achievement_barez = $valueComment['achievement_barez'];
        $label             = $valueComment['label'];
        $phone_number      = $valueComment['phone_number'];
        $product           = $valueComment['product'];

        //------------- ایجاد پست جدید -------------
        $post_data = array(
            'post_title'   => $name,
            'post_content' => $description,
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_type'    => 'farmer'
        );

        $post_id = wp_insert_post($post_data);

        if ( ! is_wp_error($post_id) ) {

            // ذخیره متادیتای مربوط به کشاورز
            $farmer_meta = array(
                'name'        => $name,
                'description' => $description,
            );
            update_post_meta($post_id, 'farmer', $farmer_meta);

            $farmer_profile = array(
                'url'         => $get_defalte_profile,
                'id'          => '206',
                'width'       => '',
                'height'      => '',
                'thumbnail'   => $get_defalte_profile,
                'alt'         => '',
                'title'       => '',
                'description' => '',
            );
            update_post_meta($post_id, '_farmer_profile', $farmer_profile);
            $farmer_background_image = array(
                'url'         => $farmer_select_defalte_background_page_single,
                'id'          => '206',
                'width'       => '',
                'height'      => '',
                'thumbnail'   => $farmer_select_defalte_background_page_single,
                'alt'         => '',
                'title'       => '',
                'description' => '',
            );
            update_post_meta($post_id, '_single_page_farmer_baner', $farmer_background_image);
            update_post_meta($post_id, '_farmer_role', $role);
            update_post_meta($post_id, '_farmer_addres', $address);
            update_post_meta($post_id, '_farmer_outstanding_achievement', $achievement_barez);
            update_post_meta($post_id, '_farmer_pro_farmer', $label);
            update_post_meta($post_id, 'farmer_phone_number', $phone_number);

            //------------- set terms ---------------

            if ( ! empty( $product ) ) {
                $Feature_bad_ = array_map( 'trim', explode( "|", $product ) );
                foreach ( $Feature_bad_ as $term ) {
                    if ( ! empty( $term ) ) {
                        if ( ! term_exists( $term, 'farmer_category' ) ) {
                            wp_insert_term( $term, 'farmer_category' );
                        }
                        wp_set_object_terms( $post_id, $term, 'farmer_category', true );
                    }
                }
            }

            wp_update_post(array(
                'ID'         => $post_id,
                'post_title' => $name
            ));

            clean_post_cache($post_id);

            wp_send_json_success(array(
                'message' => 'مقادیر با موفقیت وارد شدند',
                'pl'      => $valueComment
            ));
            wp_die();
        } else {
            wp_send_json_error('خطا در ایجاد پست.');
            wp_die();
        }
    } else {
        wp_send_json_error('مقادیر ارسال نشده‌اند.');
        wp_die();
    }
}
add_action( 'wp_ajax_import_comment_upload_import_farmer_file', 'import_comment_upload_import_farmer_file' );
add_action( 'wp_ajax_nopriv_import_comment_upload_import_farmer_file', 'import_comment_upload_import_farmer_file' );





