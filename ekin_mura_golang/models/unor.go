package models

type Unor struct {
	KD_UNOR string `gorm:"column:KD_UNOR;primary_key" json:"KD_UNOR"`
	NIP string `gorm:"column:KD_UNOR;primary_key" json:"NIP"`
	NM_UNOR string `gorm:"column:NM_UNOR" json:"NM_UNOR"`
	Status_UNOR string `gorm:"column:Status_UNOR" json:"Status_UNOR"`
}
