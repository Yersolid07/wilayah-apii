<!DOCTYPE html>
<html>
<head>
    <title>Form Wilayah Indonesia</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Pilih Wilayah</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group mb-3">
                            <label for="province">Provinsi</label>
                            <select class="form-control" id="province" name="province">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="regency">Kota/Kabupaten</label>
                            <select class="form-control" id="regency" name="regency">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="district">Kecamatan</label>
                            <select class="form-control" id="district" name="district">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="village">Kelurahan/Desa</label>
                            <select class="form-control" id="village" name="village">
                                <option value="">Pilih Kelurahan/Desa</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Load Provinsi
    $.ajax({
        url: '/api/provinces',
        type: 'GET',
        success: function(data) {
            $('#province').empty();
            $('#province').append('<option value="">Pilih Provinsi</option>');
            $.each(data, function(key, value) {
                $('#province').append('<option value="' + value.id + '">' + value.name + '</option>');
            });
        }
    });

    // Event ketika provinsi dipilih
    $('#province').on('change', function() {
        var provinceId = $(this).val();
        if(provinceId) {
            $.ajax({
                url: '/api/regencies/' + provinceId,
                type: 'GET',
                success: function(data) {
                    $('#regency').empty();
                    $('#district').empty();
                    $('#village').empty();
                    $('#regency').append('<option value="">Pilih Kota/Kabupaten</option>');
                    $('#district').append('<option value="">Pilih Kecamatan</option>');
                    $('#village').append('<option value="">Pilih Kelurahan/Desa</option>');
                    $.each(data, function(key, value) {
                        $('#regency').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        }
    });

    // Event ketika kota/kabupaten dipilih
    $('#regency').on('change', function() {
        var regencyId = $(this).val();
        if(regencyId) {
            $.ajax({
                url: '/api/districts/' + regencyId,
                type: 'GET',
                success: function(data) {
                    $('#district').empty();
                    $('#village').empty();
                    $('#district').append('<option value="">Pilih Kecamatan</option>');
                    $('#village').append('<option value="">Pilih Kelurahan/Desa</option>');
                    $.each(data, function(key, value) {
                        $('#district').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        }
    });

    // Event ketika kecamatan dipilih
    $('#district').on('change', function() {
        var districtId = $(this).val();
        if(districtId) {
            $.ajax({
                url: '/api/villages/' + districtId,
                type: 'GET',
                success: function(data) {
                    $('#village').empty();
                    $('#village').append('<option value="">Pilih Kelurahan/Desa</option>');
                    $.each(data, function(key, value) {
                        $('#village').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        }
    });
});
</script>

</body>
</html>