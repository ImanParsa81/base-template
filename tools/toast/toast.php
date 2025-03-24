<div class="nirweb_panel_toast_box">
        <div class="nirweb_panel_toast_line"></div>
            <div class="nirweb_panel_toast_content">
                <div class="nirweb_panel_toast_text">

                    <div class="toast_icon_mode">



                    </div>


                    <div>
                        <strong>

                        </strong>
                        <p>

                        </p>
                    </div>
                </div>

                <svg class="close  nirweb_pupop_close_icon " width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.21443 7.84078C0.979455 7.84078 0.744484 7.75144 0.565807 7.57154C0.207231 7.21297 0.207231 6.63288 0.565807 6.2743L6.43031 0.409802C6.78889 0.051225 7.36897 0.051225 7.72755 0.409802C8.08612 0.768378 8.08612 1.34846 7.72755 1.70704L1.86305 7.57154C1.68437 7.75144 1.4494 7.84078 1.21443 7.84078" fill="#6C757D"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.08187 7.84434C6.8469 7.84434 6.61193 7.755 6.43325 7.5751L0.563854 1.70448C0.205278 1.3459 0.205278 0.765815 0.563854 0.407238C0.923655 0.0486615 1.50374 0.0486615 1.86109 0.407238L7.73049 6.27786C8.08907 6.63643 8.08907 7.21652 7.73049 7.5751C7.55181 7.755 7.31562 7.84434 7.08187 7.84434" fill="#6C757D"/>
                </svg>


            </div>
</div>



<style>
        /**********
        * toast
        **********/



     .toast_svg_icon {
         width: 20px;
         height: auto;
     }

    .nirweb_panel_toast_content .close {
        width: 18px;
        height: auto;
    }

    .nirweb_panel_toast_box {
        transition: all 0.3s ease-in-out, visibility 0s ease-in-out 0.3s;
        background: #EAFAED;
        position: fixed;
        bottom: -200px;
        left: 50%;
        transform: translate(-50%);
        padding: 0 !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 8px 0 #B8B8B840;
        width: 90%;
        max-width: 500px;
        opacity: 0;
        visibility: hidden;

        z-index: 100;
    }

    .nirweb_panel_toast_box.active {
        transition: all 0.3s ease-in-out, visibility 0s;
        bottom: 100px;
        opacity: 1;
        visibility: visible;
    }

    .nirweb_panel_toast_line {
        height: 4px;
        width: 100%;
        background: #24AF53;
        border-radius: 6px;
    }

    .nirweb_panel_toast_content {
        display: flex;
        align-items: start;
        justify-content: space-between;
        padding: 10px 15px 20px 15px;
    }

    .nirweb_panel_toast_content > i {
        color: #6C757D;
        line-height: 1em;
        font-size: 20px;
        cursor: pointer;
    }

    .nirweb_panel_toast_content strong {
        color: #24AF53;
        font-size: 14px;
        font-weight: 600 !important;
        margin-bottom: 10px !important;
        display: block;
    }

    .nirweb_panel_toast_content p {
        color: #24AF53;
        font-size: 14px;
        margin: 0;
    }

    .nirweb_panel_toast_text {
        display: flex;
        align-items: start;
        justify-content: start;
        gap: 10px;
    }

    .nirweb_panel_toast_text i {
        margin-right: 15px;
        font-size: 24px;
        color: #24AF53;
        line-height: 1em;
    }

    .np_toast_success {
        background: #EAFAED;
    }

    .np_toast_success .nirweb_panel_toast_line {
        background: #24AF53;
    }

    .np_toast_success .nirweb_panel_toast_text i, .np_toast_success .nirweb_panel_toast_content p, .np_toast_success .nirweb_panel_toast_content strong {
        color: #24AF53;
    }

    .np_toast_error {
        background: #FCF2F2;
    }

    .np_toast_error .nirweb_panel_toast_line {
        background: #DA1515;
    }

    .np_toast_error .nirweb_panel_toast_text i, .np_toast_error .nirweb_panel_toast_content p, .np_toast_error .nirweb_panel_toast_content strong {
        color: #DA1515;
    }

    .np_toast_warning {
        background: #FFF3E2;
    }

    .np_toast_warning .nirweb_panel_toast_line {
        background: #FF8D24;
    }

    .np_toast_warning .nirweb_panel_toast_text i, .np_toast_warning .nirweb_panel_toast_content p, .np_toast_warning .nirweb_panel_toast_content strong {
        color: #FF8D24;
    }

    .np_toast_notice {
        background: #E2EFF9;
    }

    .np_toast_notice .nirweb_panel_toast_line {
        background: #0658B4;
    }

    .np_toast_notice .nirweb_panel_toast_text i, .np_toast_notice .nirweb_panel_toast_content p, .np_toast_notice .nirweb_panel_toast_content strong {
        color: #0658B4;
    }

</style>