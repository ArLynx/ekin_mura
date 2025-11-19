package controllers

import (
	"ekinerja/models"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
)

type SettingParams struct {
	Max_entry_kinerja      string `json:"max_entry_kinerja"`
	Max_approve_kinerja    string `json:"max_approve_kinerja"`
	Max_date_edit_presence string `json:"max_date_edit_presence"`
}

func (r *DB) GetSetting(c *gin.Context) {
	var settings []models.Setting
	var setting models.Setting

	nama := c.Query("nama")
	tahun := c.Query("tahun")

	db_presensi := os.Getenv("DB_PRESENSI")

	err := r.db.Table(db_presensi + ".setting")

	if nama == "" && tahun == "" {
		err.Where("id IN ?", []string{"4", "5", "13"})
		err.Scan(&settings)
	} else {
		err.Where(&models.Setting{Nama: nama, Tahun: tahun})
		err.Scan(&setting)
	}

	if err.Error == nil {
		if nama == "" && tahun == "" {
			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Data setting",
				"data":    settings,
			})
		} else {
			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Data setting",
				"data":    setting,
			})
		}
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Setting not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) UpdateSetting(c *gin.Context) {
	var settingParams SettingParams

	db_presensi := os.Getenv("DB_PRESENSI")

	errBindJson := c.BindJSON(&settingParams)

	if errBindJson == nil {
		err1 := r.db.Table(db_presensi+".setting").
			Where("nama = ?", "max_entry_kinerja").
			Update("bulan", settingParams.Max_entry_kinerja).Error

		err2 := r.db.Table(db_presensi+".setting").
			Where("nama = ?", "max_approve_kinerja").
			Update("bulan", settingParams.Max_approve_kinerja).Error

		err3 := r.db.Table(db_presensi+".setting").
			Where("nama = ?", "max_date_edit_presence").
			Update("bulan", settingParams.Max_date_edit_presence).Error

		if err1 == nil && err2 == nil && err3 == nil {
			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Update setting successfully",
				"data":    settingParams,
			})
		} else {
			c.JSON(http.StatusBadRequest, gin.H{
				"status":  "failed",
				"message": "Update setting failed",
				"data":    make([]string, 0),
			})
		}
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Body must sent on json",
			"data":    make([]string, 0),
		})
	}
}
