// payment.js
$(document).ready(function() {
    $('#checkout-btn').on('click', function() {
        const totalPayment = $('#total-payment').text().replace('Rp ', '').replace(/\./g, '').replace(',', '');

        // Langsung bayar tanpa menunggu klik tombol (mode pop-up)
        snap.pay('<?php echo $snapToken; ?>', {
            onSuccess: function(result){
                document.getElementById('result-json').innerHTML = 'Pembayaran berhasil! ' + JSON.stringify(result, null, 2);
            },
            onPending: function(result){
                document.getElementById('result-json').innerHTML = 'Pembayaran menunggu konfirmasi! ' + JSON.stringify(result, null, 2);
            },
            onError: function(result){
                document.getElementById('result-json').innerHTML = 'Terjadi kesalahan! ' + JSON.stringify(result, null, 2);
            }
        });
    });
});
