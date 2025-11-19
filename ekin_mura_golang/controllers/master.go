package controllers

import (
	"ekinerja/models"
	"fmt"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
)

func (r *DB) GetTipePegawai(c *gin.Context) {
	is_tpp := c.Query("is_tpp")

	var tipe_pegawai []models.TipePegawai

	db_presensi := os.Getenv("DB_PRESENSI")

	err := r.db.Table(db_presensi + ".tipe_pegawai")

	if is_tpp != "" && is_tpp == "true" {
		err.Where("id = ? OR id = ?", 0, 5)
	}

	if is_tpp != "" && is_tpp == "false" {
		err.Where("id != ? AND id != ?", 0, 5)
	}

	err.Order("no ASC")

	err.Scan(&tipe_pegawai)

	if err.Error == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data tipe pegawai",
			"data":    tipe_pegawai,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "tipe pegawai not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) GetAllSOPD(c *gin.Context) {
	unor := c.Query("unor")
	is_dinas := c.Query("is_dinas")

	var data_sopd []models.Unor

	db_presensi := os.Getenv("DB_PRESENSI")

	fmt.Println("unor", unor)
	fmt.Println("is_dinas", is_dinas)
	fmt.Println(is_dinas == "true")

	err := r.db.Table(db_presensi + ".unor")

	if unor != "" {
		err.Where("KD_UNOR = ?", unor)
	} else if is_dinas == "true" {
		err.Where("KD_UNOR LIKE ? OR KD_UNOR = ?", "88%", "1111111111").
			Where("NM_UNOR NOT LIKE ?", "%PUSKESMAS% AND NM_UNOR NOT LIKE %UKK% AND NM_UNOR NOT LIKE %RSUD%")
	}

	// err.Where("Status_UNOR=?","aktif")
	err.Order("NM_UNOR ASC")
	// err.Order("NM_UNOR DSC")
	err.Scan(&data_sopd)

	if err.Error == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data SOPD",
			"data":    data_sopd,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Data SOPD not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) GetDetailSOPD(c *gin.Context) {
	unor := c.Query("unor")

	var detail models.UnitProfiles

	db_presensi := os.Getenv("DB_PRESENSI")

	err := r.db.Table(db_presensi+".unit_profiles").Where("unor = ?", unor).First(&detail)

	if err.Error == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Detail SOPD",
			"data":    detail,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Detail SOPD not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) UpdateDetailSOPD(c *gin.Context) {
	var params models.UnitProfiles

	db_presensi := os.Getenv("DB_PRESENSI")

	errBindJson := c.BindJSON(&params)

	if errBindJson == nil {
		err := r.db.Table(db_presensi+".unit_profiles").
			Where("unor = ?", params.Unor).
			Updates(models.UnitProfiles{Alamat: params.Alamat, Telp: params.Telp, KdWilayah: params.KdWilayah}).Error

		if err == nil {
			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Update data SOPD successfully",
				"data":    params,
			})
		} else {
			c.JSON(http.StatusBadRequest, gin.H{
				"status":  "failed",
				"message": "Update data SOPD failed",
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
