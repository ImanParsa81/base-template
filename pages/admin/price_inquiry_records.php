<?php
global $wpdb;
$tbl = $wpdb->prefix . 'nirweb_shopping_form';

$items_per_page =  '999999';
$page           = isset( $_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
$offset         = ( $page * $items_per_page ) - $items_per_page;
$query          = "SELECT * FROM $tbl";
$total_query    = "SELECT COUNT(1) FROM (${query}) AS combined_table";
$total          = $wpdb->get_var( $total_query );
$fi             = $wpdb->get_results( "SELECT * FROM $tbl ORDER BY `id` DESC  LIMIT  $offset ,  $items_per_page " );

$resp           = array(
    $fi,
    paginate_links( array(
        'base'      => add_query_arg( 'cpage', '%#%' ),
        'format'    => '',
        'prev_text' => __( '&laquo;' ),
        'next_text' => __( '&raquo;' ),
        'total'     => ceil( $total / $items_per_page ),
        'current'   => $page
    ) )
);

$list  = $resp[0];
$links = $resp[1];
?>

<div class=" nirweb_admin_show_data " >

    <div class=" nirweb_admin_show_data_container ">

        <p class="nirweb_title_List_price_inquiry_records" >لیست سوابق استعلام قیمت</p>

        <div class="nirweb_container_table  ">


            <div class="nirweb_hiden_table_in_responsive">
                <table class="nirweb_List_of_price_inquiry_records" >

                    <thead>
                    <tr>
                        <th>نام</th>
                        <th>محصول</th>
                        <th>تاریخ درخواست</th>
                        <!--                                        <th>محصول</th>-->
                        <!--                                        <th>دسته بندی</th>-->
                        <th>توضیحات</th>
                        <th>شماره تماس</th>
                        <th>وضعیت</th>
                        <th> تغییر وضعیت </th>
                        <th> حذف </th>

                    </tr>
                    </thead>

                    <tbody>

                    <?php if ( $list ) {
                        foreach ( $list as $k => $item ) {
                            ?>

                            <tr>

                                <td>
                                    <?php
                                    $user_id = $item->user_id; // آیدی کاربر
                                    $user_info = get_userdata($user_id);
                                    if ($user_info) {
                                        echo $user_info->display_name;
                                    }
                                    ?>
                                </td>
                                <td> <?= $item->title ?> </td>
                                <td> <?php echo convertToShamsi($item->created_at); ?> </td>
                                <td class="nirweb_dont_break_line_text">  <?= $item->description ?> </td>

                                <td>

                                    <?php
                                    if (empty($item->phone_number)) {
                                        echo "نامشخص";
                                    } else {
                                        echo $item->phone_number;
                                    }
                                    ?>

                                </td>

                                <td>
                                    <?php
                                    if($item->status == NULL || $item->status == 0 ) {
                                        echo "در حال بررسی" ;
                                    } else if( $item->status == 1 ) {
                                        echo "پاسخ داده شد" ;
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if($item->status == NULL || $item->status == 0 ) {
                                        ?>
                                        <p class="nirweb_menu_change_active" data="<?= $item->id ?>" > تغییر وضعیت </p>
                                        <?php
                                    }
                                    ?>
                                </td>

                                <td>
                                    <button id_row_delete="<?=$item->id?>" class="nirweb_menu_change_active_delete" type="button"> حذف کردن </button>
                                </td>


                            </tr>

                        <?php }
                    } ?>

                    </tbody>

                </table>
            </div>

        </div>

    </div>
</div>

<style>


    @media (max-width: 782px) {

        .nirweb_List_of_price_inquiry_records th,
        .nirweb_List_of_price_inquiry_records td {
            width: fit-content;
            min-width: 100px;
            text-align: center;
        }
    }





    div#wpcontent {
        padding: 0px;
    }

    .nirweb_admin_show_data {
        padding: 15px;
    }

    .nirweb_admin_show_data_container {
        background-color: white;
        border-radius: 15px;
        width: 100%;
        overflow: auto;
    }

    .nirweb_title_List_price_inquiry_records {
        font-weight: bold;
        padding: 15px;
        color: black;
        font-size: 18px;
        margin: 0;
    }
    .nirweb_admin_show_data table{
        width: 100%;
    }
    .nirweb_admin_show_data table th {
        color: #777777;
        text-align: start;
    }
    .nirweb_admin_show_data tbody tr td{
        padding: 10px;
    }
    .nirweb_admin_show_data tbody tr:nth-child(odd) {
        background-color: #faf7f4;
    }
    .nirweb_admin_show_data tbody tr:nth-child(even) {
        background-color: #ffffff;
    }
    .nirweb_menu_change_active {
        padding: 5px 10px;
        background-color: #ff8000;
        color: #ffffff;
        border-radius: 10px;
        width: fit-content;
        text-align: center;
    }
    .nirweb_menu_change_active:hover {
        cursor: pointer;
    }

    .nirweb_menu_change_active_delete {
        padding: 5px 10px;
        background-color: #ff0000;
        color: #ffffff;
        border-radius: 10px;
        width: fit-content;
        text-align: center;
        outline: none;
        border: none;
    }
    .nirweb_menu_change_active_delete:hover {
        cursor: pointer;
    }



</style>