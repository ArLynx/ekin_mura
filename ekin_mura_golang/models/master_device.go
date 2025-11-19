package models

import (
	"time"

	"gorm.io/gorm"
)

type MasterDevice struct {
	ID        int             `gorm:"column:id;primary_key" json:"id"`     //
	KdUnor    string          `gorm:"column:kd_unor" json:"kd_unor"`       //
	Sn        string          `gorm:"column:sn" json:"sn"`                 //
	Instansi  string          `gorm:"column:instansi" json:"instansi"`     //
	CreatedAt *time.Time      `gorm:"column:created_at" json:"created_at"` //
	UpdatedAt *time.Time      `gorm:"column:updated_at" json:"updated_at"` //
	DeletedAt *gorm.DeletedAt `gorm:"column:deleted_at" json:"deleted_at"` //
}

// TableName sets the insert table name for this struct type
func (m *MasterDevice) TableName() string {
	return "master_device"
}
