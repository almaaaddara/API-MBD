<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {

    // Tabel Pabrik
    //get
    // get
    $app->get('/pabrik', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        // Memanggil procedure GetAllPabrik()
        $stmt = $db->prepare("CALL GetAllPabrik()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mengembalikan hasil dalam format JSON
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get untuk satu data, by id
     $app->get('/pabrik/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetPabrikByID
        $sql = "CALL GetPabrikByID(:id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Mengambil hasil dari procedure
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result[0]));
        } else {
            // Jika data tidak ditemukan, kirim respons dengan status 404
            $response->getBody()->write(json_encode(['error' => 'Data pabrik tidak ditemukan']));
            $response = $response->withStatus(404);
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    // Post
    $app->post('/pabrik', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        
        $idPabrik = $parsedBody['id_pabrik'];
        $namaPabrik = $parsedBody['nama_pabrik'];
        $alamatPabrik = $parsedBody['alamat'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL TambahPabrik(?, ?, ?)');
            $query->bindParam(1, $idPabrik, PDO::PARAM_INT);
            $query->bindParam(2, $namaPabrik, PDO::PARAM_STR);
            $query->bindParam(3, $alamatPabrik, PDO::PARAM_STR);
            
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pabrik disimpan dengan id ' . $idPabrik
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan pabrik: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
        });


    // Put Data Pabrik
    $app->put('/pabrik', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
        
        $idPabrik = $parsedBody['id_pabrik'];
        $namaPabrik = $parsedBody['nama_pabrik'];
        $alamatPabrik = $parsedBody['alamat'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL UpdatePabrik(?, ?, ?)');
            $query->bindParam(1, $idPabrik, PDO::PARAM_INT);
            $query->bindParam(2, $namaPabrik, PDO::PARAM_STR);
            $query->bindParam(3, $alamatPabrik, PDO::PARAM_STR);
            
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' =>  'Pabrik dengan id' . $idPabrik . ' berhasil di update'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan pabrik: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
        });
    
    // Delete data pabrik
    $app->delete('/pabrik/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('CALL HapusPabrik(?)');
            $query->bindParam(1, $currentId, PDO::PARAM_INT);
            $query->execute();

            $response->getBody()->write(json_encode(
                [
                    'message' => 'Pabrik dengan ID ' . $currentId . ' telah dihapus dari database'
                ]
            ));
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Database error: ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });


    // Tabel Produk
    //get produk
    $app->get('/produk', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        // Memanggil procedure GetAllPabrik()
        $stmt = $db->prepare("CALL GetAllProduk()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mengembalikan hasil dalam format JSON
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get produk untuk satu data, by id
     $app->get('/produk/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
    
        // Menyiapkan SQL untuk memanggil procedure GetProdukByID
        $sql = "CALL GetProdukByID(:id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Mengambil hasil dari procedure
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result[0]));
        } else {
            // Jika data tidak ditemukan, kirim respons dengan status 404
            $response->getBody()->write(json_encode(['error' => 'Data produk tidak ditemukan']));
            $response = $response->withStatus(404);
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    // Post (Tambahkan Produk)
    $app->post('/produk', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();

        $idProduk = $parsedBody['id_produk'];
        $idPabrik = $parsedBody['id_pabrik'];
        $namaProduk = $parsedBody['nama_produk'];
        $harga = $parsedBody['harga'];
        $jumlahStok = $parsedBody['jumlah_stok'];

        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('CALL TambahProduk(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idProduk, PDO::PARAM_INT);
            $query->bindParam(2, $idPabrik, PDO::PARAM_INT);
            $query->bindParam(3, $namaProduk, PDO::PARAM_STR);
            $query->bindParam(4, $harga, PDO::PARAM_INT);
            $query->bindParam(5, $jumlahStok, PDO::PARAM_INT);

            $query->execute();

            $response->getBody()->write(json_encode(
                [
                    'message' => 'Produk disimpan dengan id ' . $idProduk
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan produk: ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });

    // Put Data Produk
    $app->put('/produk', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();

        $idProduk = $parsedBody['id_produk'];
        $idPabrik = $parsedBody['id_pabrik'];
        $namaProduk = $parsedBody['nama_produk'];
        $harga = $parsedBody['harga'];
        $jumlahStok = $parsedBody['jumlah_stok'];

        $db = $this->get(PDO::class);

        try {
            $query = $db->prepare('CALL UpdateProduk(?, ?, ?, ?, ?)');
            $query->bindParam(1, $idProduk, PDO::PARAM_INT);
            $query->bindParam(2, $idPabrik, PDO::PARAM_INT);
            $query->bindParam(3, $namaProduk, PDO::PARAM_STR);
            $query->bindParam(4, $harga, PDO::PARAM_INT);
            $query->bindParam(5, $jumlahStok, PDO::PARAM_INT);

            $query->execute();

            $response->getBody()->write(json_encode(
                [
                    'message' => 'Produk dengan id' . $idProduk . ' berhasil di update'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan produk: ' . $e->getMessage()
                ]
            ));
        }

        return $response->withHeader("Content-Type", "application/json");
    });
    
    // Delete (Hapus Produk)
    $app->delete('/produk/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL HapusProduk(?)');
            $query->bindParam(1, $currentId, PDO::PARAM_INT);
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Produk dengan ID ' . $currentId . ' telah dihapus dari database'
                ]
            ));
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Database error: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    

    // Tabel Bahan Baku
    // Get Bahan Baku
    $app->get('/bahan_baku', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        // Memanggil procedure GetAllPabrik()
        $stmt = $db->prepare("CALL GetAllBahanBaku()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mengembalikan hasil dalam format JSON
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

     // get untuk satu data, by id
     $app->get('/bahan_baku/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
    
        // Menyiapkan SQL untuk memanggil procedure get_produk_by_id
        $sql = "CALL GetBahanBakuById(:id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Mengambil hasil dari procedure
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result[0]));
        } else {
            // Jika data tidak ditemukan, kirim respons dengan status 404
            $response->getBody()->write(json_encode(['error' => 'Data bahan baku tidak ditemukan']));
            $response = $response->withStatus(404);
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    // Post (Tambah Bahan Baku)
    $app->post('/bahan_baku', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idBahan = $parsedBody['id_bahan']; // Anda perlu mengambil id_bahan dari body permintaan
        $namaBahan = $parsedBody['nama_bahan'];
        $jumlahStok = $parsedBody['jumlah_stok'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL TambahBahanBaku(?, ?, ?)');
            $query->bindParam(1, $idBahan, PDO::PARAM_INT); // Bind id_bahan sebagai parameter pertama
            $query->bindParam(2, $namaBahan, PDO::PARAM_STR);
            $query->bindParam(3, $jumlahStok, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Bahan baku disimpan dengan id ' . $idBahan
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan bahan baku: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    // Put Bahan Baku
    $app->put('/bahan_baku', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idBahan = $parsedBody['id_bahan']; // Anda perlu mengambil id_bahan dari body permintaan
        $namaBahan = $parsedBody['nama_bahan'];
        $jumlahStok = $parsedBody['jumlah_stok'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL UpdateBahanBaku(?, ?, ?)');
            $query->bindParam(1, $idBahan, PDO::PARAM_INT); // Bind id_bahan sebagai parameter pertama
            $query->bindParam(2, $namaBahan, PDO::PARAM_STR);
            $query->bindParam(3, $jumlahStok, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Bahan Baku dengan id' . $idBahan . ' berhasil di update'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan bahan baku: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // Delete Bahan Baku
    $app->delete('/bahan_baku/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL HapusBahanBaku(?)');
            $query->bindParam(1, $currentId, PDO::PARAM_INT);
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Bahan Baku dengan ID ' . $currentId . ' telah dihapus dari database'
                ]
            ));
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Database error: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    

    // Tabel Proses Produksi
    // Get All Proses Produksi
    $app->get('/proses_produksi', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        // Memanggil procedure GetAllPabrik()
        $stmt = $db->prepare("CALL GetAllProsesProduksi()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mengembalikan hasil dalam format JSON
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

    // get untuk satu data, by id
     $app->get('/proses_produksi/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
    
        // Menyiapkan SQL untuk memanggil procedure get_produk_by_id
        $sql = "CALL GetProsesProduksiByID(:id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Mengambil hasil dari procedure
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result[0]));
        } else {
            // Jika data tidak ditemukan, kirim respons dengan status 404
            $response->getBody()->write(json_encode(['error' => 'Data proses produksi tidak ditemukan']));
            $response = $response->withStatus(404);
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    // Post (Tambah data Proses Produksi)
    $app->post('/proses_produksi', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idProses = $parsedBody['id_proses'];
        $idProduk = $parsedBody['id_produk'];
        $namaProses = $parsedBody['nama_proses'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL TambahProsesProduksi(?, ?, ?)');
            $query->bindParam(1, $idProses, PDO::PARAM_INT);
            $query->bindParam(2, $idProduk, PDO::PARAM_INT);
            $query->bindParam(3, $namaProses, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Proses produksi disimpan dengan ID ' . $idProses
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan proses produksi: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    // Put Proses Produksi
    $app->put('/proses_produksi', function(Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $idProses = $parsedBody['id_proses'];
        $idProduk = $parsedBody['id_produk'];
        $namaProses = $parsedBody['nama_proses'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL UpdateProsesProduksi(?, ?, ?)');
            $query->bindParam(1, $idProses, PDO::PARAM_INT);
            $query->bindParam(2, $idProduk, PDO::PARAM_INT);
            $query->bindParam(3, $namaProses, PDO::PARAM_STR);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Proses Produksi dengan id' . $idProses . ' berhasil di update'
                ]
            ));
        } catch (PDOException $e) {
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan proses produksi: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // Delete Proses Produksi
    $app->delete('/proses_produksi/{id}', function (Request $request, Response $response, $args) {
        $currentId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL HapusProsesProduksi(?)');
            $query->bindParam(1, $currentId, PDO::PARAM_INT);
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Proses produksi dengan ID ' . $currentId . ' telah dihapus dari database'
                ]
            ));
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Database error: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    

    // Tabel Detail Proses
    // Get All Detail Proses
    $app->get('/detail_proses', function(Request $request, Response $response) {
        $db = $this->get(PDO::class);

        // Memanggil procedure GetAllPabrik()
        $stmt = $db->prepare("CALL GetAllDetailProses()");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mengembalikan hasil dalam format JSON
        $response->getBody()->write(json_encode($result));

        return $response->withHeader("Content-Type", "application/json");
    });

     // get untuk satu data, by id
     $app->get('/detail_proses/{id}', function(Request $request, Response $response, $args) {
        $db = $this->get(PDO::class);
        $id = $args['id'];
    
        // Menyiapkan SQL untuk memanggil procedure get_produk_by_id
        $sql = "CALL GetDetailProsesByID(:id)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Mengambil hasil dari procedure
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result) {
            $response->getBody()->write(json_encode($result[0]));
        } else {
            // Jika data tidak ditemukan, kirim respons dengan status 404
            $response->getBody()->write(json_encode(['error' => 'Data detail proses tidak ditemukan']));
            $response = $response->withStatus(404);
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });

    // Post Detail Proses
    $app->post('/detail_proses', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $detailId = $parsedBody['id_detail'];
        $prosesId = $parsedBody['id_proses'];
        $bahanId = $parsedBody['id_bahan'];
        $jumlahBahan = $parsedBody['jumlah_bahan'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL TambahDetailProses(?, ?, ?, ?)');
            $query->bindParam(1, $detailId, PDO::PARAM_INT);
            $query->bindParam(2, $prosesId, PDO::PARAM_INT);
            $query->bindParam(3, $bahanId, PDO::PARAM_INT);
            $query->bindParam(4, $jumlahBahan, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail proses disimpan dengan ID ' . $detailId
                ]
            ));
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan detail proses: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // Put Detail Proses
    $app->put('/detail_proses', function (Request $request, Response $response) {
        $parsedBody = $request->getParsedBody();
    
        $detailId = $parsedBody['id_detail'];
        $prosesId = $parsedBody['id_proses'];
        $bahanId = $parsedBody['id_bahan'];
        $jumlahBahan = $parsedBody['jumlah_bahan'];
    
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL UpdateDetailProses(?, ?, ?, ?)');
            $query->bindParam(1, $detailId, PDO::PARAM_INT);
            $query->bindParam(2, $prosesId, PDO::PARAM_INT);
            $query->bindParam(3, $bahanId, PDO::PARAM_INT);
            $query->bindParam(4, $jumlahBahan, PDO::PARAM_INT);
    
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail Proses dengan id' . $detailId . ' berhasil di update'
                ]
            ));
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Gagal menyimpan detail proses: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
    // Delete Detail Proses
    $app->delete('/detail_proses/{id}', function (Request $request, Response $response, $args) {
        $detailId = $args['id'];
        $db = $this->get(PDO::class);
    
        try {
            $query = $db->prepare('CALL HapusDetailProses(?)');
            $query->bindParam(1, $detailId, PDO::PARAM_INT);
            $query->execute();
    
            $response->getBody()->write(json_encode(
                [
                    'message' => 'Detail proses dengan ID ' . $detailId . ' telah dihapus dari database'
                ]
            ));
        } catch (PDOException $e) {
            $response = $response->withStatus(500);
            $response->getBody()->write(json_encode(
                [
                    'error' => 'Database error: ' . $e->getMessage()
                ]
            ));
        }
    
        return $response->withHeader("Content-Type", "application/json");
    });
    
};
