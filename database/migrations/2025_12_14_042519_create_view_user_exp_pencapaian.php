<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE OR REPLACE VIEW view_user_exp_pencapaian AS
            SELECT
                u.id AS id_user,
                u.nama,

                -- =====================
                -- TOTAL EXP
                -- =====================
                (
                    SELECT COUNT(*)
                    FROM pendaftaran p2
                    JOIN kegiatan k2 ON k2.id = p2.kegiatan_id
                    WHERE p2.user_id = u.id
                      AND p2.status_kehadiran = 'Hadir'
                      AND k2.status = 'finished'
                ) * 3000 AS exp,

                -- =====================
                -- DATA PENCAPAIAN
                -- =====================
                pc.id AS pencapaian_id,
                pc.nama AS pencapaian_nama,
                pc.deskripsi AS pencapaian_deskripsi,
                pc.thumbnail AS pencapaian_thumbnail

            FROM users u

            LEFT JOIN pencapaian pc
            ON (
                -- =====================
                -- ACHIEVEMENT BERDASARKAN EXP
                -- =====================
                (
                    pc.required_exp IS NOT NULL
                    AND
                    (
                        SELECT COUNT(*)
                        FROM pendaftaran p3
                        JOIN kegiatan k3 ON k3.id = p3.kegiatan_id
                        WHERE p3.user_id = u.id
                          AND p3.status_kehadiran = 'Hadir'
                          AND k3.status = 'finished'
                    ) * 3000 >= pc.required_exp
                )

                OR

                -- =====================
                -- ACHIEVEMENT BERDASARKAN KATEGORI
                -- =====================
                (
                    pc.required_kategori IS NOT NULL
                    AND pc.required_count_kategori IS NOT NULL
                    AND
                    (
                        SELECT COUNT(*)
                        FROM pendaftaran p4
                        JOIN kegiatan k4 ON k4.id = p4.kegiatan_id
                        JOIN kategori_kegiatan kk ON kk.kegiatan_id = k4.id
                        WHERE p4.user_id = u.id
                          AND p4.status_kehadiran = 'Hadir'
                          AND k4.status = 'finished'
                          AND kk.kategori_id = pc.required_kategori
                    ) >= pc.required_count_kategori
                )
            )

            WHERE u.role = 'Volunteer'

            -- =====================
            -- FILTER EXP > 0
            -- =====================
            HAVING exp > 0

            -- =====================
            -- URUTAN DATA
            -- =====================
            ORDER BY exp DESC, nama ASC
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_user_exp_pencapaian');
    }
};
