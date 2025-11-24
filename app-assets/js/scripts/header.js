$(document).ready(function () {
    $.ajax({
        url: "pages/function/header.php",
        type: "POST",
        data: "action=search",
        success: function (response) {
            if (response != false) {
                var res = $.parseJSON(response);
                document.getElementById('span-header-main').innerHTML = Number(res['boats']) + Number(res['transfers'])
                document.getElementById('header-div-boats').innerHTML = (res['boats'] !== '') ? res['boats'] + ' New' : '0 New'
                document.getElementById('header-div-transfers').innerHTML = (res['transfers'] !== '') ? res['transfers'] + ' New' : '0 New'
            }
        }
    });
});