package controllers

import (
	"fmt"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
)

func (r *DB) GetAbsenPegawai(c *gin.Context) {
	unor := c.Query("unor")
	day := c.Query("day")
	month := c.Query("month")
	year := c.Query("year")
	tipe_pegawai := c.Query("tipe_pegawai")

	var results []map[string]interface{}

	query1 := r.db.Table("pns")
	query2 := r.db.Table("pns")

	var sqlUnion string
	var selectQ string
	var whereQ string
	var joinQ string

	if tipe_pegawai == "0" || tipe_pegawai == "5" {
		sqlUnion = "SELECT * FROM ((?) UNION (?)) AS zzz ORDER BY PNS_GOLRU DESC, kelas_jabatan DESC, PNS_NAMA ASC"
		selectQ = "'5' as hari_kerja, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, mkj.kelas_jabatan,"
		whereQ = "pns.PNS_PNSNIP NOT LIKE '%TKD%' AND pns.ID_TIPE_PEGAWAI = " + tipe_pegawai
		joinQ = "LEFT JOIN master_kelas_jabatan mkj ON mkj.id = pns.id_master_kelas_jabatan"
	} else {
		sqlUnion = "SELECT * FROM (?) AS zzz ORDER BY id_pns ASC"
		selectQ = "tkd_detail.hari_kerja, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,"
		whereQ = "pns.PNS_PNSNIP LIKE '%TKD%' AND tkd_detail.id_tipe_pegawai = '" + tipe_pegawai + "'"
		joinQ = "LEFT JOIN tkd_detail ON tkd_detail.id_tkd = pns.id"
	}

	query1 = query1.Select(selectQ+`pns.id as id_pns,
                    pns.PNS_PNSNIP,
                    pns.PNS_GLRDPN,
                    pns.PNS_PNSNAM,
                    pns.PNS_GLRBLK,
                    pns.PNS_UNOR,
                    absen_enroll.id as id_absen_enroll,
                    absen_enroll.code,
                    absen_enroll.tanggal,
                    absen_enroll.waktu,
                    absen_enroll.jenis,
                    absen_enroll.keterangan,
                    absen_enroll.uraian,
                    kehadiran.singkatan,
                    kehadiran.keterangan as keterangan_kehadiran,
                    pns.PNS_GOLRU`).
		Joins(joinQ).
		Joins(`LEFT JOIN absen_enroll ON absen_enroll.PNS_PNSNIP = pns.PNS_PNSNIP
                    AND DAY(absen_enroll.tanggal) = ?
                    AND MONTH(absen_enroll.tanggal) = ?
                    AND YEAR(absen_enroll.tanggal) = ?`, day, month, year).
		Joins("LEFT JOIN kehadiran ON kehadiran.id = absen_enroll.keterangan").
		Where("pns.PNS_UNOR = ?", unor).
		Where(whereQ).
		Where("pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex where date <= STR_TO_DATE('1," + month + "," + year + "','%d,%m,%Y'))")

	query2 = query2.Select(selectQ+`pns.id AS id_pns,
                  pns.PNS_PNSNIP,
                  pns.PNS_GLRDPN,
                  pns.PNS_PNSNAM,
                  pns.PNS_GLRBLK,
                  pns.PNS_UNOR,
                  absen_enroll.id AS id_absen_enroll,
                  absen_enroll.code,
                  absen_enroll.tanggal,
                  absen_enroll.waktu,
                  absen_enroll.jenis,
                  absen_enroll.keterangan,
                  absen_enroll.uraian,
                  kehadiran.singkatan,
                  kehadiran.keterangan AS keterangan_kehadiran,
                  pns.PNS_GOLRU`).
		Joins(joinQ).
		Joins(`LEFT JOIN absen_enroll ON absen_enroll.PNS_PNSNIP = pns.PNS_PNSNIP
                    AND DAY(absen_enroll.tanggal) = ?
                    AND MONTH(absen_enroll.tanggal) = ?
                    AND YEAR(absen_enroll.tanggal) = ?`, day, month, year).
		Joins("LEFT JOIN kehadiran ON kehadiran.id = absen_enroll.keterangan").
		Joins("LEFT JOIN mutasi_detail ON mutasi_detail.pns_pnsnip = pns.PNS_PNSNIP AND mutasi_detail.status = ?", "0").
		Where("pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex where date <= STR_TO_DATE('1,"+month+","+year+"','%d,%m,%Y'))").
		Where("mutasi_detail.pns_unor_baru = ?", unor)

	var queryUnion = r.db
	if tipe_pegawai == "0" || tipe_pegawai == "5" {
		queryUnion = queryUnion.Raw(sqlUnion,
			query1,
			query2,
		)
	} else {
		queryUnion = queryUnion.Raw(sqlUnion, query1)
	}

	queryUnion.Scan(&results)

	if queryUnion.Error == nil {

		m := make([]map[string]interface{}, 0)
		mn := make([]map[string]interface{}, 0)
		mtp := make(map[string]interface{})

		if len(results) != 0 {
			for k, t := range results {

				if k != 0 {
					if t["PNS_PNSNIP"] != mtp["PNS_PNSNIP"] {
						m = append(m, mtp)
						mtp = make(map[string]interface{})
					}
				}

				mtp["id_pns"] = t["id_pns"]
				mtp["PNS_PNSNIP"] = t["PNS_PNSNIP"]
				mtp["PNS_NAMA"] = t["PNS_NAMA"]
				mtp["PNS_GLRDPN"] = t["PNS_GLRDPN"]
				mtp["PNS_PNSNAM"] = t["PNS_PNSNAM"]
				mtp["PNS_GLRBLK"] = t["PNS_GLRBLK"]
				mtp["PNS_UNOR"] = t["PNS_UNOR"]
				mtp["hari_kerja"] = t["hari_kerja"]
				mtp["id_kehadiran"] = t["keterangan"]

				mtpin := make(map[string]interface{})
				mtpout := make(map[string]interface{})
				mtpket := make(map[string]interface{})

				if t["jenis"] == "in" {
					mtpin["id_absen_enroll"] = t["id_absen_enroll"]
					if t["waktu"] != nil {
						mtpin["waktu"] = t["waktu"].(string)[0:5]
					}
					mtpin["uraian"] = t["uraian"]

					mtp["in"] = mtpin
				} else if t["jenis"] == "out" {
					mtpout["id_absen_enroll"] = t["id_absen_enroll"]
					if t["waktu"] != nil {
						mtpout["waktu"] = t["waktu"].(string)[0:5]
					}
					mtpout["uraian"] = t["uraian"]

					mtp["out"] = mtpout
				} else if t["jenis"] == "ket" {
					mtpket["id_absen_enroll"] = t["id_absen_enroll"]
					mtpket["keterangan_kehadiran"] = t["keterangan_kehadiran"]
					mtpket["singkatan"] = t["singkatan"]
					mtpket["uraian"] = t["uraian"]

					mtp["ket"] = mtpket
				}

			}

			m = append(m, mtp)

			for _, t := range m {

				mtp := make(map[string]interface{})
				mtpin := make(map[string]interface{})
				mtpout := make(map[string]interface{})
				mtpket := make(map[string]interface{})

				mtp = t

				if t["in"] == nil {
					mtpin["id_absen_enroll"] = ""
					mtpin["waktu"] = ""
					mtpin["uraian"] = ""

					mtp["in"] = mtpin
				}

				if t["out"] == nil {
					mtpout["id_absen_enroll"] = ""
					mtpout["waktu"] = ""
					mtpout["uraian"] = ""

					mtp["out"] = mtpout
				}

				if t["ket"] == nil {
					mtpket["id_absen_enroll"] = ""
					mtpket["keterangan_kehadiran"] = ""
					mtpket["singkatan"] = ""
					mtpket["uraian"] = ""

					mtp["ket"] = mtpket
				}

				mn = append(mn, mtp)
			}
		}

		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data Absen Pegawai",
			"data":    mn,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Data not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) GetIndikatorKehadiran(c *gin.Context) {

	unor := c.Query("unor")
	month := c.Query("month")
	year := c.Query("year")
	nip := c.Query("nip")
	tipe_pegawai := c.Query("tipe_pegawai")

	db_kinerja := os.Getenv("DB_KINERJA")

	var results []map[string]interface{}
	awal_ramadhan := make(map[string]interface{})
	akhir_ramadhan := make(map[string]interface{})

	jam_masuk := os.Getenv("CHECK_IN_LIMIT")
	jam_pulang := os.Getenv("CHECK_OUT_LIMIT")

	check_rik := r.db.Table(db_kinerja+".rekap_indikator_kehadiran").
		Where("month = ?", month).
		Where("year = ?", year)

	if nip != "" {
		check_rik = check_rik.Where("PNS_PNSNIP = ?", nip)
	} else {
		check_rik = check_rik.Where("unor = ?", unor)
	}

	check_rik = check_rik.Scan(&results)
	checkErr(check_rik.Error)

	if results == nil {
		err_awr := r.db.Table("setting").Where("nama = ?", "awal_ramadhan").Scan(&awal_ramadhan).Error
		checkErr(err_awr)
		err_akr := r.db.Table("setting").Where("nama = ?", "akhir_ramadhan").Scan(&akhir_ramadhan).Error
		checkErr(err_akr)

		fmt.Println("awal_ramadhan", awal_ramadhan["bulan"])

		tanggal_awal_ramadhan := awal_ramadhan["bulan"].(string)
		tanggal_akhir_ramadhan := akhir_ramadhan["bulan"].(string)

		menit_awal_ramadhan := awal_ramadhan["menit_awal"].(string)
		menit_akhir_ramadhan := akhir_ramadhan["menit_akhir"].(string)

		var whereQ string

		if nip != "" {
			whereQ = " pns.PNS_PNSNIP = '" + nip + "'"
		} else {
			whereQ = " pns.PNS_UNOR = '" + unor + "'"
		}

		// > 14 untuk mencapai 6750 menit
		// QUERY COUNT SEBELUMNYA (COUNT(skor4) + (work_days - (SUM(IF(count_in != 0, 1, 0)) + SUM(IF(count_ket != 0, 1, 0))))) AS skor4,
		sql := `
			    SELECT
        *,
        IF((
            (((work_days - skorExc) < 16) AND (work_days >= 16)) OR (work_days < 16 AND skorExc > 0) OR (work_days < 16 and (work_days - skorTKS) > 0)
        ), 0, (ROUND(((work_days - skorExc) / work_days * 100), 2) - (1100 - totalskor))) AS persentase
        FROM
        (SELECT
            *,
            (
            skor1skor + skor2skor + skor3skor + skor4skor + skor5skor + skor6skor + skor7skor + skor8skor + skor9skor + skor10skor + skor11skor
            ) AS totalskor
        FROM
            (SELECT
            *,
            (100 - (0.5 * (skor1))) AS skor1skor,
            (100 - (1 * (skor2))) AS skor2skor,
            (100 - (1.25 * (skor3))) AS skor3skor,
            (100 - (1.5 * (skor4))) AS skor4skor,
            (100 - (0.5 * (skor5))) AS skor5skor,
            (100 - (1 * (skor6))) AS skor6skor,
            (100 - (1.25 * (skor7))) AS skor7skor,
            (100 - (1.55 * (skor8))) AS skor8skor,
            (100 - (1.5 * (skor9))) AS skor9skor,
            (100 - (3 * (skor10))) AS skor10skor,
            (100 - (0.5 * (skor11))) AS skor11skor,
            (count_in + count_ket) AS count_in_ket,
	        (count_out + count_ket) AS count_out_ket
            FROM
            (SELECT
                PNS_GOLRU,
                PNS_PNSNIP,
                PNS_NAMA,
                COUNT(skor1) AS skor1,
                COUNT(skor2) AS skor2,
                COUNT(skor3) AS skor3,
                COUNT(skor4) + (COUNT(DISTINCT skorTGL) - (SUM(IF(count_in != 0, 1, 0)) + SUM(IF(count_ket != 0, 1 , 0)))) AS skor4,
                COUNT(skor5) AS skor5,
                COUNT(skor6) AS skor6,
                COUNT(skor7) AS skor7,
                COUNT(skor8) + (COUNT(DISTINCT skorTGL) - (SUM(IF(count_out != 0, 1, 0)) + SUM(IF(count_ket != 0, 1 , 0)))) AS skor8,
                SUM(skor9) AS skor9,
                work_days - COUNT(DISTINCT(skorTGL)) AS skor10,
                SUM(skor11) AS skor11,
                SUM(skorExc) AS skorExc,
                SUM(IF(count_in != 0, 1, 0)) AS count_in,
                SUM(IF(count_out != 0, 1, 0)) AS count_out,
                SUM(IF(count_ket != 0, 1, 0)) AS count_ket,
                work_days,
                 COUNT(DISTINCT(skorTGL)) AS skorTKS
            FROM
                (SELECT
                pns.PNS_GOLRU,
                pns.PNS_PNSNIP,
                IF(
                    pns.PNS_GLRDPN IS NOT NULL
                    AND pns.PNS_GLRBLK IS NOT NULL,
                    CONCAT(
                    CONCAT(pns.PNS_GLRDPN, '. '),
                    CONCAT(
                        pns.PNS_PNSNAM,
                        CONCAT(', ', pns.PNS_GLRBLK)
                    )
                    ),
                    IF(
                    pns.PNS_GLRDPN IS NOT NULL,
                    CONCAT(
                        CONCAT(pns.PNS_GLRDPN, '. '),
                        pns.PNS_PNSNAM
                    ),
                    IF(
                        pns.PNS_GLRBLK IS NOT NULL,
                        CONCAT(
                        pns.PNS_PNSNAM,
                        CONCAT(', ', pns.PNS_GLRBLK)
                        ),
                        pns.PNS_PNSNAM
                    )
                    )
                ) AS PNS_NAMA,
                MAX(

                    IF(manajemen_shift.tanggal != '', 
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu >=
                                (SELECT ADDTIME(manajemen_shift.absen_masuk, '00:01'))
                                AND waktu <
                                (SELECT ADDTIME(manajemen_shift.absen_masuk, '00:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu >=
                                (SELECT ADDTIME((SELECT ADDTIME(manajemen_shift.absen_masuk, '` + menit_awal_ramadhan + `')), '00:01'))
                                AND waktu <
                                (SELECT ADDTIME((SELECT ADDTIME(manajemen_shift.absen_masuk, '` + menit_awal_ramadhan + `')), '00:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            )
                        ),
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu >=
                                (SELECT ADDTIME('` + jam_masuk + `', '00:01'))
                                AND waktu <
                                (SELECT ADDTIME('` + jam_masuk + `', '00:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu >=
                                (SELECT ADDTIME((SELECT ADDTIME('` + jam_masuk + `', '` + menit_awal_ramadhan + `')), '00:01'))
                                AND waktu <
                                (SELECT ADDTIME((SELECT ADDTIME('` + jam_masuk + `', '` + menit_awal_ramadhan + `')), '00:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            )
                        )
                    )

                ) AS skor1,
                MAX(

                    IF(manajemen_shift.tanggal != '', 
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu >=
                                (SELECT
                                    ADDTIME(manajemen_shift.absen_masuk, '00:31'))
                                AND waktu <
                                (SELECT
                                    ADDTIME(manajemen_shift.absen_masuk, '01:01'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu >=
                                (SELECT ADDTIME((SELECT ADDTIME(manajemen_shift.absen_masuk, '` + menit_awal_ramadhan + `')), '00:31'))
                                AND waktu <
                                (SELECT ADDTIME((SELECT ADDTIME(manajemen_shift.absen_masuk, '` + menit_awal_ramadhan + `')), '01:01'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            )
                        ),
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu >=
                                (SELECT
                                    ADDTIME('` + jam_masuk + `', '00:31'))
                                AND waktu <
                                (SELECT
                                    ADDTIME('` + jam_masuk + `', '01:01'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu >=
                                (SELECT ADDTIME((SELECT ADDTIME('` + jam_masuk + `', '` + menit_awal_ramadhan + `')), '00:31'))
                                AND waktu <
                                (SELECT ADDTIME((SELECT ADDTIME('` + jam_masuk + `', '` + menit_awal_ramadhan + `')), '01:01'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            )
                        )
                    )

                ) AS skor2,
                MAX(

                    IF(manajemen_shift.tanggal != '',
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu >=
                                (SELECT
                                    ADDTIME(manajemen_shift.absen_masuk, '01:01'))
                                AND waktu <
                                (SELECT
                                    ADDTIME(manajemen_shift.absen_masuk, '01:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu >=
                                (SELECT
                                    ADDTIME((SELECT ADDTIME(manajemen_shift.absen_masuk, '` + menit_awal_ramadhan + `')), '01:01'))
                                AND waktu <
                                (SELECT
                                    ADDTIME((SELECT ADDTIME(manajemen_shift.absen_masuk, '` + menit_awal_ramadhan + `')), '01:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            )
                        ),
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu >=
                                (SELECT
                                    ADDTIME('` + jam_masuk + `', '01:01'))
                                AND waktu <
                                (SELECT
                                    ADDTIME('` + jam_masuk + `', '01:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu >=
                                (SELECT
                                    ADDTIME((SELECT ADDTIME('` + jam_masuk + `', '` + menit_awal_ramadhan + `')), '01:01'))
                                AND waktu <
                                (SELECT
                                    ADDTIME((SELECT ADDTIME('` + jam_masuk + `', '` + menit_awal_ramadhan + `')), '01:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            )
                        )
                    )

                ) AS skor3,
                MAX(
                    IF(manajemen_shift.tanggal != '', 
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                (waktu >=
                                (SELECT
                                    ADDTIME(manajemen_shift.absen_masuk, '01:31')) 
                                    OR
                                waktu <=
                                (SELECT
                                    SUBTIME(manajemen_shift.absen_masuk, '00:30'))
                                )
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu >=
                                (SELECT
                                    ADDTIME((SELECT ADDTIME(manajemen_shift.absen_masuk, '` + menit_awal_ramadhan + `')), '01:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            )
                        ),
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                (waktu >=
                                (SELECT
                                    ADDTIME('` + jam_masuk + `', '01:31'))
                                    OR
                                waktu <
                                (SELECT
                                    SUBTIME('` + jam_masuk + `', '00:30'))
                                )
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu >=
                                (SELECT
                                    ADDTIME((SELECT ADDTIME('` + jam_masuk + `', '` + menit_awal_ramadhan + `')), '01:31'))
                                AND jenis = 'in',
                                ae.tanggal,
                                NULL
                            )
                        )
                    )

                ) AS skor4,
                MAX(

                    IF(manajemen_shift.tanggal != '', 
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu > SUBTIME(manajemen_shift.absen_pulang, '00:31')
                                AND waktu <= SUBTIME(manajemen_shift.absen_pulang, '00:01')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu > SUBTIME((SELECT ADDTIME(manajemen_shift.absen_pulang, '` + menit_akhir_ramadhan + `')), '00:31')
                                AND waktu <= SUBTIME((SELECT ADDTIME(manajemen_shift.absen_pulang, '` + menit_akhir_ramadhan + `')), '00:01')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            )
                        ),
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu > SUBTIME('` + jam_pulang + `', '00:31')
                                AND waktu <= SUBTIME('` + jam_pulang + `', '00:01')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu > SUBTIME((SELECT ADDTIME('` + jam_pulang + `', '` + menit_akhir_ramadhan + `')), '00:31')
                                AND waktu <= SUBTIME((SELECT ADDTIME('` + jam_pulang + `', '` + menit_akhir_ramadhan + `')), '00:01')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            )
                        )
                    )

                ) AS skor5,
                MAX(

                    IF(manajemen_shift.tanggal != '', 
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu > SUBTIME(manajemen_shift.absen_pulang, '01:01')
                                AND waktu <= SUBTIME(manajemen_shift.absen_pulang, '00:31')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu > SUBTIME((SELECT ADDTIME(manajemen_shift.absen_pulang, '` + menit_akhir_ramadhan + `')), '01:01')
                                AND waktu <= SUBTIME((SELECT ADDTIME(manajemen_shift.absen_pulang, '` + menit_akhir_ramadhan + `')), '00:31')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            )
                        ),
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu > SUBTIME('` + jam_pulang + `', '01:01')
                                AND waktu <= SUBTIME('` + jam_pulang + `', '00:31')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu > SUBTIME((SELECT ADDTIME('` + jam_pulang + `', '` + menit_akhir_ramadhan + `')), '01:01')
                                AND waktu <= SUBTIME((SELECT ADDTIME('` + jam_pulang + `', '` + menit_akhir_ramadhan + `')), '00:31')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            )
                        )
                    )

                ) AS skor6,
                MAX(

                    IF(manajemen_shift.tanggal != '', 
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu > SUBTIME(manajemen_shift.absen_pulang, '01:31')
                                AND waktu <= SUBTIME(manajemen_shift.absen_pulang, '01:01')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu > SUBTIME((SELECT ADDTIME(manajemen_shift.absen_pulang, '` + menit_akhir_ramadhan + `')), '01:31')
                                AND waktu <= SUBTIME((SELECT ADDTIME(manajemen_shift.absen_pulang, '` + menit_akhir_ramadhan + `')), '01:01')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            )
                        ),
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                waktu > SUBTIME('` + jam_pulang + `', '01:31')
                                AND waktu <= SUBTIME('` + jam_pulang + `', '01:01')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu > SUBTIME((SELECT ADDTIME('` + jam_pulang + `', '` + menit_akhir_ramadhan + `')), '01:31')
                                AND waktu <= SUBTIME((SELECT ADDTIME('` + jam_pulang + `', '` + menit_akhir_ramadhan + `')), '01:01')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            )
                        )
                    )

                ) AS skor7,
                MAX(

                    IF(manajemen_shift.tanggal != '', 
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                (waktu <= SUBTIME(manajemen_shift.absen_pulang, '01:31')
                                OR
                                waktu >= ADDTIME(manajemen_shift.absen_pulang, '01:30'))
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu <= SUBTIME((SELECT ADDTIME(manajemen_shift.absen_pulang, '` + menit_akhir_ramadhan + `')), '01:31')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            )
                        ),
                        IF((ae.tanggal < DATE('` + tanggal_awal_ramadhan + `') OR ae.tanggal > DATE('` + tanggal_akhir_ramadhan + `')),
                            IF(
                                (waktu <= SUBTIME('` + jam_pulang + `', '01:31')
                                OR
                                waktu > ADDTIME('` + jam_pulang + `', '01:30'))
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            ),
                            IF(
                                waktu <= SUBTIME((SELECT ADDTIME('` + jam_pulang + `', '` + menit_akhir_ramadhan + `')), '01:31')
                                AND jenis = 'out',
                                ae.tanggal,
                                NULL
                            )
                        )
                    )

                ) AS skor8,
                     IF(
                    jenis = 'ket'
                    AND keterangan IN ('21','24'),
                    1,
                    0
                ) AS skor9,
                0 AS skor10,
                0 AS skor11,
                ae.tanggal as skorTGL,
                IF(
                    jenis = 'ket'
                    AND keterangan IN ( '5', '8', '12', '13', '15', '16', '17', '23'),
                    1,
                    0
                ) AS skorExc,
                SUM(IF(jenis = 'in', ae.tanggal, 0)) count_in,
	            SUM(IF(jenis = 'out', ae.tanggal, 0)) count_out,
                SUM(IF(jenis = 'ket', ae.tanggal, 0)) count_ket,
              
                (SELECT
                COUNT(date_field) AS work_days
                    FROM
                (SELECT
                    MAKEDATE(` + year + `, 1) + INTERVAL (` + month + ` - 1) MONTH + INTERVAL daynum DAY date_field
                FROM
                    (SELECT
                    t * 10+ u daynum
                    FROM
                    (SELECT
                        0 t
                    UNION
                    SELECT
                        1
                    UNION
                    SELECT
                        2
                    UNION
                    SELECT
                        3) A,
                    (SELECT
                        0 u
                    UNION
                    SELECT
                        1
                    UNION
                    SELECT
                        2
                    UNION
                    SELECT
                        3
                    UNION
                    SELECT
                        4
                    UNION
                    SELECT
                        5
                    UNION
                    SELECT
                        6
                    UNION
                    SELECT
                        7
                    UNION
                    SELECT
                        8
                    UNION
                    SELECT
                        9) B
                    ORDER BY daynum) AA) AAA
                WHERE MONTH(date_field) = ` + month + `
                AND DAYNAME(date_field) != 'Saturday'
                AND DAYNAME(date_field) != 'Sunday'
                AND date_field NOT IN
                (SELECT
                    tanggal
                FROM
                    absen_libur
                WHERE MONTH(tanggal) = ` + month + `
                    AND YEAR(tanggal) = ` + year + `)) AS work_days
                FROM
                absen_enroll ae
                    LEFT JOIN pns
                        ON pns.PNS_PNSNIP = ae.PNS_PNSNIP AND pns.PNS_UNOR = '` + unor + `'
                    LEFT JOIN manajemen_shift 
                        ON manajemen_shift.unor = pns.PNS_UNOR 
                        AND manajemen_shift.nip = pns.PNS_PNSNIP 
                        AND manajemen_shift.tanggal = ae.tanggal
                WHERE ` + whereQ + `
                AND MONTH(ae.tanggal) = '` + month + `'
                AND YEAR(ae.tanggal) = '` + year + `'
                AND pns.PNS_PNSNIP NOT LIKE '%TKD%'
                AND pns.ID_TIPE_PEGAWAI = '` + tipe_pegawai + `'
                GROUP BY pns.PNS_PNSNIP,
                ae.tanggal, jenis
                ORDER BY pns.PNS_GOLRU DESC, ae.tanggal ASC) aw

            GROUP BY aw.PNS_PNSNIP) ax) ay) az
            ORDER BY PNS_GOLRU DESC
		`
		r.db.Raw(sql).Scan(&results)

		if results != nil {
			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Data indikator kehadiran",
				"data":    results,
			})
		} else {
			c.JSON(http.StatusBadRequest, gin.H{
				"status":  "failed",
				"message": "Data not found",
				"data":    make([]string, 0),
			})
		}
	} else {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success_archive",
			"message": "Data arsip indikator kehadiran",
			"data":    results,
		})
	}
}
