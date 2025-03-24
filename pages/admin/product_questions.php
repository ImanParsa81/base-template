<?php

if (isset($_GET['action']) && $_GET['action'] == 'answer') {
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_questions';
    $question_id = intval($_GET['id']);
    $question = $wpdb->get_row("SELECT * FROM $table_name WHERE id = $question_id");

    if ($_POST && isset($_POST['nirweb_answer'])) {
        $answer = sanitize_text_field($_POST['nirweb_answer']);
        $wpdb->update(
            $table_name,
            array('answer' => $answer),
            array('id' => $question_id)
        );
        echo '<div class="updated notice"><p>پاسخ ثبت شد!</p></div>';
    }

    echo '<div class="wrap">';
    echo '<h1>پاسخ به سوال</h1>';
    echo '<p>سوال: ' . esc_html($question->question) . '</p>';
    echo '<form method="POST">';
    echo '<textarea name="nirweb_answer" rows="5" style="width:100%;">' . esc_html($question->answer) . '</textarea>';
    echo '<input type="submit" value="ثبت پاسخ" class="button button-primary">';
    echo '</form>';
    echo '</div>';
} else {


    global $wpdb;
    $table_name = $wpdb->prefix . 'product_questions';
    $questions = $wpdb->get_results("SELECT * FROM $table_name");


    echo '<div class="tabel_conatiner">';

    echo '<div class="wrap"><h1>سوالات محصولات</h1>';
    echo '<table class="wp-list-table widefat fixed striped  nirweb_show_product_questions">';
    echo '<thead><tr><th>شناسه</th><th>محصول</th><th>کاربر</th><th>سوال</th><th>پاسخ</th><th>عملیات</th></tr></thead><tbody>';

    foreach ($questions as $question) {
        $product_title = get_the_title($question->product_id);
        $user_info = get_userdata($question->user_id);
        $user_name = $user_info->user_login;

        $answer = empty($question->answer) ? 'هنوز پاسخی داده نشده' : $question->answer;
        ?>
        <tr>
            <td><?= $question->id ?></td>
            <td><?= $product_title ?></td>
            <td><?= $user_name ?></td>
            <td><?= $question->question ?></td>
            <td><?= $answer ?></td>
            <td><a href="<?= add_query_arg(['action' => 'answer', 'id' => $question->id]) ?>" class="button">پاسخ</a>
            </td>
        </tr>
        <?php
    }

    echo '</tbody></table></div></div>';

}
?>

<style>

    @media (max-width: 782px) {

        .tabel_conatiner {
            width: 99vw;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .tabel_conatiner table {
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tabel_conatiner th,
        .tabel_conatiner td {
            width: 120px;
            min-width: 120px;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
    }

</style>