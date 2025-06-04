<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateTriggersStokObat extends Migration
{
    public function up()
    {
        // Tambah stok
        DB::unprepared('
            CREATE TRIGGER tambah_stok_obat
            AFTER INSERT ON stok_obat
            FOR EACH ROW
            BEGIN
                UPDATE obat SET stok = stok + NEW.jumlah WHERE id = NEW.obat_id;
            END;
        ');

        // Kurangi stok
        DB::unprepared('
            CREATE TRIGGER kurangi_stok_obat
            AFTER DELETE ON stok_obat
            FOR EACH ROW
            BEGIN
                UPDATE obat SET stok = stok - OLD.jumlah WHERE id = OLD.obat_id;
            END;
        ');

        // Update stok (jika jumlah diubah)
        DB::unprepared('
            CREATE TRIGGER update_stok_obat
            AFTER UPDATE ON stok_obat
            FOR EACH ROW
            BEGIN
                UPDATE obat
                SET stok = stok - OLD.jumlah + NEW.jumlah
                WHERE id = NEW.obat_id;
            END;
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tambah_stok_obat;');
        DB::unprepared('DROP TRIGGER IF EXISTS kurangi_stok_obat;');
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok_obat;');
    }
}

