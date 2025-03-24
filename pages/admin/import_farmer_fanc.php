
<div class="wrap">
    <h2>درون‌ریزی اطلاعات کشاورز</h2>
    <div class="b2wall-card">
        <div class="b2wall-loading b2wall-hidden">
            <p>در حال درون‌ریزی محصولات، لطفا تا اتمام کار صبر کنید ...</p>
            <div class="meter animate">
                <span style="width: 100%"></span>
            </div>
        </div>
        <form class="b2wall-fileUpload" enctype="multipart/form-data">
            <div class="form-group">
                <p>
                    لطفا فایل اکسل (csv) را از این قسمت آپلود کنید:
                </p>
                <label for="b2wall_upload_import_farmer_file">
                    <i class="dashicons dashicons-upload"></i>
                    بارگذاری فایل
                </label>
                <input class="b2wall-file" type="file" id="b2wall_upload_import_farmer_file"/>
            </div>
        </form>
    </div>
</div>

<button type="button" class="butten_import_b2wall_upload_import_farmer_file"> import </button>

<div class="imports_farmer_input">

    <div>
        <label for="farmer_select_defalte_image_profile"> پروفایل پیشورز کشاورزان :</label>
        <input id="farmer_select_defalte_image_profile" class="farmer_select_defalte_image_profile" type="text">
<!--        value="http://localhost/b2wall_local/wp-content/uploads/2025/03/Group-70814.svg"-->
        <p class="farmer_select_defalte_image_profile_Error part_Error"> لطفا ادرس عکس پروفایل کشاورز را وارد کنید </p>
    </div>

    <div>
        <label for="farmer_select_defalte_background_page_single"> background صفحه سینگل :</label>
        <input id="farmer_select_defalte_background_page_single" class="farmer_select_defalte_background_page_single" type="text">
<!--        value="http://localhost/b2wall_local/wp-content/uploads/2024/12/baner_single_profile.png"-->
        <p class="farmer_select_defalte_background_page_single_Error part_Error"> لطفا ادرس عکس پروفایل کشاورز را وارد کنید </p>
    </div>

</div>

<div class="tabel_data"> </div>

<style>



    @media (max-width: 782px) {
        .tabel_data {
            width: 99vw;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .tabel_data table {
            border-collapse: collapse;
            table-layout: fixed;
        }
        .tabel_data th,
        .tabel_data td {
            width: 200px;
            min-width: 200px;
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
    }




    .part_Error {
        color: red;
        display: none;
    }

    .part_Error.active {
        display: block;
    }
    
    .imports_farmer_input div {
        margin: 10px 0px;
    }

    .imports_farmer_input {
        display: none;
    }
    .imports_farmer_input.active {
        display: block;
    }

    

    .butten_import_b2wall_upload_import_farmer_file {
        display: none;
        margin: 10px 10px;
        color: white;
        background-color: orangered;
        border: none;
        border-radius: 8px;
        padding: 10px;
        cursor: pointer;
    }

    .butten_import_b2wall_upload_import_farmer_file.active {
        display: block;
    }

    .b2wall-card {
        padding: 15px;
        margin: 25px 0 0;
        background-color: #fff;
        border: 1px solid #ccd0d4;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    }

    .b2wall-hidden {
        display: none;
    }

    .b2wall-fileUpload input {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }

    .b2wall-alert {
        padding: 10px 15px;
        color: #fff;
    }

    .b2wall-success {
        background-color: #1bac1b;
    }

    .b2wall-danger {
        background-color: #c82424;
    }

    .b2wall-alert > i {
        font-size: 15px;
        margin-left: 10px;
        padding-top: 3px;
    }

    .b2wall-fileUpload label {
        max-width: 80%;
        font-size: 18px;
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
        cursor: pointer;
        display: inline-block;
        overflow: hidden;
        padding: 15px 20px;
        background-color: #167cff;
        color: #fff;
    }

    .b2wall-fileUpload label:hover {
        background-color: #4798ff;
    }

    .b2wall-fileUpload label i {
        font-size: 15px;
    }

    .meter {
        height: 20px;
        position: relative;
        background: transparent;
        padding: 0;
    }

    .meter > span {
        display: block;
        height: 100%;
        background-color: rgb(76, 196, 255);
        box-shadow: inset 0 2px 9px rgba(255, 255, 255, 0.3),
        inset 0 -2px 6px rgba(0, 0, 0, 0.4);
        position: relative;
        overflow: hidden;
    }

    .meter > span:after {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background-image: linear-gradient(
                -45deg,
                rgba(255, 255, 255, .2) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, .2) 50%,
                rgba(255, 255, 255, .2) 75%,
                transparent 75%,
                transparent
        );
        z-index: 1;
        background-size: 50px 50px;
        animation: move 2s linear infinite;
        overflow: hidden;
    }

    .meter > span:after, .animate > span > span {
        animation: move 2s linear infinite;
    }

    @keyframes move {
        0% {
            background-position: 0 0;
        }
        100% {
            background-position: 50px 50px;
        }
    }
</style>



