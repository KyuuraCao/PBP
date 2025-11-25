@extends('layout.app')

@section('title', 'Data Buku dari API')
@section('konten')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4><i class="fas fa-book"></i> Data Buku dari API Endpoint</h4>
                <button id="refreshBtn" class="btn btn-primary btn-sm">
                    <i class="fas fa-sync"></i> Refresh dari API
                </button>
            </div>

            <!-- Info Endpoint -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                Data diambil langsung dari: <code>http://localhost:8000/api/buku_nated</code>
            </div>

            <!-- Loading Spinner -->
            <div class="text-center d-none" id="loadingSpinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data dari API...</p>
            </div>

            <!-- Error Message -->
            <div class="alert alert-danger d-none" id="errorMessage">
                <i class="fas fa-exclamation-triangle"></i>
                <span id="errorText"></span>
            </div>

            <!-- Data Table -->
            <div class="table-responsive d-none" id="tableContainer">
                <table class="table table-sm table-bordered table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center" width="5%">No</th>
                            <th class="text-center" width="12%">Kode Buku</th>
                            <th class="text-center" width="20%">Judul Buku</th>
                            <th class="text-center" width="15%">Pengarang</th>
                            <th class="text-center" width="12%">Kategori</th>
                            <th class="text-center" width="10%">Rak</th>
                            <th class="text-center" width="8%">Stok</th>
                            <th class="text-center" width="8%">Status</th>
                        </tr>
                    </thead>
                    <tbody id="bukuTableBody">
                        <!-- Data akan diisi oleh JavaScript dari API -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const refreshBtn = document.getElementById('refreshBtn');
            const loadingSpinner = document.getElementById('loadingSpinner');
            const tableContainer = document.getElementById('tableContainer');
            const errorMessage = document.getElementById('errorMessage');
            const errorText = document.getElementById('errorText');
            const bukuTableBody = document.getElementById('bukuTableBody');

            // Endpoint API - ambil dari api/buku_nated
            const apiUrl = 'http://localhost:8000/api/buku_nated';

            async function loadDataFromAPI() {
                try {
                    // Tampilkan loading, sembunyikan tabel dan error
                    loadingSpinner.classList.remove('d-none');
                    tableContainer.classList.add('d-none');
                    errorMessage.classList.add('d-none');

                    console.log('Mengambil data dari:', apiUrl);

                    const response = await fetch(apiUrl);

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const result = await response.json();
                    console.log('Data dari API:', result);

                    // Proses data berdasarkan format response
                    displayBooks(result);

                    // Tampilkan tabel, sembunyikan loading
                    loadingSpinner.classList.add('d-none');
                    tableContainer.classList.remove('d-none');

                } catch (error) {
                    console.error('Error fetching data:', error);
                    errorText.textContent = `Gagal memuat data dari API: ${error.message}`;
                    errorMessage.classList.remove('d-none');
                    loadingSpinner.classList.add('d-none');
                }
            }

            function displayBooks(apiResponse) {
                // Kosongkan tabel
                bukuTableBody.innerHTML = '';

                // Handle berbagai format response API
                let bookData = [];

                if (apiResponse.status === true && apiResponse.data) {
                    // Format: {status: true, data: [...]}
                    bookData = apiResponse.data;
                } else if (Array.isArray(apiResponse)) {
                    // Format: [...]
                    bookData = apiResponse;
                } else if (apiResponse.data && Array.isArray(apiResponse.data)) {
                    // Format: {data: [...]}
                    bookData = apiResponse.data;
                } else {
                    // Format tidak dikenali
                    bukuTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-exclamation-triangle"></i> Format data tidak dikenali
                    </td>
                </tr>
            `;
                    return;
                }

                if (bookData.length === 0) {
                    bukuTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-info-circle"></i> Tidak ada data buku
                    </td>
                </tr>
            `;
                    return;
                }

                // Isi tabel dengan data dari API
                bookData.forEach((book, index) => {
                    const statusClass = book.status === 'Ada' ? 'badge-success' :
                        book.status === 'Dipinjam' ? 'badge-warning' : 'badge-danger';

                    // Handle data kategori (bisa object atau string)
                    let kategoriNama = '-';
                    if (book.kategori) {
                        if (typeof book.kategori === 'object') {
                            kategoriNama = book.kategori.nama_kategori || '-';
                        } else {
                            kategoriNama = book.kategori;
                        }
                    } else if (book.kategori_id) {
                        kategoriNama = `Kategori ID: ${book.kategori_id}`;
                    }

                    // Handle data rak (bisa object atau string)
                    let rakKode = '-';
                    if (book.rak) {
                        if (typeof book.rak === 'object') {
                            rakKode = book.rak.kode_rak || '-';
                        } else {
                            rakKode = book.rak;
                        }
                    } else if (book.rak_id) {
                        rakKode = `Rak ID: ${book.rak_id}`;
                    } else if (book.posisi) {
                        if (typeof book.posisi === 'object') {
                            rakKode = book.posisi.kode_rak || '-';
                        }
                    }

                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td class="text-center align-middle">${index + 1}</td>
                <td class="text-center align-middle">${book.kode_buku || '-'}</td>
                <td class="align-middle">${book.judul_buku || '-'}</td>
                <td class="align-middle">${book.pengarang || '-'}</td>
                <td class="text-center align-middle">${kategoriNama}</td>
                <td class="text-center align-middle">${rakKode}</td>
                <td class="text-center align-middle">${book.stok || 0}</td>
                <td class="text-center align-middle">
                    <span class="badge ${statusClass}">${book.status || '-'}</span>
                </td>
            `;
                    bukuTableBody.appendChild(row);
                });
            }

            // Event listener untuk tombol refresh
            refreshBtn.addEventListener('click', loadDataFromAPI);

            // Load data otomatis saat halaman pertama kali dibuka
            loadDataFromAPI();
        });
    </script>
@endsection
