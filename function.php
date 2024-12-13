<?php

session_start();

// Membuat Koneksi Database
$conn = mysqli_connect('localhost','root','12345','stockbarang');

// Tambah Barang Baru
if(isset($_POST['addnewbarang'])){
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['keterangan']; 
    $stock = $_POST['stock'];

    $addtotable = mysqli_query($conn, "INSERT INTO stock (nama_barang, deskripsi, stock) VALUES ('$nama_barang','$deskripsi','$stock')");
    if($addtotable){
        header('location:index.php');
    } else {
        echo 'Gagal Menambah Barang';
        header('location:index.php');
    }
}

// Barang Masuk
if(isset($_POST['barangmasuk'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO barang_masuk (id_barang, keterangan, qty) values ('$barangnya', '$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstocksekarangdenganquantity' WHERE id_barang='$barangnya'");
    if($addtomasuk && $updatestockmasuk){
        header('location:masuk.php');
    }else{
        echo 'Gagal Menambah Barang';
        header('location:masuk.php');
    }
}

// Barang keluar
if(isset($_POST['addbarangkeluar'])){
    $barangnya = $_POST['barangnya'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahkanstocksekarangdenganquantity = $stocksekarang - $qty;

    $addtokeluar = mysqli_query($conn, "INSERT INTO barang_keluar (id_barang, penerima, qty) values ('$barangnya', '$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock='$tambahkanstocksekarangdenganquantity' WHERE id_barang='$barangnya'");
    if($addtokeluar && $updatestockmasuk){
        header('location:keluar.php');
    }else{
        echo 'Gagal Menambah Barang';
        header('location:keluar.php');
    }
}


// Update Barang
if(isset($_POST['updatebarang'])){
    $idb = $_POST['idb'];
    $nama_barang = $_POST['nama_barang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "UPDATE stock set nama_barang='$nama_barang', deskripsi='$deskripsi' WHERE id_barang='$idb'");
    if($update){
        header('location:index.php');
    }else{
        echo 'Gagal Menambah Barang';
        header('location:index.php');
    }
}

// Hapus Barang
if(isset($_POST['hapusbarang'])){
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "DELETE FROM stock WHERE id_barang='$idb'");
    if($hapus){
        header('location:index.php');
    }else{
        echo 'Gagal Menambah Barang';
        header('location:index.php');
    }
}

// Mengubah data barang masuk
if(isset($_POST['updatebarangmasuk'])){
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $deskripsi = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $qtysekarang = mysqli_query($conn, "SELECT * FROM barang_masuk WHERE id_barang_masuk='$idm'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    if($qty>$qtysekarang){
        $selisih = $qty - $qtysekarang;
        $kurangin = $stocksekarang + $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE id_barang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE barang_masuk SET qty='$qty', keterangan='$deskripsi' WHERE id_barang_masuk='$idm'");
            if($kurangistocknya && $updatenya){
                header('location:masuk.php');
            }else{
                echo 'Gagal Menambah Barang';
                header('location:masuk.php');
            }

    }else{
        $selisih = $qtysekarang - $qty;
        $kurangin = $stocksekarang - $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE id_barang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE barang_masuk SET qty='$qty', keterangan='$deskripsi' WHERE id_barang_masuk='$idm'");
            if($kurangistocknya && $updatenya){
                header('location:masuk.php');
            }else{
                echo 'Gagal Menambah Barang';
                header('location:masuk.php');
            }
    }
}

// Hapus Barang Masuk
if(isset($_POST['hapusbarangmasuk'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idm = $_POST['idm'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock - $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE id_barang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM barang_masuk WHERE id_barang_masuk='$idm'");

    if($update && $hapusdata){
        header('location:masuk.php');
    }else{
        header('location:masuk.php');
    }
}

// Mengubah data barang keluar
if(isset($_POST['updatebarangkeluar'])){
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $qtysekarang = mysqli_query($conn, "SELECT * FROM barang_keluar WHERE id_barang_keluar='$idk'");
    $qtynya = mysqli_fetch_array($qtysekarang);
    $qtysekarang = $qtynya['qty'];

    if($qty>$qtysekarang){
        $selisih = $qty - $qtysekarang;
        $kurangin = $stocksekarang - $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE id_barang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE barang_keluar SET qty='$qty', penerima='$penerima' WHERE id_barang_keluar='$idk'");
            if($kurangistocknya && $updatenya){
                header('location:keluar.php');
            }else{
                echo 'Gagal Menambah Barang';
                header('location:keluar.php');
            }

    }else{
        $selisih = $qtysekarang - $qty;
        $kurangin = $stocksekarang + $selisih;
        $kurangistocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurangin' WHERE id_barang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE barang_keluar SET qty='$qty', penerima='$penerima' WHERE id_barang_keluar='$idk'");
            if($kurangistocknya && $updatenya){
                header('location:keluar.php');
            }else{
                echo 'Gagal Menambah Barang';
                header('location:keluar.php');
            }
    }
}

// Hapus Barang keluar
if(isset($_POST['hapusbarangkeluar'])){
    $idb = $_POST['idb'];
    $qty = $_POST['kty'];
    $idk = $_POST['idk'];

    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE id_barang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock + $qty;

    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE id_barang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM barang_keluar WHERE id_barang_keluar='$idk'");

    if($update && $hapusdata){
        header('location:keluar.php');
    }else{
        header('location:keluar.php');
    }
}


?>