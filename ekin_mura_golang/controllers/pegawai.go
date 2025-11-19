package controllers

import (
	"ekinerja/utils"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
	"gorm.io/gorm"
)

func (r *DB) GetDetailPNS(c *gin.Context) {
	result := utils.EndResult{}
	var data map[string]interface{}

	db_presensi := os.Getenv("DB_PRESENSI")

	nip := c.Query("nip")

	err := r.db.Table(db_presensi+".pns").
		Where("pns.PNS_PNSNIP = ?", nip).
		Take(&data).Error

	if err == nil {
		if data != nil {
			result.Status = true
			result.Message = "Data PNS"
			result.Data = data
		} else {
			result.Status = true
			result.Message = "Data PNS"
		}
	} else {
		result.Status = false
		result.Message = "Data not found"
	}

	result.Response(c)
}

func (r *DB) GetPegawaiTPP(c *gin.Context) {
	unor := c.Query("unor")
	is_plt := c.Query("is_plt")
	tipe_pegawai := c.Query("tipe_pegawai")

	var results []map[string]interface{}

	var whereUnionQuery = ""
	var selectQuery = ""
	var unionQuery *gorm.DB
	var query1 *gorm.DB
	var query2 *gorm.DB
	var err error

	if tipe_pegawai != "" {
		whereUnionQuery = "WHERE id_tipe_pegawai = " + tipe_pegawai
	}

	query1 = r.db.Table("pns").
		Select(`pns.id, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, pns.PNS_PNSNAM, pns.PNS_GLRDPN, pns.PNS_GLRBLK, unor.KD_UNOR, unor.NM_UNOR, CONCAT(gol.NM_PKT, CONCAT(' ', gol.NM_GOL)) as pangkat,
                    eselon.NM_ESELON, eselon.SEBUTAN AS SEBUTAN_ESELON, genpos.NM_GENPOS, pns.PNS_GOLRU, pns.id_master_kelas_jabatan, pns.PNS_PHOTO, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan, master_kelas_jabatan.id_master_jabatan_pns, master_jabatan_pns.jabatan_pns,
                    pns.PNS_ID_BANK, bank.bank AS NM_BANK, pns.PNS_NO_REK, IFNULL(pns.PNS_NPWP, '') AS PNS_NPWP, 
					IFNULL(pns.PNS_NIK, '') AS PNS_NIK, IFNULL(pns.PNS_ALAMAT, '') AS PNS_ALAMAT,
					pns.PNS_UNOR, tipe_pegawai.id AS id_tipe_pegawai, tipe_pegawai.type AS tipe_pegawai, gol.NM_GOL`).
		Joins("LEFT JOIN unor ON unor.KD_UNOR = pns.PNS_UNOR").
		Joins("LEFT JOIN bank ON bank.id = pns.PNS_ID_BANK").
		Joins("LEFT JOIN gol ON gol.KD_GOL = pns.PNS_GOLRU").
		Joins("LEFT JOIN eselon ON eselon.KD_ESELON = pns.PNS_KODECH").
		Joins("LEFT JOIN genpos ON genpos.KD_GENPOS = pns.PNS_JABSTR").
		Joins("LEFT JOIN master_kelas_jabatan ON master_kelas_jabatan.id = pns.id_master_kelas_jabatan").
		Joins("LEFT JOIN master_jabatan_pns ON master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns").
		Joins("LEFT JOIN tipe_pegawai ON tipe_pegawai.id = pns.ID_TIPE_PEGAWAI").
		Where("pns.PNS_UNOR = ?", unor).
		Where("pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex where unor = ?)", unor).
		Where("pns.PNS_PNSNIP NOT LIKE ?", "%TKD%")

	query2 = r.db.Table("pns").
		Select(`pns.id, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, pns.PNS_PNSNAM, pns.PNS_GLRDPN, pns.PNS_GLRBLK, unor.KD_UNOR, unor.NM_UNOR, CONCAT(gol.NM_PKT, CONCAT(' ', gol.NM_GOL)) as pangkat,
                    eselon.NM_ESELON, eselon.SEBUTAN AS SEBUTAN_ESELON, genpos.NM_GENPOS, pns.PNS_GOLRU, pns.id_master_kelas_jabatan, pns.PNS_PHOTO, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan, master_kelas_jabatan.id_master_jabatan_pns, master_jabatan_pns.jabatan_pns,
                    pns.PNS_ID_BANK, bank.bank AS NM_BANK, pns.PNS_NO_REK, IFNULL(pns.PNS_NPWP, '') AS PNS_NPWP, 
					IFNULL(pns.PNS_NIK, '') AS PNS_NIK, IFNULL(pns.PNS_ALAMAT, '') AS PNS_ALAMAT,
					pns.PNS_UNOR, tipe_pegawai.id AS id_tipe_pegawai, tipe_pegawai.type AS tipe_pegawai, gol.NM_GOL`).
		Joins("LEFT JOIN gol ON gol.KD_GOL = pns.PNS_GOLRU").
		Joins("LEFT JOIN bank ON bank.id = pns.PNS_ID_BANK").
		Joins("LEFT JOIN eselon ON eselon.KD_ESELON = pns.PNS_KODECH").
		Joins("LEFT JOIN genpos ON genpos.KD_GENPOS = pns.PNS_JABSTR").
		Joins("LEFT JOIN mutasi_detail ON mutasi_detail.pns_pnsnip = pns.PNS_PNSNIP AND mutasi_detail.status = ?", "0"). //Pending Mutasi
		Joins("LEFT JOIN unor ON unor.KD_UNOR = mutasi_detail.pns_unor_baru").
		Joins("LEFT JOIN master_kelas_jabatan ON master_kelas_jabatan.id = mutasi_detail.id_master_kelas_jabatan_baru").
		Joins("LEFT JOIN master_jabatan_pns ON master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns").
		Joins("LEFT JOIN tipe_pegawai ON tipe_pegawai.id = pns.ID_TIPE_PEGAWAI").
		Where("mutasi_detail.pns_unor_baru = ?", unor).
		Where("mutasi_detail.status = ?", "0").
		Where("mutasi_detail.pns_pnsnip NOT IN (SELECT nip FROM pns_ex where unor = ?)", unor).
		Where("mutasi_detail.pns_pnsnip NOT LIKE ?", "%TKD%")

	if is_plt != "" && is_plt == "true" {
		selectQuery = "SELECT * FROM ((?) UNION (?) UNION (?)) AS zzz " + whereUnionQuery + "  ORDER BY PNS_GOLRU DESC, kelas_jabatan DESC, PNS_NAMA ASC"
		unionQuery = r.db.Table("pns").
			Select(`pns.id, pns.PNS_PNSNIP, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, pns.PNS_PNSNAM, pns.PNS_GLRDPN, pns.PNS_GLRBLK, unor.KD_UNOR, unor.NM_UNOR, CONCAT(gol.NM_PKT, CONCAT(' ', gol.NM_GOL)) as pangkat,
		            eselon.NM_ESELON, eselon.SEBUTAN AS SEBUTAN_ESELON, genpos.NM_GENPOS, pns.PNS_GOLRU, pns.id_master_kelas_jabatan, pns.PNS_PHOTO, master_kelas_jabatan.kelas_jabatan, master_kelas_jabatan.nama_jabatan, master_kelas_jabatan.id_master_jabatan_pns, master_jabatan_pns.jabatan_pns,
		            pns.PNS_ID_BANK, bank.bank AS NM_BANK, pns.PNS_NO_REK, IFNULL(pns.PNS_NPWP, '') AS PNS_NPWP,
					IFNULL(pns.PNS_NIK, '') AS PNS_NIK, IFNULL(pns.PNS_ALAMAT, '') AS PNS_ALAMAT,
					pns.PNS_UNOR, tipe_pegawai.id AS id_tipe_pegawai, tipe_pegawai.type AS tipe_pegawai, gol.NM_GOL`).
			Joins("LEFT JOIN unor ON unor.KD_UNOR = pns.PNS_UNOR").
			Joins("LEFT JOIN bank ON bank.id = pns.PNS_ID_BANK").
			Joins("LEFT JOIN gol ON gol.KD_GOL = pns.PNS_GOLRU").
			Joins("LEFT JOIN eselon ON eselon.KD_ESELON = pns.PNS_KODECH").
			Joins("LEFT JOIN genpos ON genpos.KD_GENPOS = pns.PNS_JABSTR").
			Joins("LEFT JOIN pns_plt ON pns_plt.pns_pnsnip = pns.PNS_PNSNIP AND pns_plt.akhir_plt IS NULL").
			Joins("LEFT JOIN master_kelas_jabatan ON master_kelas_jabatan.id = pns_plt.id_master_kelas_jabatan_plt").
			Joins("LEFT JOIN master_jabatan_pns ON master_jabatan_pns.id = master_kelas_jabatan.id_master_jabatan_pns").
			Joins("LEFT JOIN tipe_pegawai ON tipe_pegawai.id = pns.ID_TIPE_PEGAWAI").
			Where("pns_plt.pns_unor_plt = ?", unor).
			Where("pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex where unor = ?)", unor).
			Where("pns.PNS_PNSNIP NOT LIKE ?", "%TKD%")

		err = r.db.Raw(
			selectQuery,
			query1,
			query2,
			unionQuery,
		).Scan(&results).Error
	} else {
		selectQuery = "SELECT * FROM ((?) UNION (?)) AS zzz " + whereUnionQuery + "  ORDER BY PNS_GOLRU DESC, kelas_jabatan DESC, PNS_NAMA ASC"

		err = r.db.Raw(
			selectQuery,
			query1,
			query2,
		).Scan(&results).Error
	}

	if err == nil {
		if results != nil {
			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Data PNS",
				"data":    results,
			})
		} else {
			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Data PNS",
				"data":    make([]string, 0),
			})
		}
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Data not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) GetPegawaiNonTPP(c *gin.Context) {
	unor := c.Query("unor")
	tipe_pegawai := c.Query("tipe_pegawai")

	var results []map[string]interface{}

	getData := r.db.Table("pns").
		Select("pns.id, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA, pns.PNS_GLRDPN, pns.PNS_PNSNIP, pns.PNS_PNSNAM, pns.PNS_GLRBLK, tkd_detail.hari_kerja, tkd_detail.alamat, tkd_detail.foto, tkd_detail.tempat_lahir, tkd_detail.tanggal_lahir, tkd_detail.id_tipe_pegawai, tkd_detail.id_master_agama, tipe_pegawai.type, master_agama.agama, unor.KD_UNOR, unor.NM_UNOR").
		Joins("LEFT JOIN tkd_detail ON tkd_detail.id_tkd = pns.id").
		Joins("LEFT JOIN tipe_pegawai ON tipe_pegawai.id = tkd_detail.id_tipe_pegawai").
		Joins("LEFT JOIN master_agama ON master_agama.id = tkd_detail.id_master_agama").
		Joins("LEFT JOIN unor ON unor.KD_UNOR = pns.PNS_UNOR").
		Where("pns.PNS_UNOR = ?", unor).
		Where("pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex where unor = ?)", unor).
		Where("pns.ID_TIPE_PEGAWAI = ?", tipe_pegawai)

	if tipe_pegawai != "3" {
		getData = getData.Where("pns.PNS_PNSNIP LIKE ?", "%TKD%")
	}

	err := getData.Order("pns.PNS_PNSNAM ASC").
		Scan(&results).Error

	if err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data Non PNS",
			"data":    results,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Data not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) GetGajiPegawai(c *gin.Context) {

	unor := c.Param("unor")
	var results []map[string]interface{}

	db_kinerja := os.Getenv("DB_KINERJA")

	err := r.db.Table("pns").
		Select("pns.PNS_PNSNIP, gaji_pegawai.gaji_kotor, gaji_pegawai.iw_sudah_bayar, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA").
		Joins("LEFT JOIN "+db_kinerja+".gaji_pegawai ON gaji_pegawai.nip = pns.PNS_PNSNIP").
		Joins("LEFT JOIN master_kelas_jabatan ON master_kelas_jabatan.id = pns.id_master_kelas_jabatan").
		Where("pns.PNS_UNOR = ?", unor).
		Where("pns.PNS_PNSNIP NOT IN (SELECT nip FROM pns_ex where unor = ?)", unor).
		Where("pns.PNS_PNSNIP NOT LIKE ?", "%TKD%").
		Order("pns.PNS_GOLRU DESC, master_kelas_jabatan.kelas_jabatan DESC, PNS_NAMA ASC").
		Scan(&results).Error

	if err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data Gaji Pegawai",
			"data":    results,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Data not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) GetPegawaiExchange(c *gin.Context) {
	// unor := c.Param("unor")
	var results []map[string]interface{}

	db_presensi := os.Getenv("DB_PRESENSI")

	// selectQuery = "SELECT * FROM pns_ex"

	err := r.db.Table(db_presensi + "pns_ex").Raw("SELECT * FROM pns_ex").Scan(&results).Error
	if err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data Gaji Pegawai",
			"data":    results,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Data not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) CreatePegawaiExchange(c *gin.Context) {
	// unor := c.Param("unor")
	var results []map[string]interface{}

	db_presensi := os.Getenv("DB_PRESENSI")

	// selectQuery = "SELECT * FROM pns_ex"

	err := r.db.Table(db_presensi + "pns_ex").Raw("SELECT * FROM pns_ex").Scan(&results).Error
	if err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data Gaji Pegawai",
			"data":    results,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Data not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) UpdatePegawaiExchange(c *gin.Context) {
	// unor := c.Param("unor")
	var results []map[string]interface{}

	db_presensi := os.Getenv("DB_PRESENSI")

	// selectQuery = "SELECT * FROM pns_ex"

	err := r.db.Table(db_presensi + "pns_ex").Raw("SELECT * FROM pns_ex").Scan(&results).Error
	if err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data Gaji Pegawai",
			"data":    results,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Data not found",
			"data":    make([]string, 0),
		})
	}
}
