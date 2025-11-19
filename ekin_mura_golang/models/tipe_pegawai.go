package models

type TipePegawai struct {
	ID         int    `gorm:"column:id;primary_key" json:"id"`
	Type       string `gorm:"column:type" json:"type"`
}
