package controllers

import (
	"ekinerja/models"
	"encoding/json"
	"errors"
	"math"
	"net/http"
	"os"
	"strconv"
	"time"

	"github.com/gin-gonic/gin"
	"github.com/idoubi/goz"
)

func (r *DB) Test(c *gin.Context) {
	const layoutISO = "2006-01-02"

	date := "2021-01-01"
	t, _ := time.Parse(layoutISO, date)

	// month := strconv.Itoa(int(t.Month()))
	year := strconv.Itoa(t.Year())
	int_year, _ := strconv.Atoi(year)

	// a := month + "-" + year

	c.JSON(http.StatusBadRequest, gin.H{
		"status":  "failed",
		"message": "Data not found",
		"data":    int_year,
	})
}

func (r *DB) CollectKegiatanTotalWaktu(unor string, month string, year string) (interface{}, error) {

	var kegiatan_total_waktu models.KegiatanTotalWaktu

	if unor != "" && month != "" && year != "" {
		check_unor_exists := make(map[string]interface{})
		err_cuex := r.db.Table("unor").
			Where("KD_UNOR = ?", unor).
			Scan(&check_unor_exists).
			Error

		checkErr(err_cuex)

		if len(check_unor_exists) != 0 {
			db_kinerja := os.Getenv("DB_KINERJA")
			limit_aktivitas_kerja := os.Getenv("LIMIT_AKTIVITAS_KERJA")

			var results []map[string]interface{}

			err_data := r.db.Table("pns").
				Select("pns.PNS_PNSNIP, pns.PNS_PNSNAM, IF(SUM(kegiatan.norma_waktu) >= "+limit_aktivitas_kerja+", "+limit_aktivitas_kerja+", SUM(kegiatan.norma_waktu)) AS total_norma_waktu").
				Joins("LEFT JOIN "+db_kinerja+".kegiatan kegiatan ON kegiatan.pns_pnsnip = pns.PNS_PNSNIP AND MONTH(kegiatan.waktu_mulai) = '"+month+"' AND YEAR(kegiatan.waktu_mulai) = '"+year+"' AND kegiatan.status = 6 AND kegiatan.jam_kerja = 1").
				Where("pns.PNS_UNOR = ?", unor).
				Where("pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex WHERE unor = '" + unor + "' AND date <= STR_TO_DATE('1," + month + "," + year + "','%d,%m,%Y'))").
				Where("pns.PNS_PNSNIP NOT LIKE '%TKD%'").
				Group("pns.PNS_PNSNIP").
				Order("pns.PNS_GOLRU DESC").
				Scan(&results).
				Error

			checkErr(err_data)

			if len(results) != 0 {

				var data_kegiatan_total_waktu []map[string]interface{}

				currentTime := time.Now()

				for _, t := range results {

					mtp := make(map[string]interface{})

					r.db.Table(db_kinerja+".kegiatan_total_waktu").
						Where("pns_pnsnip = ?", t["PNS_PNSNIP"]).
						Where("month = ?", month).
						Where("year = ?", year).
						Delete(&kegiatan_total_waktu)

					total_norma_waktu := 0
					if t["total_norma_waktu"] != nil {
						total_norma_waktu = StringConv(t["total_norma_waktu"], "int").(int)
					}

					mtp["pns_pnsnip"] = t["PNS_PNSNIP"]
					mtp["month"] = month
					mtp["year"] = year
					mtp["status"] = 6 //disetujui penilai
					mtp["total_norma_waktu"] = total_norma_waktu
					mtp["total_capaian_waktu_kerja"] = 0
					mtp["jam_kerja"] = 1
					mtp["created_at"] = currentTime.Format("2006-01-02 15:04:05")

					data_kegiatan_total_waktu = append(data_kegiatan_total_waktu, mtp)
				}

				r.db.Table(db_kinerja + ".kegiatan_total_waktu").Create(&data_kegiatan_total_waktu)

				return data_kegiatan_total_waktu, nil

			} else {
				return -1, errors.New("Data not found")
			}
		} else {
			return -1, errors.New("Data not found")
		}
	} else {
		return -1, errors.New("Param needed")
	}
}

func (r *DB) CheckRekapTppGabungan(unor string, month string, year string, nip string) ([]models.TPPGabungan, error) {

	var results []models.TPPGabungan

	db_kinerja := os.Getenv("DB_KINERJA")
	db_presensi := os.Getenv("DB_PRESENSI")

	where_crtg := ""

	if nip != "" {
		where_crtg = db_kinerja + ".rekap_tpp_gabungan.PNS_PNSNIP=" + nip
	}

	err := r.db.Table(db_kinerja+".rekap_tpp_gabungan").
		Select(db_kinerja+".rekap_tpp_gabungan.*, "+db_kinerja+".rekap_indikator_kehadiran.persentase AS persentase_indikator_kehadiran").
		Joins("LEFT JOIN "+db_kinerja+".rekap_indikator_kehadiran ON rekap_indikator_kehadiran.unor = rekap_tpp_gabungan.unor AND rekap_indikator_kehadiran.month = rekap_tpp_gabungan.month AND rekap_indikator_kehadiran.year = rekap_tpp_gabungan.year AND rekap_indikator_kehadiran.PNS_PNSNIP = rekap_tpp_gabungan.PNS_PNSNIP").
		Joins("LEFT JOIN "+db_presensi+".pns ON pns.PNS_PNSNIP = rekap_tpp_gabungan.PNS_PNSNIP").
		Where(db_kinerja+".rekap_tpp_gabungan.unor=?", unor).
		Where(db_kinerja+".rekap_tpp_gabungan.month=?", month).
		Where(db_kinerja+".rekap_tpp_gabungan.year=?", year).
		Where(where_crtg).
		Group("rekap_tpp_gabungan.PNS_PNSNIP").
		Order("PNS_GOLRU DESC, kelas_jabatan DESC, PNS_NAMA ASC").
		Scan(&results).Error

	if len(results) == 0 {
		return results, err
	}
	return results, nil
}

func (r *DB) CheckPNSHukuman(month string, year string) (interface{}, error) {
	var get_pns_hukuman []map[string]interface{}

	err := r.db.Table("pns_hukuman").
		Where("akhir_hukuman > ?", year+"-"+month+"-01").
		Scan(&get_pns_hukuman).Error

	checkErr(err)

	arr_ph := make([]map[string]interface{}, 0)

	if len(get_pns_hukuman) != 0 {
		for _, t := range get_pns_hukuman {
			arr_ph = append(arr_ph, t["pns_pnsnip"].(map[string]interface{}))
		}
		return arr_ph, nil
	}
	return -1, err
}

func (r *DB) CheckTKSOnMonth(month string, year string, nip string) (int64, error) {
	get_count_tks_on_month := make(map[string]interface{})

	err := r.db.Raw(`
						SELECT
							COUNT(date_field) AS count_date_field
						FROM
						(SELECT
							MAKEDATE(` + year + `, 1) + INTERVAL (` + month + `-1) MONTH + INTERVAL daynum DAY date_field
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
							AND YEAR(tanggal) = ` + year + `)
						AND date_field NOT IN
						(SELECT
							tanggal
						FROM
							absen_enroll
						WHERE pns_pnsnip = '` + nip + `'
							AND MONTH(tanggal) = ` + month + `
							AND YEAR(tanggal) = ` + year + `
						ORDER BY tanggal ASC)
					`).Scan(&get_count_tks_on_month).Error

	checkErr(err)

	if len(get_count_tks_on_month) != 0 {
		return StringConv(get_count_tks_on_month["count_date_field"], "int64").(int64), nil
	}
	return int64(0), err
}

func (r *DB) CheckNominalSanksi(param map[string]interface{}) (float64, error) {
	nominal_sanksi := 0.0

	t_mulai := param["mulai_sanksi"].(time.Time)
	t_sampai := param["sampai_sanksi"].(time.Time)

	month_mulai := strconv.Itoa(int(t_mulai.Month()))
	year_mulai := strconv.Itoa(t_mulai.Year())

	month_sampai := strconv.Itoa(int(t_sampai.Month()))
	year_sampai := strconv.Itoa(t_sampai.Year())

	int_month_mulai, _ := strconv.Atoi(month_mulai)
	int_year_mulai, _ := strconv.Atoi(year_mulai)

	int_month_sampai, _ := strconv.Atoi(month_sampai)
	int_year_sampai, _ := strconv.Atoi(year_sampai)

	if int_month_mulai <= param["month"].(int) && int_year_mulai <= param["year"].(int) && int_month_sampai >= param["month"].(int) && int_year_sampai >= param["year"].(int) {
		nominal_sanksi = StringConv(param["nominal"], "float64").(float64)
	}
	return nominal_sanksi, nil
}

func (r *DB) TotalNormaWaktu(month string, year string, nip string, unor string, norma_waktu float64) (float64, error) {
	limit_aktivitas_kerja := StringConv(os.Getenv("LIMIT_AKTIVITAS_KERJA"), "float64").(float64)

	check_pjex := make(map[string]interface{})

	err_pjex := r.db.Table("pns_jam_extra").
		Where("nip = ?", nip).
		Where("unor = ?", unor).
		Where("bulan = ?", month).
		Where("tahun = ?", year).
		Scan(&check_pjex).Error

	checkErr(err_pjex)

	total_norma_waktu := StringConv(norma_waktu, "float64").(float64)
	jam_extra_total_menit := StringConv(check_pjex["total_menit"], "float64").(float64)
	sum_total_norma_waktu := total_norma_waktu + jam_extra_total_menit

	if len(check_pjex) != 0 {
		if sum_total_norma_waktu <= limit_aktivitas_kerja {
			total_norma_waktu = sum_total_norma_waktu
		} else {
			total_norma_waktu = limit_aktivitas_kerja
		}
	}

	return total_norma_waktu, nil
}

func (r *DB) MasterPenguranganTPP(year string) (float64, error) {
	var get_master_pengurangan_tpp models.MasterPenguranganTPP
	var persen_pengurangan = 0.0

	err_gmpt := r.db.Table("master_pengurangan_tpp").
		Where("tahun = ?", year).
		Scan(&get_master_pengurangan_tpp).Error

	if err_gmpt == nil {
		persen_pengurangan = get_master_pengurangan_tpp.Pengurangan / 100.0
	}

	return persen_pengurangan, err_gmpt
}

func (r *DB) TppGabungan(c *gin.Context) {
	unor := c.Query("unor")
	month := c.Query("month")
	year := c.Query("year")
	nip := c.Query("nip")

	var results []models.TPPGabungan

	if unor != "" && month != "" && year != "" {
		db_kinerja := os.Getenv("DB_KINERJA")

		limit_aktivitas_kerja := StringConv(os.Getenv("LIMIT_AKTIVITAS_KERJA"), "float64").(float64)
		persentase_prestasi_kerja := StringConv(os.Getenv("PERSENTASE_PRESTASI_KERJA"), "float64").(float64)
		persentase_beban_kerja := StringConv(os.Getenv("PERSENTASE_BEBAN_KERJA"), "float64").(float64)

		int_month, _ := strconv.Atoi(month)
		int_year, _ := strconv.Atoi(year)

		zero_month := month
		if int_month < 10 {
			zero_month = "0" + month
		}

		persen_pengurangan, _ := r.MasterPenguranganTPP(year)

		checkRekap, _ := r.CheckRekapTppGabungan(unor, month, year, nip)

		if checkRekap == nil {
			r.CollectKegiatanTotalWaktu(unor, month, year)

			where_tg := ""

			if nip != "" {
				where_tg = "pns.PNS_PNSNIP='" + nip + "'"
			}

			var data []models.TPPGabungan

			err_data := r.db.Table("pns").
				Select(`pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA,
                    pns.PNS_UNOR, CONCAT(gol.NM_PKT, CONCAT(' (', CONCAT(gol.NM_GOL,')'))) as pangkat, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan, gol.NM_GOL,
                    kegiatan_total_waktu.total_norma_waktu, kegiatan_total_waktu.total_capaian_waktu_kerja,
                    master_index_tpp.ikfd, master_index_tpp.ikk, master_index_tpp.ippd, gol.CODE_GOL,
					kondisi_kerja.besaran_tpp as tpp_kondisi_kerja,
					kelangkaan_profesi.besaran_tpp as tpp_kelangkaan_profesi,
					tempat_bertugas.dibayarkan as tpp_tempat_bertugas,
                    REPLACE(
                        FORMAT(
                        CEIL(
                            (
                            master_index_tpp.ikfd * master_index_tpp.ikk * master_index_tpp.ippd
                            ) * master_tukin_bpk.tukin_bpk
                        ),
                        0,
                        'id_ID'
                        ),
                        '.',
                        ''
                    ) AS tpp_basic, bank.bank, pns.PNS_NO_REK, master_unit_organisasi.unit_organisasi,
                    (SELECT
                        nama_jabatan
                    FROM
                        master_kelas_jabatan
                        LEFT JOIN pns_plt
                        ON master_kelas_jabatan.id = pns_plt.id_master_kelas_jabatan_plt
                        AND master_kelas_jabatan.unor = pns_plt.pns_unor_plt
                    WHERE pns_plt.pns_pnsnip = pns.PNS_PNSNIP AND STR_TO_DATE('1,`+month+`,`+year+`','%d,%m,%Y') BETWEEN awal_plt AND IFNULL(akhir_plt, LAST_DAY(CURDATE()))) AS nama_jabatan_plt,
                    (SELECT NM_UNOR FROM unor WHERE KD_UNOR = pns_plt.pns_unor_plt AND STR_TO_DATE('1,`+month+`,`+year+`','%d,%m,%Y') BETWEEN awal_plt AND IFNULL(akhir_plt, LAST_DAY(CURDATE()))) AS sopd_plt, master_kelas_jabatan.id_master_jabatan_pns,
                    (SELECT REPLACE(
                        FORMAT(
                        CEIL(
                            (
                            master_index_tpp.ikfd * master_index_tpp.ikk * master_index_tpp.ippd
                            ) * mtb.tukin_bpk
                        ),
                        0,
                        'id_ID'
                        ),
                        '.',
                        ''
                    ) FROM pns_plt
					
                    LEFT JOIN master_kelas_jabatan ON master_kelas_jabatan.id = pns_plt.id_master_kelas_jabatan_plt AND master_kelas_jabatan.unor = pns_plt.pns_unor_plt
                    LEFT JOIN master_tukin_bpk mtb ON mtb.kelas_jabatan = master_kelas_jabatan.kelas_jabatan
					LEFT JOIN master_index_tpp ON master_index_tpp.tahun = `+year+` WHERE pns_plt.pns_pnsnip = pns.PNS_PNSNIP) as tpp_basic_plt, 
					gaji_pegawai.gaji_kotor, gaji_pegawai.iw_sudah_bayar, nominal_rapel.nominal AS nominal_rapel,
					nominal_rapel.keterangan AS keterangan_rapel, nominal_sanksi.mulai_tanggal AS mulai_sanksi, nominal_sanksi.sampai_tanggal AS sampai_sanksi,
					nominal_sanksi.nominal AS nominal_sanksi, pns.ID_TIPE_PEGAWAI`).
				Joins("LEFT JOIN bank ON bank.id = pns.PNS_ID_BANK").
				Joins("LEFT JOIN gol ON gol.KD_GOL = pns.PNS_GOLRU").
				Joins("LEFT JOIN master_kelas_jabatan ON master_kelas_jabatan.id = pns.id_master_kelas_jabatan").
				Joins("LEFT JOIN master_unit_organisasi ON master_unit_organisasi.id = master_kelas_jabatan.id_master_unit_organisasi").
				Joins("LEFT JOIN master_index_tpp ON master_index_tpp.tahun = ?", year).
				Joins("LEFT JOIN master_tukin_bpk ON master_tukin_bpk.kelas_jabatan = master_kelas_jabatan.kelas_jabatan").
				Joins("LEFT JOIN "+db_kinerja+".kegiatan_total_waktu ON kegiatan_total_waktu.pns_pnsnip = pns.PNS_PNSNIP AND kegiatan_total_waktu.month = ? AND kegiatan_total_waktu.year = ? AND kegiatan_total_waktu.status = ? AND kegiatan_total_waktu.jam_kerja = ?", month, year, 6, 1).
				Joins("LEFT JOIN pns_plt ON pns_plt.pns_pnsnip = pns.PNS_PNSNIP").
				Joins("LEFT JOIN "+db_kinerja+".gaji_pegawai ON gaji_pegawai.nip = pns.PNS_PNSNIP").
				Joins("LEFT JOIN nominal_sanksi ON nominal_sanksi.pns_pnsnip = pns.PNS_PNSNIP").
				Joins("LEFT JOIN nominal_rapel ON nominal_rapel.pns_pnsnip = pns.PNS_PNSNIP AND nominal_rapel.bulan_rapel = ? AND nominal_rapel.tahun_rapel = ?", zero_month, year).
				Joins("LEFT JOIN kondisi_kerja ON kondisi_kerja.id_kelas_jabatan = pns.id_master_kelas_jabatan").
				Joins("LEFT JOIN kelangkaan_profesi ON kelangkaan_profesi.id_kelas_jabatan = pns.id_master_kelas_jabatan").
				Joins("LEFT JOIN tempat_bertugas ON tempat_bertugas.kelas_jabatan = master_kelas_jabatan.kelas_jabatan AND pns.pns_unor IN (SELECT unor FROM lokasi_tempat_bertugas)").
				Where("pns.PNS_UNOR = ?", unor).
				Where("(kondisi_kerja.tahun = ? OR kondisi_kerja.tahun IS NULL)", year).
				Where("(kelangkaan_profesi.tahun = ? OR kelangkaan_profesi.tahun IS NULL)", year).
				Where("(tempat_bertugas.tahun = ? OR tempat_bertugas.tahun IS NULL)", year).
				Where("pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex WHERE unor = ? AND date <= STR_TO_DATE('1,"+month+","+year+"','%d,%m,%Y'))", unor).
				Where(where_tg).
				Where("pns.PNS_PNSNIP NOT LIKE ?", "%TKD%").
				Where("pns.ID_TIPE_PEGAWAI IN (0,5)").
				// Order("PNS_GOLRU DESC, kelas_jabatan DESC, PNS_NAMA ASC").
				Order("kelas_jabatan DESC, PNS_NAMA ASC").
				Group("pns.PNS_PNSNIP").
				Scan(&data).Error

			checkErr(err_data)

			if err_data == nil {
				arr_ph, err := r.CheckPNSHukuman(month, year)
				checkErr(err)

				no := 1

				for k, t := range data {

					var mtp models.TPPGabungan

					mtp = t
					mtp.No = no

					total_norma_waktu, _ := r.TotalNormaWaktu(month, year, t.PNS_PNSNIP, t.PNS_UNOR, limit_aktivitas_kerja)
					mtp.Total_norma_waktu = total_norma_waktu

					mtp.Ket_pns = "<strong>" + t.PNS_NAMA + "</strong><br>" + t.PNS_PNSNIP + "<br>" + t.Pangkat

					nama_jabatan := t.Nama_jabatan
					nama_bank := t.Bank
					no_rek_pns := t.PNS_NO_REK

					check_cpns := make(map[string]interface{})

					err_check_cpns := r.db.Table("cpns").
						Where("nip = ?", t.PNS_PNSNIP).
						Where("tahun = ?", year).
						Scan(&check_cpns).Error

					checkErr(err_check_cpns)

					text_cpns := ""
					if len(check_cpns) != 0 {
						text_cpns = "<em>(CPNS menerima 80%)</em>"
					}

					text_plt := ""
					if t.Nama_jabatan_plt != "" && t.Sopd_plt != "" {
						text_plt = "<em>(Plt. " + t.Nama_jabatan_plt + " - " + t.Sopd_plt + ")</em>"
					}

					mtp.Eselon_jabatan_pns = t.NM_GOL + " / " + nama_jabatan + text_cpns + text_plt + " <br><strong>" + nama_bank + "</strong><br><strong>" + no_rek_pns + "</strong>"

					cli := goz.NewClient()

					go_port := os.Getenv("GO_PORT")

					resp_gikn, err := cli.Get("http://localhost:"+go_port+"/api/get_indikator_kehadiran", goz.Options{
						Headers: map[string]interface{}{
							"Authorization": c.Request.Header["Authorization"],
						},
						Query: map[string]interface{}{
							"unor":         unor,
							"month":        month,
							"year":         year,
							"nip":          t.PNS_PNSNIP,
							"tipe_pegawai": strconv.Itoa(t.ID_TIPE_PEGAWAI),
						},
					})
					checkErr(err)

					get_ikbn, _ := resp_gikn.GetBody()

					var data_gikbn map[string]interface{}

					json.Unmarshal([]byte(get_ikbn), &data_gikbn)

					persentase_indikator_kehadiran := 0.0

					if len(data_gikbn["data"].([]interface{})) != 0 {
						data_gikbn_interface := data_gikbn["data"].([]interface{})[0].(map[string]interface{})

						if len(data_gikbn_interface) != 0 {
							persentase_indikator_kehadiran = StringConv(data_gikbn_interface["persentase"], "float64").(float64)
						}
					}

					mtp.Persentase_indikator_kehadiran = persentase_indikator_kehadiran

					get_master_koef_kelas_jabatan := make(map[string]interface{})

					err_gmkkj := r.db.Table("master_koef_kelas_jabatan").
						Where("id_master_jabatan_pns = ?", t.Id_master_jabatan_pns).
						Where("kelas_jabatan = ?", t.Kelas_jabatan).
						Where("tahun = ?", year).
						Scan(&get_master_koef_kelas_jabatan).Error

					checkErr(err_gmkkj)

					if len(get_master_koef_kelas_jabatan) != 0 {
						faktor_penyeimbang := StringConv(get_master_koef_kelas_jabatan["koef"], "float64").(float64)
						tpp_basic := t.Tpp_basic
						capaian_kerja := limit_aktivitas_kerja / limit_aktivitas_kerja
						//capaian_kerja := 1
						tpp_prestasi_kerja := 0.0
						tpp_beban_kerja := 0.0
						persen_pajak := 0.0
						tpp_gabungan := 0.0
						tpp_kelangkaan_profesi := t.Tpp_kelangkaan_profesi
						tpp_kondisi_kerja := t.Tpp_kondisi_kerja
						tpp_tempat_bertugas := t.Tpp_tempat_bertugas
						pengurangan_cpns := 0.0
						tunjangan_plt := 0.0
						cost_bpjs := 0.0
						pph := 0.0

						mtp.Faktor_penyeimbang = faktor_penyeimbang
						mtp.Limit_aktivitas_kerja = limit_aktivitas_kerja
						mtp.Capaian_kerja = capaian_kerja

						if ArraySearch(t.PNS_PNSNIP, arr_ph) == -1 {

							tpp_prestasi_kerja = math.Round(persentase_prestasi_kerja * tpp_basic * faktor_penyeimbang * capaian_kerja)
							if tpp_prestasi_kerja > 0 {
								tpp_beban_kerja = math.Round(persentase_beban_kerja * tpp_basic * faktor_penyeimbang * (persentase_indikator_kehadiran / 100.0))
							}
						}

						mtp.Tpp_prestasi_kerja = tpp_prestasi_kerja
						mtp.Tpp_beban_kerja = tpp_beban_kerja
						mtp.CODE_GOL = t.CODE_GOL
						if t.CODE_GOL == 4 {
							persen_pajak = 15.0 / 100.0
						} else if t.CODE_GOL == 3 {
							persen_pajak = 5.0 / 100.0
						}
						mtp.Persen_pajak = persen_pajak

						count_tks_on_month, _ := r.CheckTKSOnMonth(month, year, t.PNS_PNSNIP)

						percen_minus_coz_tks := float64(count_tks_on_month) * 3.0 / 100.0

						var tpp_gabungan_minus_coz_tks = 0.0

						if percen_minus_coz_tks != 0 {
							tpp_gabungan_minus_coz_tks = math.Round(percen_minus_coz_tks * (tpp_prestasi_kerja + tpp_beban_kerja))
						}

						mulai_sanksi := t.Mulai_sanksi
						sampai_sanksi := t.Sampai_sanksi
						nom_sanksi := t.Nominal_sanksi
						nom_rapel := t.Nominal_rapel
						ket_rapel := t.Keterangan_rapel

						param_check_sanksi := map[string]interface{}{
							"month":         int_month,
							"year":          int_year,
							"nip":           t.PNS_PNSNIP,
							"mulai_sanksi":  mulai_sanksi,
							"sampai_sanksi": sampai_sanksi,
							"nominal":       nom_sanksi,
						}
						nominal_sanksi, _ := r.CheckNominalSanksi(param_check_sanksi)

						nominal_rapel := nom_rapel
						keterangan_rapel := ket_rapel

						mtp.Nominal_sanksi = nominal_sanksi
						mtp.Nominal_rapel = nominal_rapel
						mtp.Keterangan_rapel = keterangan_rapel
						mtp.Besaran_hukuman_tks = tpp_gabungan_minus_coz_tks

						pengurangan := math.Round((tpp_prestasi_kerja + tpp_beban_kerja) * persen_pengurangan)
						mtp.Pengurangan = pengurangan

						tpp_gabungan = math.Round((tpp_prestasi_kerja + tpp_beban_kerja + (tpp_kelangkaan_profesi + tpp_kondisi_kerja + tpp_tempat_bertugas)) - tpp_gabungan_minus_coz_tks - nominal_sanksi - pengurangan)

						if tpp_gabungan < 0 {
							tpp_gabungan = 0.0
						}

						if len(check_cpns) != 0 {
							pengurangan_cpns = math.Round(20.0 / 100.0 * tpp_gabungan)
							tpp_gabungan = math.Round(80.0 / 100.0 * tpp_gabungan)
						}
						mtp.Pengurangan_cpns = pengurangan_cpns

						mtp.Tpp_basic = tpp_basic
						mtp.Tpp_basic_plt = t.Tpp_basic_plt

						component_penambah := tpp_gabungan + tunjangan_plt + nominal_rapel

						if t.Gaji_kotor != 0.0 {
							cost_bpjs = 0.0
							if component_penambah != 0 {
								if (t.Gaji_kotor + component_penambah) >= 12000000 {
									cost_bpjs = math.Round(1.0/100.0*12000000) - t.Iw_sudah_bayar
								} else {
									cost_bpjs = math.Round(1.0/100.0*(t.Gaji_kotor+component_penambah)) - t.Iw_sudah_bayar
								}
							}
						} else {
							cost_bpjs = math.Round(1.0 / 100.0 * component_penambah)
						}
						mtp.Cost_bpjs = cost_bpjs

						pph = math.Round(persen_pajak * component_penambah)
						mtp.Pph = pph
						mtp.Tpp_gabungan = tpp_gabungan
						mtp.Tpp_gabungan_setelah_pph = math.Round(component_penambah - cost_bpjs - pph)
					} else {
						mtp.Faktor_penyeimbang = 0.0
						mtp.Limit_aktivitas_kerja = 0.0
						mtp.Capaian_kerja = 0.0
						mtp.Tpp_prestasi_kerja = 0.0
						mtp.Tpp_beban_kerja = 0.0
						mtp.Tpp_gabungan = 0.0
						mtp.Tunjangan_plt = 0.0
						mtp.CODE_GOL = 0.0
						mtp.Persen_pajak = 0.0
						mtp.Pph = 0.0
						mtp.Besaran_hukuman_tks = 0.0
						mtp.Tpp_gabungan_setelah_pph = 0.0
						mtp.Cost_bpjs = 0.0
						mtp.Nominal_sanksi = 0.0
						mtp.Nominal_rapel = 0.0
						mtp.Keterangan_rapel = ""
						mtp.Pengurangan = 0.0
						mtp.Pengurangan_cpns = 0.0
					}
					no = k + 1
					results = append(results, mtp)

				}
			} else {
				results = data
			}

			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Data TPP Gabungan",
				"data":    results,
			})

		} else {
			m := []models.TPPGabungan{}

			results = checkRekap

			for k, t := range results {
				t.No = k + 1
				m = append(m, t)
			}
			c.JSON(http.StatusOK, gin.H{
				"status":  "success_archive",
				"message": "Data Arsip TPP Gabungan",
				"data":    results,
			})
		}
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Param needed",
			"data":    make([]string, 0),
		})
	}
}
