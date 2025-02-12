{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wilayah</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <div class="container">
        <h1>Pilih Wilayah</h1>

        <!-- Dropdown Provinsi -->
        <label for="province">Provinsi:</label>
        <select id="province" onchange="fetchCities()">
            <option value="">Pilih Provinsi</option>
        </select>

        <!-- Dropdown Kota -->
        <label for="city">Kota:</label>
        <select id="city" onchange="fetchDistricts()" disabled>
            <option value="">Pilih Kota</option>
        </select>

        <!-- Dropdown Kecamatan -->
        <label for="district">Kecamatan:</label>
        <select id="district" onchange="fetchSubdistricts()" disabled>
            <option value="">Pilih Kecamatan</option>
        </select>

        <!-- Dropdown Kelurahan -->
        <label for="subdistrict">Kelurahan:</label>
        <select id="subdistrict" disabled>
            <option value="">Pilih Kelurahan</option>
        </select>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html> --}}




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wilayah</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Tambahkan CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <h1>Pilih Wilayah</h1>

        <!-- Dropdown Provinsi -->
        <label for="province">Provinsi:</label>
        <select id="province" style="width: 100%;" onchange="fetchCities()">
            <option value="">Pilih Provinsi</option>
        </select>

        <!-- Dropdown Kota -->
        <label for="city">Kota:</label>
        <select id="city" style="width: 100%;" onchange="fetchDistricts()" disabled>
            <option value="">Pilih Kota</option>
        </select>

        <!-- Dropdown Kecamatan -->
        <label for="district">Kecamatan:</label>
        <select id="district" style="width: 100%;" onchange="fetchSubdistricts()" disabled>
            <option value="">Pilih Kecamatan</option>
        </select>

        <!-- Dropdown Kelurahan -->
        <label for="subdistrict">Kelurahan:</label>
        <select id="subdistrict" style="width: 100%;" disabled>
            <option value="">Pilih Kelurahan</option>
        </select>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <!-- Tambahkan JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>