package controllers

import (
	"ekinerja/models"
	"ekinerja/utils"
	"encoding/json"
	"fmt"
	"io"
	"net/http"
	"os"
	"strings"
	"time"
)

func (r *DB) ReadADMSDB() utils.EndResult {
	result := utils.EndResult{}
	listMasterDevice := []models.MasterDevice{}

	db_presensi := os.Getenv("DB_PRESENSI")

	errGetListMasterDevice := r.db.Order("kd_unor ASC").Find(&listMasterDevice).Error

	if errGetListMasterDevice == nil {

		for _, valMD := range listMasterDevice {
			var sn = strings.Trim(valMD.Sn, "\r\n")
			var tanggal = valMD.UpdatedAt.Format(utils.LayoutDate)
			var waktu = valMD.UpdatedAt.Format(utils.LayoutTime)
			// ip lokal
			// resp, err := http.Get("http://199.0.0.107/fingerspot/api/absen/devicescan?sn=" + sn + "&tanggal=" + tanggal + "&waktu=" + waktu)
			// resp, err := http.Get("http://199.0.0.177/fingerspot/api/absen/devicescan?sn=" + sn + "&tanggal=" + tanggal + "&waktu=" + waktu)
			// ip publik baru
			// resp, err := http.Get("http://ip/fingerspot/api/absen/devicescan?sn=" + sn + "&tanggal=" + tanggal + "&waktu=" + waktu)
			resp, err := http.Get("http://103.123.24.235/fingerspot/api/absen/devicescan?sn=" + sn + "&tanggal=" + tanggal + "&waktu=" + waktu)
			// ip publik lama
			// resp, err := http.Get("http://36.66.239.107/fingerspot/api/absen/devicescan?sn=" + sn + "&tanggal=" + tanggal + "&waktu=" + waktu)
			// resp, err := http.Get("https://ekinerja.kotawaringinbaratkab.go.id/api/tarik_absen?sn=" + sn + "&tanggal=" + tanggal + "&waktu=" + waktu)

			if err != nil {
				panic(err)
			}

			defer resp.Body.Close()

			readDataBody, errRead := io.ReadAll(resp.Body)

			dataTarikAbsen := models.TarikAbsen{}
			dataInsertAbsenADMSDB := []models.AbsenADMSDB{}

			json.Unmarshal([]byte(readDataBody), &dataTarikAbsen)

			if errRead == nil {

				var lastScanDate string

				for _, vTarikAbsen := range dataTarikAbsen.Data {
					prepareInsertAbsenADMSDB := models.AbsenADMSDB{}

					prepareInsertAbsenADMSDB.DeviceIP = vTarikAbsen.DeviceIP
					prepareInsertAbsenADMSDB.SN = vTarikAbsen.SN
					prepareInsertAbsenADMSDB.PIN = vTarikAbsen.PIN
					prepareInsertAbsenADMSDB.ScanDate = vTarikAbsen.ScanDate

					formatScanDate, _ := time.Parse("2006-01-02 15:04:05", vTarikAbsen.ScanDate)

					prepareInsertAbsenADMSDB.Tanggal = formatScanDate.Format("2006-01-02")
					prepareInsertAbsenADMSDB.Waktu = formatScanDate.Format("15:04:05")
					prepareInsertAbsenADMSDB.Status = 0

					dataInsertAbsenADMSDB = append(dataInsertAbsenADMSDB, prepareInsertAbsenADMSDB)

					lastScanDate = vTarikAbsen.ScanDate
				}

				if lastScanDate != "" {
					r.db.Model(&models.MasterDevice{}).Where("id = ?", valMD.ID).Update("updated_at", lastScanDate)
				}

				errSave := r.db.Table(db_presensi+".absen_adms_db").
					CreateInBatches(&dataInsertAbsenADMSDB, 500).Error

				if errSave == nil {
					result.Status = true
					result.Message = "Action Successfully.."
				} else {
					result.Status = false
					result.Message = "Failed save data to local"
				}
			} else {
				if len(readDataBody) == 0 {
					result.Status = false
					result.Message = "No data to fetch"
				} else {
					result.Status = false
					result.Message = "Failed get data"
				}
			}
		}
	} else {
		result.Status = false
		result.Message = "Failed get data master device"
	}

	result = r.prepareProcessADMSDB()
	return result
}

func (r *DB) prepareProcessADMSDB() utils.EndResult {
	result := utils.EndResult{}

	var data []map[string]interface{}
	db_presensi := os.Getenv("DB_PRESENSI")

	check_in_limit := os.Getenv("CHECK_IN_LIMIT")

	query1 := r.db.Select(`
				  MIN(absen_adms_db.id) AS id,
                  MIN(absen_adms_db.device_ip) AS device_ip,
                  MIN(absen_adms_db.sn) AS sn,
                  (absen_adms_db.pin) AS pin,
                  MIN(absen_adms_db.scan_date) AS scan_date,
                  (absen_adms_db.tanggal) AS tanggal,
                  MIN(absen_adms_db.waktu) AS waktu,
                  MIN(absen_adms_db.status) AS status,
                  pns.PNS_PNSNIP,
                  pns.PNS_PNSNAM,
                  pns.PNS_GLRDPN,
                  pns.PNS_GLRBLK,
                  pns.PNS_UNOR,
                  manajemen_shift.absen_masuk,
                  manajemen_shift.absen_pulang,
                  IF(
                        manajemen_shift.tanggal != '',
                        IF(
                              manajemen_shift.absen_masuk > manajemen_shift.absen_pulang,
                              IF(
                                    absen_adms_db.waktu > 
                                    (SELECT 
                                          SUBTIME(
                                          manajemen_shift.absen_masuk,
                                          "04:00"
                                          )) && waktu < 
                                    (SELECT 
                                          ADDTIME(
                                          manajemen_shift.absen_masuk,
                                          "04:00"
                                          )),
                                    absen_adms_db.tanggal,
                                    IF(
                                          absen_adms_db.waktu > 
                                          (SELECT 
                                          SUBTIME(
                                                manajemen_shift.absen_pulang,
                                                "04:00"
                                          )) && waktu < 
                                          (SELECT 
                                          ADDTIME(
                                                manajemen_shift.absen_pulang,
                                                "04:00"
                                          )),
                                          (SELECT 
                                          DATE_SUB(
                                                absen_adms_db.tanggal,
                                                INTERVAL 1 DAY
                                          )),
                                          absen_adms_db.tanggal
                                    )
                              ),
                              absen_adms_db.tanggal
                        ),
                        absen_adms_db.tanggal
                  ) AS tanggal_shift 
	`).
		Table(db_presensi + ".absen_adms_db").
		Joins("LEFT JOIN pns ON " + db_presensi + ".pns.id = absen_adms_db.pin").
		Joins("LEFT JOIN " + db_presensi + ".manajemen_shift ON manajemen_shift.unor = pns.PNS_UNOR AND manajemen_shift.nip = pns.PNS_PNSNIP AND manajemen_shift.tanggal = absen_adms_db.tanggal").
		Where("absen_adms_db.status = 0").
		Group("pin, tanggal")

	query2 := r.db.Select(`
				  MAX(absen_adms_db.id) AS id,
                  MAX(absen_adms_db.device_ip) AS device_ip,
                  MAX(absen_adms_db.sn) AS sn,
                  (absen_adms_db.pin) AS pin,
                  MAX(absen_adms_db.scan_date) AS scan_date,
                  (absen_adms_db.tanggal) AS tanggal,
                  MAX(absen_adms_db.waktu) AS waktu,
                  MAX(absen_adms_db.status) AS status,
                  pns.PNS_PNSNIP,
                  pns.PNS_PNSNAM,
                  pns.PNS_GLRDPN,
                  pns.PNS_GLRBLK,
                  pns.PNS_UNOR,
                  manajemen_shift.absen_masuk,
                  manajemen_shift.absen_pulang,
                  IF(
                        manajemen_shift.tanggal != NULL,
                        IF(
                              manajemen_shift.absen_masuk > manajemen_shift.absen_pulang,
                              IF(
                                    absen_adms_db.waktu > 
                                    (SELECT 
                                          SUBTIME(
                                          manajemen_shift.absen_masuk,
                                          "04:00"
                                          )) && waktu < 
                                    (SELECT 
                                          ADDTIME(
                                          manajemen_shift.absen_masuk,
                                          "04:00"
                                          )),
                                    absen_adms_db.tanggal,
                                    IF(
                                          absen_adms_db.waktu > 
                                          (SELECT 
                                          SUBTIME(
                                                manajemen_shift.absen_pulang,
                                                "04:00"
                                          )) && waktu < 
                                          (SELECT 
                                          ADDTIME(
                                                manajemen_shift.absen_pulang,
                                                "04:00"
                                          )),
                                          (SELECT 
                                          DATE_SUB(
                                                absen_adms_db.tanggal,
                                                INTERVAL 1 DAY
                                          )),
                                          absen_adms_db.tanggal
                                    )
                              ),
                              absen_adms_db.tanggal
                        ),
                        absen_adms_db.tanggal
                  ) AS tanggal_shift 
	`).
		Table(db_presensi + ".absen_adms_db").
		Joins("LEFT JOIN pns ON " + db_presensi + ".pns.id = absen_adms_db.pin").
		Joins("LEFT JOIN " + db_presensi + ".manajemen_shift ON manajemen_shift.unor = pns.PNS_UNOR AND manajemen_shift.nip = pns.PNS_PNSNIP AND manajemen_shift.tanggal = absen_adms_db.tanggal").
		Where("absen_adms_db.status = 0").
		Group("pin, tanggal")

	errPrepare := r.db.Raw(`SELECT 
                  id AS id_absen_adms_db,
			pin AS code,
			PNS_PNSNIP,
			PNS_PNSNAM,
			PNS_GLRDPN,
			PNS_GLRBLK,
			PNS_UNOR,
			device_ip AS ip,
			scan_date AS time,
			tanggal_shift AS tanggal,
			waktu,
			jenis,
			0 AS keterangan,
			'up_face' AS uraian 
	  FROM (SELECT 
            *,
            IF(
                  tanggal_shift != '',
                  IF(
                        waktu > 
                        (SELECT 
                              SUBTIME(absen_masuk, "04:00")) && waktu < 
                        (SELECT 
                              ADDTIME(absen_masuk, "04:00")),
                        "in",
                        IF(
                              waktu > 
                              (SELECT 
                                    SUBTIME(absen_pulang, "04:00")) && waktu < 
                              (SELECT 
                                    ADDTIME(absen_pulang, "04:00")),
                              "out",
                              IF(
										waktu > 
										(SELECT 
											SUBTIME("`+check_in_limit+`", "04:00")) && waktu < 
										(SELECT 
											ADDTIME("`+check_in_limit+`", "04:00")),
										"in",
										"out"
								)
                        )
                  ),
                  IF(
                        waktu > 
                        (SELECT 
                              SUBTIME("`+check_in_limit+`", "04:00")) && waktu < 
                        (SELECT 
                              ADDTIME("`+check_in_limit+`", "04:00")),
                        "in",
                        "out"
                  )
            ) AS jenis FROM (? UNION ?) AS ccc 
      ORDER BY pin, tanggal) AS zzz 
	  WHERE PNS_PNSNIP IS NOT NULL
	  ORDER BY pin, tanggal, jenis`, query1, query2).
		Find(&data).Error

	if errPrepare == nil {
		// Convert map to json string
		jsonStr, err := json.Marshal(data)
		if err != nil {
			fmt.Println(err)
		}

		// Convert json string to struct
		dataInsertBatch := []models.AbsenEnroll{}
		if err := json.Unmarshal(jsonStr, &dataInsertBatch); err != nil {
			fmt.Println(err)
		}

		errSaveEnroll := r.db.Table(db_presensi+".absen_enroll").
			CreateInBatches(&dataInsertBatch, 500).Error

		if errSaveEnroll == nil {
			idList := r.IDList(data)

			errChangeStatus := r.db.Model(models.AbsenADMSDB{}).
				Where("status = ?", 0).
				Where("id IN (?)", idList).
				Updates(models.AbsenADMSDB{Status: 1}).Error

			if errChangeStatus == nil {
				result.Status = true
				result.Message = "Action Successfully.."
			} else {
				result.Status = false
				result.Message = "Change status absen_adms_db to done failed"
			}
		} else {
			result.Status = false
			result.Message = "Save to absen enroll failed"
		}
	} else {
		result.Status = false
		result.Message = "Failed to prepare data to absen enroll"
	}

	return result
}

func (r *DB) IDList(AbsenEnrollPrepare []map[string]interface{}) []int64 {
	var list []int64
	for _, val := range AbsenEnrollPrepare {
		list = append(list, val["id_absen_adms_db"].(int64))
	}
	return list
}
