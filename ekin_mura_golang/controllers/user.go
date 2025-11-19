package controllers

import (
	"crypto/sha1"
	"encoding/hex"

	"ekinerja/models"
	"os"

	"net/http"

	"github.com/gin-gonic/gin"
)

type LoginParams struct {
	Username   string `json:"username"`
	Password   string `json:"password"`
	AksesLogin string `json:"akses_login"`
}

func (r *DB) GetUserKinerja(c *gin.Context) {
	var user []models.User

	db_kinerja := os.Getenv("DB_KINERJA")

	err := r.db.Table(db_kinerja + ".users").
		Find(&user).Error

	if err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "List user",
			"data":    user,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "User not found",
			"data":    make([]string, 0),
		})
	}
}
func (r *DB) GetUserPresensi(c *gin.Context) {
	var user []models.User

	db_presensi := os.Getenv("DB_PRESENSI")

	err := r.db.Table(db_presensi + ".users").
		Find(&user).Error

	if err == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "List user",
			"data":    user,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "User not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) Login(c *gin.Context) {
	var user models.User
	var loginParams LoginParams

	db_kinerja := os.Getenv("DB_KINERJA")
	db_presensi := os.Getenv("DB_PRESENSI")

	errBindJson := c.BindJSON(&loginParams)

	if errBindJson == nil {
		var err error
		if loginParams.AksesLogin == "1" { //PNS
			err = r.db.Table(db_kinerja+".users").
				Select("users.*, IFNULL(users_groups.group_id, 3) AS group_id, IF(users_groups.group_id = 1 OR users_groups.group_id = 5, '8804000000', pns.PNS_UNOR) AS unor").
				Joins("left join "+db_presensi+".pns on pns.PNS_PNSNIP = users.nip").
				Joins("left join "+db_kinerja+".users_groups on users.id = users_groups.user_id").
				Where("username = ?", loginParams.Username).
				Scan(&user).Error
		} else { //KEPEGAWAIAN
			err = r.db.Table(db_presensi+".users").
				Select("users.*, users_groups.group_id, unit_profiles.unor").
				Joins("left join "+db_presensi+".users_groups on users.id = users_groups.user_id").
				Joins("left join "+db_presensi+".unit_profiles on unit_profiles.unit_id = users.unit").
				Where("username = ?", loginParams.Username).
				Scan(&user).Error
		}
		if err == nil && user.ID != 0 {
			var salt = user.Password[0:10]
			h := sha1.New()
			h.Write([]byte(salt + loginParams.Password))
			sha := h.Sum(nil)
			shaStr := hex.EncodeToString(sha)

			if (salt + shaStr[:len(shaStr)-10]) == user.Password {
				c.JSON(http.StatusOK, gin.H{
					"status":  "success",
					"message": "Data user",
					"data":    user,
				})
			} else {
				c.JSON(http.StatusUnauthorized, gin.H{
					"status":  "failed",
					"message": "Username and/or password not correct",
					"data":    make([]string, 0),
				})
			}
		} else {
			c.JSON(http.StatusBadRequest, gin.H{
				"status":  "failed",
				"message": "User not found",
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
