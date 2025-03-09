<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Wilayah Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Login Form -->
        <div id="loginForm" class="mb-4">
            <h3>Login</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <input type="email" class="form-control" id="email" placeholder="Email">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                    <button class="btn btn-primary" id="loginBtn">Login</button>
                </div>
            </div>
        </div>

        <!-- Data Wilayah (initially hidden) -->
        <div id="wilayahContent" style="display: none;">
            <h1 class="mb-4">Data Wilayah Indonesia</h1>
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="province" class="form-label">Provinsi</label>
                    <select class="form-select" id="province">
                        <option value="">Pilih Provinsi</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="regency" class="form-label">Kabupaten/Kota</label>
                    <select class="form-select" id="regency" disabled>
                        <option value="">Pilih Kabupaten/Kota</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="district" class="form-label">Kecamatan</label>
                    <select class="form-select" id="district" disabled>
                        <option value="">Pilih Kecamatan</option>
                    </select>
                </div>
                
                <div class="col-md-3 mb-3">
                    <label for="village" class="form-label">Desa/Kelurahan</label>
                    <select class="form-select" id="village" disabled>
                        <option value="">Pilih Desa/Kelurahan</option>
                    </select>
                </div>
            </div>

            <div class="mt-4">
                <h4>Detail Wilayah Terpilih:</h4>
                <div id="selected-details" class="mt-2">
                    <p>Silahkan pilih wilayah di atas</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let token = '';

            // Login handler
            $('#loginBtn').click(function() {
                $.ajax({
                    url: '/api/v1/auth/login',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        email: $('#email').val(),
                        password: $('#password').val()
                    }),
                    success: function(response) {
                        token = response.access_token;
                        $('#loginForm').hide();
                        $('#wilayahContent').show();
                        loadProvinces(); // Load provinces after login
                    },
                    error: function(xhr) {
                        alert('Login failed: ' + xhr.responseJSON.error);
                    }
                });
            });

            // Load provinces
            function loadProvinces() {
                $.ajax({
                    url: '/api/v1/provinces',
                    headers: { 'Authorization': 'Bearer ' + token },
                    success: function(response) {
                        const provinces = response.data;
                        $('#province').empty().append('<option value="">Pilih Provinsi</option>');
                        provinces.forEach(function(province) {
                            $('#province').append(`<option value="${province.id}">${province.name}</option>`);
                        });
                    }
                });
            }

            // Province change event
            $('#province').change(function() {
                const provinceId = $(this).val();
                if (provinceId) {
                    $.ajax({
                        url: `/api/v1/provinces/${provinceId}/regencies`,
                        headers: { 'Authorization': 'Bearer ' + token },
                        success: function(response) {
                            const regencies = response.data;
                            $('#regency').empty().append('<option value="">Pilih Kabupaten/Kota</option>');
                            regencies.forEach(function(regency) {
                                $('#regency').append(`<option value="${regency.id}">${regency.name}</option>`);
                            });
                            $('#regency').prop('disabled', false);
                            $('#district').prop('disabled', true).empty().append('<option value="">Pilih Kecamatan</option>');
                            $('#village').prop('disabled', true).empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                        }
                    });
                }
            });

            // Regency change event
            $('#regency').change(function() {
                const regencyId = $(this).val();
                if (regencyId) {
                    $.ajax({
                        url: `/api/v1/regencies/${regencyId}/districts`,
                        headers: { 'Authorization': 'Bearer ' + token },
                        success: function(response) {
                            const districts = response.data;
                            $('#district').empty().append('<option value="">Pilih Kecamatan</option>');
                            districts.forEach(function(district) {
                                $('#district').append(`<option value="${district.id}">${district.name}</option>`);
                            });
                            $('#district').prop('disabled', false);
                            $('#village').prop('disabled', true).empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                        }
                    });
                }
            });

            // District change event
            $('#district').change(function() {
                const districtId = $(this).val();
                if (districtId) {
                    $.ajax({
                        url: `/api/v1/districts/${districtId}/villages`,
                        headers: { 'Authorization': 'Bearer ' + token },
                        success: function(response) {
                            const villages = response.data;
                            $('#village').empty().append('<option value="">Pilih Desa/Kelurahan</option>');
                            villages.forEach(function(village) {
                                $('#village').append(`<option value="${village.id}">${village.name}</option>`);
                            });
                            $('#village').prop('disabled', false);
                        }
                    });
                }
            });

            // Village change event
            $('#village').change(function() {
                updateSelectedDetails();
            });

            function updateSelectedDetails() {
                const province = $('#province option:selected').text();
                const regency = $('#regency option:selected').text();
                const district = $('#district option:selected').text();
                const village = $('#village option:selected').text();

                let details = '<ul class="list-group">';
                if (province !== 'Pilih Provinsi') details += `<li class="list-group-item">Provinsi: ${province}</li>`;
                if (regency !== 'Pilih Kabupaten/Kota') details += `<li class="list-group-item">Kabupaten/Kota: ${regency}</li>`;
                if (district !== 'Pilih Kecamatan') details += `<li class="list-group-item">Kecamatan: ${district}</li>`;
                if (village !== 'Pilih Desa/Kelurahan') details += `<li class="list-group-item">Desa/Kelurahan: ${village}</li>`;
                details += '</ul>';

                $('#selected-details').html(details);
            }
        });
    </script>
</body>
</html>