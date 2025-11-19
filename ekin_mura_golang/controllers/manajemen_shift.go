package controllers

import (
	"ekinerja/models"
	"os"

	"ekinerja/utils"

	"github.com/gin-gonic/gin"
)

func (r *DB) GetManagementShift(c *gin.Context) {
	params := models.ManajemenShiftParams{}
	result := utils.EndResult{}

	var data []map[string]interface{}

	db_presensi := os.Getenv("DB_PRESENSI")

	params.Unor = c.Query("unor")
	params.IDTipePegawai = c.Query("id_tipe_pegawai")
	params.Month = c.Query("month")
	params.Year = c.Query("year")

	query1 := r.db.Table(db_presensi+".manajemen_shift").
		Joins("LEFT JOIN "+db_presensi+".pns ON pns.PNS_PNSNIP = manajemen_shift.nip").
		Select("manajemen_shift.*, IF(pns.PNS_GLRDPN IS NOT NULL AND pns.PNS_GLRBLK IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK))), IF(pns.PNS_GLRDPN IS NOT NULL, CONCAT(CONCAT(pns.PNS_GLRDPN, '. '), pns.PNS_PNSNAM), IF(pns.PNS_GLRBLK IS NOT NULL, CONCAT(pns.PNS_PNSNAM, CONCAT(', ', pns.PNS_GLRBLK)), pns.PNS_PNSNAM))) as PNS_NAMA").
		Where("unor = ? AND manajemen_shift.id_tipe_pegawai = ? AND MONTH(tanggal) = ? AND YEAR(tanggal) = ?", params.Unor, params.IDTipePegawai, params.Month, params.Year).
		Order("nip ASC, tanggal ASC")

	query2 := r.db.Table("(?) AS zzz", query1).Select(`
            id_tipe_pegawai,
            unor,
            nip,
            PNS_NAMA,
            IF(DAY(tanggal) = 1, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk1,
            IF(DAY(tanggal) = 2, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk2,
            IF(DAY(tanggal) = 3, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk3,
            IF(DAY(tanggal) = 4, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk4,
            IF(DAY(tanggal) = 5, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk5,
            IF(DAY(tanggal) = 6, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk6,
            IF(DAY(tanggal) = 7, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk7,
            IF(DAY(tanggal) = 8, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk8,
            IF(DAY(tanggal) = 9, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk9,
            IF(DAY(tanggal) = 10, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk10,
            IF(DAY(tanggal) = 11, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk11,
            IF(DAY(tanggal) = 12, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk12,
            IF(DAY(tanggal) = 13, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk13,
            IF(DAY(tanggal) = 14, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk14,
            IF(DAY(tanggal) = 15, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk15,
            IF(DAY(tanggal) = 16, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk16,
            IF(DAY(tanggal) = 17, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk17,
            IF(DAY(tanggal) = 18, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk18,
            IF(DAY(tanggal) = 19, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk19,
            IF(DAY(tanggal) = 20, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk20,
            IF(DAY(tanggal) = 21, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk21,
            IF(DAY(tanggal) = 22, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk22,
            IF(DAY(tanggal) = 23, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk23,
            IF(DAY(tanggal) = 24, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk24,
            IF(DAY(tanggal) = 25, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk25,
            IF(DAY(tanggal) = 26, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk26,
            IF(DAY(tanggal) = 27, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk27,
            IF(DAY(tanggal) = 28, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk28,
            IF(DAY(tanggal) = 29, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk29,
            IF(DAY(tanggal) = 30, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk30,
            IF(DAY(tanggal) = 31, TIME_FORMAT(absen_masuk, "%H:%i"), '') AS absen_masuk31,
            IF(DAY(tanggal) = 1, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang1,
            IF(DAY(tanggal) = 2, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang2,
            IF(DAY(tanggal) = 3, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang3,
            IF(DAY(tanggal) = 4, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang4,
            IF(DAY(tanggal) = 5, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang5,
            IF(DAY(tanggal) = 6, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang6,
            IF(DAY(tanggal) = 7, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang7,
            IF(DAY(tanggal) = 8, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang8,
            IF(DAY(tanggal) = 9, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang9,
            IF(DAY(tanggal) = 10, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang10,
            IF(DAY(tanggal) = 11, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang11,
            IF(DAY(tanggal) = 12, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang12,
            IF(DAY(tanggal) = 13, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang13,
            IF(DAY(tanggal) = 14, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang14,
            IF(DAY(tanggal) = 15, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang15,
            IF(DAY(tanggal) = 16, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang16,
            IF(DAY(tanggal) = 17, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang17,
            IF(DAY(tanggal) = 18, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang18,
            IF(DAY(tanggal) = 19, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang19,
            IF(DAY(tanggal) = 20, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang20,
            IF(DAY(tanggal) = 21, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang21,
            IF(DAY(tanggal) = 22, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang22,
            IF(DAY(tanggal) = 23, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang23,
            IF(DAY(tanggal) = 24, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang24,
            IF(DAY(tanggal) = 25, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang25,
            IF(DAY(tanggal) = 26, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang26,
            IF(DAY(tanggal) = 27, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang27,
            IF(DAY(tanggal) = 28, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang28,
            IF(DAY(tanggal) = 29, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang29,
            IF(DAY(tanggal) = 30, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang30,
            IF(DAY(tanggal) = 31, TIME_FORMAT(absen_pulang, "%H:%i"), '') AS absen_pulang31
    `)

	err := r.db.Table("(?) AS yyy", query2).Select(`
            id_tipe_pegawai,
            unor,
            nip,
            PNS_NAMA,
            MAX(absen_masuk1) AS absen_masuk1,
            MAX(absen_masuk2) AS absen_masuk2,
            MAX(absen_masuk3) AS absen_masuk3,
            MAX(absen_masuk4) AS absen_masuk4,
            MAX(absen_masuk5) AS absen_masuk5,
            MAX(absen_masuk6) AS absen_masuk6,
            MAX(absen_masuk7) AS absen_masuk7,
            MAX(absen_masuk8) AS absen_masuk8,
            MAX(absen_masuk9) AS absen_masuk9,
            MAX(absen_masuk10) AS absen_masuk10,
            MAX(absen_masuk11) AS absen_masuk11,
            MAX(absen_masuk12) AS absen_masuk12,
            MAX(absen_masuk13) AS absen_masuk13,
            MAX(absen_masuk14) AS absen_masuk14,
            MAX(absen_masuk15) AS absen_masuk15,
            MAX(absen_masuk16) AS absen_masuk16,
            MAX(absen_masuk17) AS absen_masuk17,
            MAX(absen_masuk18) AS absen_masuk18,
            MAX(absen_masuk19) AS absen_masuk19,
            MAX(absen_masuk20) AS absen_masuk20,
            MAX(absen_masuk21) AS absen_masuk21,
            MAX(absen_masuk22) AS absen_masuk22,
            MAX(absen_masuk23) AS absen_masuk23,
            MAX(absen_masuk24) AS absen_masuk24,
            MAX(absen_masuk25) AS absen_masuk25,
            MAX(absen_masuk26) AS absen_masuk26,
            MAX(absen_masuk27) AS absen_masuk27,
            MAX(absen_masuk28) AS absen_masuk28,
            MAX(absen_masuk29) AS absen_masuk29,
            MAX(absen_masuk30) AS absen_masuk30,
            MAX(absen_masuk31) AS absen_masuk31,
            MAX(absen_pulang1) AS absen_pulang1,
            MAX(absen_pulang2) AS absen_pulang2,
            MAX(absen_pulang3) AS absen_pulang3,
            MAX(absen_pulang4) AS absen_pulang4,
            MAX(absen_pulang5) AS absen_pulang5,
            MAX(absen_pulang6) AS absen_pulang6,
            MAX(absen_pulang7) AS absen_pulang7,
            MAX(absen_pulang8) AS absen_pulang8,
            MAX(absen_pulang9) AS absen_pulang9,
            MAX(absen_pulang10) AS absen_pulang10,
            MAX(absen_pulang11) AS absen_pulang11,
            MAX(absen_pulang12) AS absen_pulang12,
            MAX(absen_pulang13) AS absen_pulang13,
            MAX(absen_pulang14) AS absen_pulang14,
            MAX(absen_pulang15) AS absen_pulang15,
            MAX(absen_pulang16) AS absen_pulang16,
            MAX(absen_pulang17) AS absen_pulang17,
            MAX(absen_pulang18) AS absen_pulang18,
            MAX(absen_pulang19) AS absen_pulang19,
            MAX(absen_pulang20) AS absen_pulang20,
            MAX(absen_pulang21) AS absen_pulang21,
            MAX(absen_pulang22) AS absen_pulang22,
            MAX(absen_pulang23) AS absen_pulang23,
            MAX(absen_pulang24) AS absen_pulang24,
            MAX(absen_pulang25) AS absen_pulang25,
            MAX(absen_pulang26) AS absen_pulang26,
            MAX(absen_pulang27) AS absen_pulang27,
            MAX(absen_pulang28) AS absen_pulang28,
            MAX(absen_pulang29) AS absen_pulang29,
            MAX(absen_pulang30) AS absen_pulang30,
            MAX(absen_pulang31) AS absen_pulang31
    `).Group("nip").Find(&data).Error

	if err == nil {
		result.Status = true
		result.Message = "Action Successfully.."
		result.Data = data
	} else {
		result.Status = false
		result.Message = "Data not found"
	}

	result.Response(c)
}

func (r *DB) SaveManagementShift(c *gin.Context) {
	params := []models.ManajemenShift{}
	paramsDel := models.DeleteManajemenShift{}
	result := utils.EndResult{}
	db_presensi := os.Getenv("DB_PRESENSI")

	errBindJson := c.BindJSON(&params)

	if errBindJson == nil {
		var checkID []int

		for _, v := range params {
			tmpID := models.IDManajemenShift{}

			paramsDel.Nip = v.Nip
			paramsDel.Tanggal = append(paramsDel.Tanggal, v.Tanggal)
			paramsDel.Uraian = "up_face"

			r.db.Table(db_presensi + ".manajemen_shift").
				Where(models.ManajemenShift{Unor: v.Unor, Nip: v.Nip, Tanggal: v.Tanggal}).
				Take(&tmpID)

			if tmpID.ID != 0 {
				checkID = append(checkID, tmpID.ID)
			}
		}

		if len(checkID) != 0 {
			r.db.Table(db_presensi+".manajemen_shift").
				Delete(&models.ManajemenShift{}, checkID)
		}

		err := r.db.Table(db_presensi + ".manajemen_shift").
			Create(&params).Error

		if err == nil {
			data := []models.AbsenEnroll{}
			paramsUnDone := models.ChangeStatusAbsenADMSDB{}

			errAE := r.db.Where("PNS_PNSNIP = ?", paramsDel.Nip).
				Where("tanggal IN ?", paramsDel.Tanggal).
				Where("uraian = ?", "up_face").
				Delete(&data).Error

			if errAE == nil {
				errGetIDPNS := r.db.Table("pns").Select("id").Where("PNS_PNSNIP = ?", paramsDel.Nip).Take(&paramsDel.IDPNS).Error

				if errGetIDPNS == nil {
					paramsUnDone.Pin = utils.IntToStr(paramsDel.IDPNS)
					paramsUnDone.Tanggal = paramsDel.Tanggal

					r.db.AllowGlobalUpdate = true

					errCSU := r.db.Table(db_presensi+".absen_adms_db").
						Where("pin = ?", paramsUnDone.Pin).
						Where("tanggal IN ?", paramsUnDone.Tanggal).
						Update("status", 0).Error

					if errCSU == nil {
						result.Status = true
						result.Message = "Action Successfully.."
						result.Data = params
					} else {
						result.Status = false
						result.Message = "Change status to undone failed"
					}
				} else {
					result.Status = false
					result.Message = "Failed to get ID PNS"
				}

			} else {
				result.Status = false
				result.Message = "Absen enroll failed to delete"
			}
		} else {
			result.Status = false
			result.Message = "Data failed to insert"
		}
	} else {
		result.Status = false
		result.Message = "Body must sent on json"
	}

	result.Response(c)
}

func (r *DB) DeleteManagementShift(c *gin.Context) {
	params := models.DeleteManajemenShift{}
	paramsUnDone := models.ChangeStatusAbsenADMSDB{}
	result := utils.EndResult{}

	tmp := []models.ManajemenShift{}
	db_presensi := os.Getenv("DB_PRESENSI")

	errBindJson := c.BindJSON(&params)

	if errBindJson == nil {
		var checkID []int

		r.db.Table(db_presensi+".manajemen_shift").
			Where("nip = ?", params.Nip).
			Where("tanggal IN ?", params.Tanggal).
			Find(&tmp)

		for _, v := range tmp {
			if v.ID != 0 {
				checkID = append(checkID, v.ID)
			}
		}

		if len(checkID) != 0 {
			err := r.db.Table(db_presensi+".manajemen_shift").
				Delete(&models.ManajemenShift{}, checkID).Error

			if err == nil {

				data := []models.AbsenEnroll{}

				errAE := r.db.Where("PNS_PNSNIP = ?", params.Nip).
					Where("tanggal IN ?", params.Tanggal).
					Where("uraian = ?", params.Uraian).
					Delete(&data).Error

				if errAE == nil {
					paramsUnDone.Pin = utils.IntToStr(params.IDPNS)
					paramsUnDone.Tanggal = params.Tanggal

					r.db.AllowGlobalUpdate = true

					errCSU := r.db.Table(db_presensi+".absen_adms_db").
						Where("pin = ?", paramsUnDone.Pin).
						Where("tanggal IN ?", paramsUnDone.Tanggal).
						Update("status", 0).Error

					if errCSU == nil {
						result.Status = true
						result.Message = "Action Successfully.."
						result.Data = tmp
					} else {
						result.Status = false
						result.Message = "Change status to undone failed"
					}

				} else {
					result.Status = false
					result.Message = "Absen enroll failed to delete"
				}
			} else {
				result.Status = false
				result.Message = "Data failed to insert"
			}
		} else {
			result.Status = false
			result.Message = "Data not found"
		}
	} else {
		result.Status = false
		result.Message = "Body must sent on json"
	}

	result.Response(c)
}
