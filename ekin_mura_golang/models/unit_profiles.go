package models

type UnitProfiles struct {
	UnitId       string `gorm:"column:unit_id;primary_key" json:"unit_id"`
	NamaUnit     string `gorm:"column:nama_unit" json:"nama_unit"`
	Unor         string `gorm:"column:unor" json:"unor"`
	Alamat       string `gorm:"column:alamat" json:"alamat"`
	Telp         string `gorm:"column:telp" json:"telp"`
	Email        string `gorm:"column:email" json:"email"`
	KdWilayah    string `gorm:"column:kd_wilayah" json:"kd_wilayah"`
}
