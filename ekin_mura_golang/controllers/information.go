package controllers

import (
	"ekinerja/models"
	"net/http"
	"os"

	"github.com/gin-gonic/gin"
)

type InformationParams struct {
	Title     string `json:"title"`
	Content   string `json:"content"`
	UpdatedAt string `json:"updated_at"`
}

func (r *DB) GetInformation(c *gin.Context) {
	var information models.Information

	db_kinerja := os.Getenv("DB_KINERJA")

	err := r.db.Table(db_kinerja + ".information").First(&information)

	if err.Error == nil {
		c.JSON(http.StatusOK, gin.H{
			"status":  "success",
			"message": "Data information",
			"data":    information,
		})
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "information not found",
			"data":    make([]string, 0),
		})
	}
}

func (r *DB) UpdateInformation(c *gin.Context) {
	var informationParams InformationParams

	db_kinerja := os.Getenv("DB_KINERJA")

	errBindJson := c.BindJSON(&informationParams)

	if errBindJson == nil {
		err := r.db.Table(db_kinerja+".information").
			Where("id = ? ", 1).
			Updates(models.Information{Title: informationParams.Title, Content: informationParams.Content, UpdatedAt: informationParams.UpdatedAt}).Error

		if err == nil {
			c.JSON(http.StatusOK, gin.H{
				"status":  "success",
				"message": "Update information successfully",
				"data":    informationParams,
			})
		} else {
			c.JSON(http.StatusBadRequest, gin.H{
				"status":  "failed",
				"message": "Update information failed",
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
