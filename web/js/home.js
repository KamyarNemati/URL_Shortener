/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Kamyar
 */

function getShortURL(url, callback) {
    $.ajax({
        url: "api/home/ShortenURL",
        type: 'GET',
        data: {
            url: url
        },
        beforeSend: function (xhr) {
        },
        success: function (data, textStatus, jqXHR) {
            callback(data);
        },
        complete: function (jqXHR, textStatus) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
        }
    });
}

$(document).on('click', '#btn_shorten', function() {
    $('#showTime').empty();
    var url = $('#input_url').val();
    getShortURL(url, function(obj) {
        var html = '';
        if(obj.stat === 0) {
            html += '<a href="' + obj.data.short_url + '" class="btn btn-default btn-lg">' + obj.data.short_url + '</a>';
        } else {
            html += '<h2>' + obj.msg + '</h2>';
        }
        $('#showTime').append(html);
    });
});