// const BASE_URL = "http://localhost:8000/api";

// async function fetchProvinces() {
//     try {
//         const response = await axios.get(`${BASE_URL}/provinces`);
//         const provinces = response.data.data;

//         const provinceDropdown = document.getElementById("province");
//         provinces.forEach((province) => {
//             const option = document.createElement("option");
//             option.value = province.code;
//             option.textContent = province.name;
//             provinceDropdown.appendChild(option);
//         });
//     } catch (error) {
//         console.error("Error fetching provinces:", error);
//     }
// }

// // Fungsi untuk mengambil data kota berdasarkan kode provinsi
// async function fetchCities() {
//     const provinceCode = document.getElementById("province").value;
//     if (!provinceCode) return;

//     const cityDropdown = document.getElementById("city");
//     cityDropdown.innerHTML = '<option value="">Pilih Kota</option>';
//     cityDropdown.disabled = true;

//     try {
//         const response = await axios.get(
//             `${BASE_URL}/cities?province_code=${provinceCode}`
//         );
//         const cities = response.data.data;

//         cities.forEach((city) => {
//             const option = document.createElement("option");
//             option.value = city.code;
//             option.textContent = city.name;
//             cityDropdown.appendChild(option);
//         });

//         cityDropdown.disabled = false;
//     } catch (error) {
//         console.error("Error fetching cities:", error);
//     }
// }

// // Fungsi untuk mengambil data kecamatan berdasarkan kode kota
// // Fungsi untuk mengambil data kecamatan berdasarkan kode provinsi dan kota
// async function fetchDistricts() {
//     const provinceCode = document.getElementById("province").value;
//     const cityCode = document.getElementById("city").value;

//     if (!provinceCode || !cityCode) return;

//     const districtDropdown = document.getElementById("district");
//     districtDropdown.innerHTML = '<option value="">Pilih Kecamatan</option>';
//     districtDropdown.disabled = true;

//     try {
//         console.log(
//             `Fetching districts for province_code=${provinceCode} and city_code=${cityCode}`
//         );
//         const response = await axios.get(`${BASE_URL}/districts`, {
//             params: {
//                 province_code: provinceCode,
//                 city_code: cityCode,
//             },
//         });

//         const districts = response.data.data;

//         districts.forEach((district) => {
//             const option = document.createElement("option");
//             option.value = district.code;
//             option.textContent = district.name;
//             districtDropdown.appendChild(option);
//         });

//         districtDropdown.disabled = false;
//     } catch (error) {
//         console.error("Error fetching districts:", error);
//     }
// }

// // Fungsi untuk mengambil data kelurahan berdasarkan kode kecamatan
// // Fungsi untuk mengambil data kelurahan berdasarkan kode provinsi, kota, dan kecamatan
// async function fetchSubdistricts() {
//     const provinceCode = document.getElementById("province").value;
//     const cityCode = document.getElementById("city").value;
//     const districtCode = document.getElementById("district").value;

//     if (!provinceCode || !cityCode || !districtCode) return;

//     const subdistrictDropdown = document.getElementById("subdistrict");
//     subdistrictDropdown.innerHTML = '<option value="">Pilih Kelurahan</option>';
//     subdistrictDropdown.disabled = true;

//     try {
//         console.log(
//             `Fetching subdistricts for province_code=${provinceCode}, city_code=${cityCode}, district_code=${districtCode}`
//         );
//         const response = await axios.get(`${BASE_URL}/subdistricts`, {
//             params: {
//                 province_code: provinceCode,
//                 city_code: cityCode,
//                 district_code: districtCode,
//             },
//         });

//         const subdistricts = response.data.data;

//         subdistricts.forEach((subdistrict) => {
//             const option = document.createElement("option");
//             option.value = subdistrict.code;
//             option.textContent = subdistrict.name;
//             subdistrictDropdown.appendChild(option);
//         });

//         subdistrictDropdown.disabled = false;
//     } catch (error) {
//         console.error("Error fetching subdistricts:", error);
//     }
// }

// // Panggil fungsi untuk mengambil data provinsi saat halaman dimuat
// document.addEventListener("DOMContentLoaded", () => {
//     fetchProvinces();
// });

// Base URL API
const BASE_URL = "http://localhost:8000/api";

// Fungsi untuk mengambil data provinsi
async function fetchProvinces() {
    try {
        const response = await axios.get(`${BASE_URL}/provinces`);
        const provinces = response.data.data; // Pastikan API mengembalikan data dalam properti "data"

        const provinceDropdown = document.getElementById("province");
        provinceDropdown.innerHTML = '<option value="">Pilih Provinsi</option>'; // Reset dropdown

        provinces.forEach((province) => {
            const option = document.createElement("option");
            option.value = province.code;
            option.textContent = province.name;
            provinceDropdown.appendChild(option);
        });

        // Aktifkan event listener untuk dropdown kota
        provinceDropdown.addEventListener("change", fetchCities);
    } catch (error) {
        console.error("Error fetching provinces:", error);
        alert("Gagal memuat data provinsi. Silakan coba lagi.");
    }
}

// Fungsi untuk mengambil data kota berdasarkan kode provinsi
async function fetchCities() {
    const provinceCode = document.getElementById("province").value;
    if (!provinceCode) return;

    const cityDropdown = document.getElementById("city");
    cityDropdown.innerHTML = '<option value="">Pilih Kota</option>';
    cityDropdown.disabled = true;

    try {
        const response = await axios.get(
            `${BASE_URL}/cities?province_code=${provinceCode}`
        );
        const cities = response.data.data;

        cities.forEach((city) => {
            const option = document.createElement("option");
            option.value = city.code;
            option.textContent = city.name;
            cityDropdown.appendChild(option);
        });

        cityDropdown.disabled = false;

        // Aktifkan event listener untuk dropdown kecamatan
        cityDropdown.addEventListener("change", fetchDistricts);
    } catch (error) {
        console.error("Error fetching cities:", error);
        alert("Gagal memuat data kota. Silakan coba lagi.");
    }
}

// Fungsi untuk mengambil data kecamatan berdasarkan kode provinsi dan kota
async function fetchDistricts() {
    const provinceCode = document.getElementById("province").value;
    const cityCode = document.getElementById("city").value;
    if (!provinceCode || !cityCode) return;

    const districtDropdown = document.getElementById("district");
    districtDropdown.innerHTML = '<option value="">Pilih Kecamatan</option>';
    districtDropdown.disabled = true;

    try {
        console.log(
            `Fetching districts for province_code=${provinceCode} and city_code=${cityCode}`
        );
        const response = await axios.get(`${BASE_URL}/districts`, {
            params: {
                province_code: provinceCode,
                city_code: cityCode,
            },
        });
        const districts = response.data.data;

        districts.forEach((district) => {
            const option = document.createElement("option");
            option.value = district.code;
            option.textContent = district.name;
            districtDropdown.appendChild(option);
        });

        districtDropdown.disabled = false;

        // Aktifkan event listener untuk dropdown kelurahan
        districtDropdown.addEventListener("change", fetchSubdistricts);
    } catch (error) {
        console.error("Error fetching districts:", error);
        alert("Gagal memuat data kecamatan. Silakan coba lagi.");
    }
}

// Fungsi untuk mengambil data kelurahan berdasarkan kode provinsi, kota, dan kecamatan
async function fetchSubdistricts() {
    const provinceCode = document.getElementById("province").value;
    const cityCode = document.getElementById("city").value;
    const districtCode = document.getElementById("district").value;
    if (!provinceCode || !cityCode || !districtCode) return;

    const subdistrictDropdown = document.getElementById("subdistrict");
    subdistrictDropdown.innerHTML = '<option value="">Pilih Kelurahan</option>';
    subdistrictDropdown.disabled = true;

    try {
        console.log(
            `Fetching subdistricts for province_code=${provinceCode}, city_code=${cityCode}, district_code=${districtCode}`
        );
        const response = await axios.get(`${BASE_URL}/subdistricts`, {
            params: {
                province_code: provinceCode,
                city_code: cityCode,
                district_code: districtCode,
            },
        });
        const subdistricts = response.data.data;

        subdistricts.forEach((subdistrict) => {
            const option = document.createElement("option");
            option.value = subdistrict.code;
            option.textContent = subdistrict.name;
            subdistrictDropdown.appendChild(option);
        });

        subdistrictDropdown.disabled = false;
    } catch (error) {
        console.error("Error fetching subdistricts:", error);
        alert("Gagal memuat data kelurahan. Silakan coba lagi.");
    }
}

// Panggil fungsi untuk mengambil data provinsi saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
    fetchProvinces();
});
