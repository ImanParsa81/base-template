<?php
global $wpdb;
$tbl = $wpdb->prefix . 'phoneNumber_discount';


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
                        <th> ردیف </th>
                        <th>شماره تماس</th>
                        <th> تاریخ </th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php if ( $list ) {

                        foreach ( $list as $k => $item ) {
                            ?>

                            <tr>
                                <td> <?= $item->id ?> </td>
                                <td> <?= $item->phone_number ?> </td>
                                <td> <?=  convertToShamsi($item->date) ?> </td>
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

</style>