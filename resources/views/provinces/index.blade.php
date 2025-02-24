<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wilayah Indonesia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row mb-3">
            <div class="col">
                <h2>Data Wilayah Indonesia</h2>
            </div>
            <div class="col-auto">
                <button class="btn btn-danger" id="logout">Logout</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-4">
                    <!-- Pencarian Provinsi -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Provinsi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="provinceSearch" placeholder="Cari provinsi...">
                            <select class="form-select" id="provinceSelect" style="display: none;">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div id="provinceList" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                    </div>

                    <!-- Pencarian Kota/Kabupaten -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kota/Kabupaten</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="regencySearch" placeholder="Cari kota/kabupaten..." disabled>
                            <select class="form-select" id="regencySelect" style="display: none;">
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                        <div id="regencyList" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                    </div>

                    <!-- Pencarian Kecamatan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kecamatan</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="districtSearch" placeholder="Cari kecamatan..." disabled>
                            <select class="form-select" id="districtSelect" style="display: none;">
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div id="districtList" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                    </div>

                    <!-- Pencarian Desa/Kelurahan -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Desa/Kelurahan</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="villageSearch" placeholder="Cari desa/kelurahan..." disabled>
                            <select class="form-select" id="villageSelect" style="display: none;">
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                        <div id="villageList" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                    </div>
                </div>

                <!-- Hasil Pilihan -->
                <div class="card mt-4">
                    <div class="card-header">
                        Hasil Pilihan
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <strong>Provinsi:</strong>
                                <p id="selectedProvince">-</p>
                            </div>
                            <div class="col-md-3">
                                <strong>Kota/Kabupaten:</strong>
                                <p id="selectedRegency">-</p>
                            </div>
                            <div class="col-md-3">
                                <strong>Kecamatan:</strong>
                                <p id="selectedDistrict">-</p>
                            </div>
                            <div class="col-md-3">
                                <strong>Desa/Kelurahan:</strong>
                                <p id="selectedVillage">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            // Pencarian Provinsi
            $('#provinceSearch').on('keyup', function() {
                const keyword = $(this).val();
                if (keyword.length > 0) {
                    $.ajax({
                        url: `/api/provinces?keyword=${keyword}`,
                        headers: { 'Authorization': 'Bearer ' + token },
                        success: function(response) {
                            $('#provinceList').empty();
                            response.data.forEach(province => {
                                $('#provinceList').append(`
                                    <a href="#" class="list-group-item list-group-item-action province-item" 
                                       data-id="${province.code}" 
                                       data-name="${province.name}">
                                        ${province.name}
                                    </a>
                                `);
                            });
                        }
                    });
                } else {
                    $('#provinceList').empty();
                }
            });

            // Pilih Provinsi
            $(document).on('click', '.province-item', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');
                
                $('#selectedProvince').text(name);
                $('#provinceSearch').val(name);
                $('#provinceList').empty();
                
                // Enable dan reset form kota
                $('#regencySearch').prop('disabled', false).val('');
                $('#selectedRegency').text('-');
                
                // Disable dan reset form kecamatan dan desa
                $('#districtSearch, #villageSearch').prop('disabled', true).val('');
                $('#selectedDistrict, #selectedVillage').text('-');
            });

            // Pencarian Kota/Kabupaten
            $('#regencySearch').on('keyup', function() {
                const keyword = $(this).val();
                const provinceName = $('#provinceSearch').val();
                
                if (keyword.length > 0 && provinceName) {
                    $.ajax({
                        url: `/api/regencies?keyword=${keyword}&province=${provinceName}`,
                        headers: { 'Authorization': 'Bearer ' + token },
                        success: function(response) {
                            $('#regencyList').empty();
                            response.data.forEach(regency => {
                                $('#regencyList').append(`
                                    <a href="#" class="list-group-item list-group-item-action regency-item" 
                                       data-id="${regency.code}" 
                                       data-name="${regency.name}">
                                        ${regency.name}
                                    </a>
                                `);
                            });
                        }
                    });
                } else {
                    $('#regencyList').empty();
                }
            });

            // Pilih Kota/Kabupaten
            $(document).on('click', '.regency-item', function(e) {
                e.preventDefault();
                const name = $(this).data('name');
                
                $('#selectedRegency').text(name);
                $('#regencySearch').val(name);
                $('#regencyList').empty();
                
                // Enable dan reset form kecamatan
                $('#districtSearch').prop('disabled', false).val('');
                $('#selectedDistrict').text('-');
                
                // Disable dan reset form desa
                $('#villageSearch').prop('disabled', true).val('');
                $('#selectedVillage').text('-');
            });

            // Pencarian Kecamatan
            $('#districtSearch').on('keyup', function() {
                const keyword = $(this).val();
                const regencyName = $('#regencySearch').val();
                
                if (keyword.length > 0 && regencyName) {
                    $.ajax({
                        url: `/api/districts?keyword=${keyword}&regency=${regencyName}`,
                        headers: { 'Authorization': 'Bearer ' + token },
                        success: function(response) {
                            $('#districtList').empty();
                            response.data.forEach(district => {
                                $('#districtList').append(`
                                    <a href="#" class="list-group-item list-group-item-action district-item" 
                                       data-id="${district.code}" 
                                       data-name="${district.name}">
                                        ${district.name}
                                    </a>
                                `);
                            });
                        }
                    });
                } else {
                    $('#districtList').empty();
                }
            });

            // Pilih Kecamatan
            $(document).on('click', '.district-item', function(e) {
                e.preventDefault();
                const name = $(this).data('name');
                
                $('#selectedDistrict').text(name);
                $('#districtSearch').val(name);
                $('#districtList').empty();
                
                // Enable dan reset form desa
                $('#villageSearch').prop('disabled', false).val('');
                $('#selectedVillage').text('-');
            });

            // Pencarian Desa/Kelurahan
            $('#villageSearch').on('keyup', function() {
                const keyword = $(this).val();
                const districtName = $('#districtSearch').val();
                
                if (keyword.length > 0 && districtName) {
                    $.ajax({
                        url: `/api/villages?keyword=${keyword}&district=${districtName}`,
                        headers: { 'Authorization': 'Bearer ' + token },
                        success: function(response) {
                            $('#villageList').empty();
                            response.data.forEach(village => {
                                $('#villageList').append(`
                                    <a href="#" class="list-group-item list-group-item-action village-item" 
                                       data-id="${village.code}" 
                                       data-name="${village.name}">
                                        ${village.name}
                                    </a>
                                `);
                            });
                        }
                    });
                } else {
                    $('#villageList').empty();
                }
            });

            // Pilih Desa/Kelurahan
            $(document).on('click', '.village-item', function(e) {
                e.preventDefault();
                const name = $(this).data('name');
                
                $('#selectedVillage').text(name);
                $('#villageSearch').val(name);
                $('#villageList').empty();
            });

            // Logout
            $('#logout').click(function() {
                localStorage.removeItem('token');
                window.location.href = '/login';
            });
        });
    </script>
</body>
</html> 