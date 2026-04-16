<?php

$this->title = 'Сервис коротких ссылок + QR';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><?= $this->title ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="url-input">Введите URL адрес</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="url-input" placeholder="https://google.com">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="generate-btn">OK</button>
                            </div>
                        </div>
                    </div>

                    <div id="result-area" style="display: none;">
                        <div id="error-message" class="alert alert-danger"></div>
                        <div id="success-message" class="alert alert-success">
                            <h5>Ваша короткая ссылка:</h5>
                            <div class="mb-3">
                                <a href="#" id="short-link" target="_blank"></a>
                            </div>
                            <div class="text-center">
                                <img id="qr-image" src="" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#generate-btn').on('click', function() {
            var url = $('#url-input').val();

            if (!url) {
                alert('Пожалуйста, введите URL');
                return;
            }

            $('#generate-btn').prop('disabled', true).html('Обработка...');
            $('#result-area').hide();

            $.ajax({
                url: '/site/create-short-link',
                type: 'POST',
                data: { url: url },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#short-link').attr('href', response.short_url).text(response.short_url);
                        $('#qr-image').attr('src', response.qr_code);  // response.qr_code теперь URL
                        $('#error-message').hide();
                        $('#success-message').show();
                    } else {
                        $('#error-message').text(response.error || 'Неизвестная ошибка').show();
                        $('#success-message').hide();
                    }
                    $('#result-area').show();
                },
                error: function(xhr, status, error) {
                    $('#error-message').text('Ошибка: ' + error).show();
                    $('#success-message').hide();
                    $('#result-area').show();
                },
                complete: function() {
                    $('#generate-btn').prop('disabled', false).html('OK');
                }
            });
        });
    });
</script>
</body>
</html>