package config

import (
	"os"

	gormMysql "gorm.io/driver/mysql"
	"gorm.io/gorm"
)

var DB *gorm.DB

func SetDatabase(db *gorm.DB) {
	DB = db
}

func Connect() (*gorm.DB, error) {
	dsn := os.Getenv("DSN")

	return gorm.Open(gormMysql.Open(dsn), &gorm.Config{})
}
