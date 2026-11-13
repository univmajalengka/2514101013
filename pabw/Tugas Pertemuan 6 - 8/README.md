# Kalkulator Diskon Belanja (PHP + MySQL)

Program PHP untuk menghitung diskon belanja menggunakan fungsi `hitungDiskon()` yang menerima parameter total belanja dan mengembalikan nilai diskon. Hasil hitungan disimpan ke database MySQL sebagai riwayat.

## Aturan diskon

| Total belanja | Diskon |
|---------------|--------|
| >= Rp 100.000 | 10% |
| >= Rp 50.000 dan < Rp 100.000 | 5% |
| < Rp 50.000 | Tidak ada diskon |

Total yang harus dibayar = total belanja - diskon.

## Cara menjalankan

1. Install dan buka **XAMPP**, lalu **Start** modul **Apache** dan **MySQL**.
2. Buat folder `Tugas_Pertemuan_8` di dalam `C:\xampp\htdocs\`.
3. Taruh file `diskon.php` di folder tersebut.
4. Buka browser dan akses `http://localhost/Tugas_Pertemuan_8/diskon.php`.
5. Masukkan total belanja, lalu klik **Hitung Diskon**.

## Contoh hasil

| Total belanja | Diskon | Total bayar |
|---------------|--------|-------------|
| Rp 120.000 | Rp 12.000 | Rp 108.000 |
| Rp 75.000 | Rp 3.750 | Rp 71.250 |
| Rp 30.000 | Rp 0 | Rp 30.000 |
