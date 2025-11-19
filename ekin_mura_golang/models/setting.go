package models

type Setting struct {
	ID         int    `gorm:"column:id;primary_key" json:"id"`
	Nama       string `gorm:"column:nama" json:"nama"`
	Bulan      string `gorm:"column:bulan" json:"bulan"`
	Tahun      string `gorm:"column:tahun" json:"tahun"`
	MenitAwal  string `gorm:"column:menit_awal" json:"menit_awal"`
	MenitAkhir string `gorm:"column:menit_akhir" json:"menit_akhir"`
}
