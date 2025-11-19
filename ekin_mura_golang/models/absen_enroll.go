package models

import (
	"time"

	"gorm.io/datatypes"
)

type AbsenEnroll struct {
	ID          int            `gorm:"column:id;primary_key" json:"id"`         //
	Code        string         `gorm:"column:code" json:"code"`                 //
	PNSPNSNIP   string         `gorm:"column:PNS_PNSNIP" json:"PNS_PNSNIP"`     //
	PNSPNSNAM   string         `gorm:"column:PNS_PNSNAM" json:"PNS_PNSNAM"`     //
	PNSGLRDPN   string         `gorm:"column:PNS_GLRDPN" json:"PNS_GLRDPN"`     //
	PNSGLRBLK   string         `gorm:"column:PNS_GLRBLK" json:"PNS_GLRBLK"`     //
	PNSUNOR     string         `gorm:"column:PNS_UNOR" json:"PNS_UNOR"`         //
	IP          string         `gorm:"column:ip" json:"ip"`                     //
	Time        time.Time      `gorm:"column:time" json:"time"`                 //
	Tanggal     datatypes.Date `gorm:"column:tanggal" json:"tanggal"`           //
	Waktu       datatypes.Time `gorm:"column:waktu" json:"waktu"`               //
	Jenis       string         `gorm:"column:jenis" json:"jenis"`               //
	Keterangan  int            `gorm:"column:keterangan" json:"keterangan"`     //
	Uraian      string         `gorm:"column:uraian" json:"uraian"`             //
	UserCreated string         `gorm:"column:user_created" json:"user_created"` //
}

// TableName sets the insert table name for this struct type
func (a *AbsenEnroll) TableName() string {
	return "absen_enroll"
}
