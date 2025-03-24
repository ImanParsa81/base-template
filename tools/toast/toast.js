/************* Toast popup *************/

function nirweb_panel_toast(type, title, text) {
    let line = jQuery('.nirweb_panel_toast_line');
    let box = jQuery('.nirweb_panel_toast_box');
    let title_tag = jQuery('.nirweb_panel_toast_box strong');
    let text_tag = jQuery('.nirweb_panel_toast_box p');
    let toast_icon_mode = jQuery('.toast_icon_mode');

    box.removeClass('np_toast_success');
    box.removeClass('np_toast_error');
    box.removeClass('np_toast_warning');
    box.removeClass('np_toast_notice');
    line.css('width','100%')

    if (type == 'success') {
        box.addClass('np_toast_success');

        toast_icon_mode.html(
            '<svg class="toast_svg_icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path fill-rule="evenodd" clip-rule="evenodd" d="M5.665 1.5C3.135 1.5 1.5 3.233 1.5 5.916V14.084C1.5 16.767 3.135 18.5 5.665 18.5H14.333C16.864 18.5 18.5 16.767 18.5 14.084V5.916C18.5 3.233 16.864 1.5 14.334 1.5H5.665ZM14.333 20H5.665C2.276 20 0 17.622 0 14.084V5.916C0 2.378 2.276 0 5.665 0H14.334C17.723 0 20 2.378 20 5.916V14.084C20 17.622 17.723 20 14.333 20V20Z" fill="#34B560"/>\n' +
            '<path d="M5 10.5L7.5 13L14.5 6" stroke="#34B560" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>\n' +
            '</svg>'
        );

        title_tag.text(title)
        text_tag.text(text)
    } else if (type == 'error') {

        box.addClass('np_toast_error');

        toast_icon_mode.html(
            '<svg class="toast_svg_icon" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path fill-rule="evenodd" clip-rule="evenodd" d="M5.665 1.5C3.135 1.5 1.5 3.233 1.5 5.916V14.084C1.5 16.767 3.135 18.5 5.665 18.5H14.333C16.864 18.5 18.5 16.767 18.5 14.084V5.916C18.5 3.233 16.864 1.5 14.334 1.5H5.665ZM14.333 20H5.665C2.276 20 0 17.622 0 14.084V5.916C0 2.378 2.276 0 5.665 0H14.334C17.723 0 20 2.378 20 5.916V14.084C20 17.622 17.723 20 14.333 20V20Z" fill="#B53434"/>\n' +
            '<path d="M6 14L14 6" stroke="#B53434" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n' +
            '<path d="M14 14L6 6" stroke="#B53434" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>\n' +
            '</svg>'
        );

        title_tag.text(title);
        text_tag.text(text);

    } else if (type == 'warning') {
        box.addClass('np_toast_warning');

        toast_icon_mode.html(
            '<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path fill-rule="evenodd" clip-rule="evenodd" d="M11.314 0.374643C11.7989 0.671917 12.2423 1.14891 12.6046 1.80619L12.6424 1.88053L12.6861 1.96012L19.4898 14.3628C20.8067 16.6757 19.4428 19.0279 16.9049 18.9951V19H2.87051C2.83555 19 2.80098 18.9984 2.76672 18.9954C0.457969 18.9626 -0.603479 16.7404 0.348398 14.6141C0.381289 14.5401 0.419336 14.4716 0.458398 14.4009L7.40166 1.80246L7.39959 1.80115C7.75974 1.15387 8.20885 0.675321 8.70471 0.374028C9.52299 -0.123014 10.4965 -0.126541 11.314 0.374643ZM9.9883 13.9085C10.4977 13.9085 10.9106 14.3419 10.9106 14.8766C10.9106 15.4113 10.4977 15.8448 9.9883 15.8448C9.47889 15.8448 9.06592 15.4113 9.06592 14.8766C9.06592 14.3419 9.47889 13.9085 9.9883 13.9085ZM10.6773 12.4578C10.6463 13.274 9.32943 13.275 9.29928 12.4575C9.16607 11.059 8.82549 7.88399 8.83627 6.56984C8.84736 6.16497 9.16709 5.9251 9.57608 5.83362C9.70229 5.80549 9.84096 5.79163 9.98065 5.79184C10.1211 5.79217 10.2601 5.80668 10.3864 5.83493C10.809 5.92936 11.1406 6.17903 11.1406 6.59128L11.139 6.63249L10.6773 12.4578ZM1.4686 15.1639L8.47127 2.45782C9.32041 0.932043 10.6676 0.887841 11.5328 2.45782L18.4195 15.0116C19.0905 16.1714 18.8117 17.7275 16.9049 17.6969H2.87051C1.58078 17.7279 0.806407 16.6433 1.4686 15.1639Z" fill="#DC6A01"/>\n' +
            '</svg>'
        );

        title_tag.text(title)
        text_tag.text(text)
    } else {
        box.addClass('np_toast_notice');

        toast_icon_mode.html(
            '<svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">\n' +
            '<path fill-rule="evenodd" clip-rule="evenodd" d="M11.314 0.374643C11.7989 0.671917 12.2423 1.14891 12.6046 1.80619L12.6424 1.88053L12.6861 1.96012L19.4898 14.3628C20.8067 16.6757 19.4428 19.0279 16.9049 18.9951V19H2.87051C2.83555 19 2.80098 18.9984 2.76672 18.9954C0.457969 18.9626 -0.603479 16.7404 0.348398 14.6141C0.381289 14.5401 0.419336 14.4716 0.458398 14.4009L7.40166 1.80246L7.39959 1.80115C7.75974 1.15387 8.20885 0.675321 8.70471 0.374028C9.52299 -0.123014 10.4965 -0.126541 11.314 0.374643ZM9.9883 13.9085C10.4977 13.9085 10.9106 14.3419 10.9106 14.8766C10.9106 15.4113 10.4977 15.8448 9.9883 15.8448C9.47889 15.8448 9.06592 15.4113 9.06592 14.8766C9.06592 14.3419 9.47889 13.9085 9.9883 13.9085ZM10.6773 12.4578C10.6463 13.274 9.32943 13.275 9.29928 12.4575C9.16607 11.059 8.82549 7.88399 8.83627 6.56984C8.84736 6.16497 9.16709 5.9251 9.57608 5.83362C9.70229 5.80549 9.84096 5.79163 9.98065 5.79184C10.1211 5.79217 10.2601 5.80668 10.3864 5.83493C10.809 5.92936 11.1406 6.17903 11.1406 6.59128L11.139 6.63249L10.6773 12.4578ZM1.4686 15.1639L8.47127 2.45782C9.32041 0.932043 10.6676 0.887841 11.5328 2.45782L18.4195 15.0116C19.0905 16.1714 18.8117 17.7275 16.9049 17.6969H2.87051C1.58078 17.7279 0.806407 16.6433 1.4686 15.1639Z" fill="#0658B4"/>\n' +
            '</svg>'
        );

        title_tag.text(title)
        text_tag.text(text)
    }
    box.addClass('active')
    line.animate({
        width: '0%'
    }, 5000, function () {
        // Animation complete, now remove the active class
        box.removeClass('active');
    });

}

jQuery(document).ready(function ($){
    $('.nirweb_panel_toast_content > svg').click(function (e){
        $('.nirweb_panel_toast_box').removeClass('active')
    })
})
