package models

type KegiatanTotalWaktu struct {
	ID                        int    `gorm:"column:id;primary_key" json:"id"`
	Pns_pnsnip                string `gorm:"column:pns_pnsnip" json:"pns_pnsnip"`
	Month                     string `gorm:"column:month" json:"month"`
	Year                      string `gorm:"column:year" json:"year"`
	Status                    string `gorm:"column:status" json:"status"`
	Total_norma_waktu         string `gorm:"column:total_norma_waktu" json:"total_norma_waktu"`
	Total_capaian_waktu_kerja string `gorm:"column:total_capaian_waktu_kerja" json:"total_capaian_waktu_kerja"`
	Jam_kerja                 string `gorm:"column:jam_kerja" json:"jam_kerja"`
	Created_at                string `gorm:"column:created_at" json:"created_at"`
}
