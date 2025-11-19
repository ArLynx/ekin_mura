package models

type IDManajemenShift struct {
	ID int `gorm:"column:id;primary_key" json:"id"` //
}

type DeleteManajemenShift struct {
	IDPNS   int      `gorm:"column:id_pns;primary_key" json:"id_pns"` //
	Nip     string   `gorm:"column:nip" json:"nip"`                   //
	Tanggal []string `gorm:"column:tanggal" json:"tanggal"`           //
	Uraian  string   `gorm:"column:uraian" json:"uraian"`             //
}

type ManajemenShiftParams struct {
	Unor          string `json:"unor"`
	IDTipePegawai string `json:"id_tipe_pegawai"`
	Month         string `json:"month"`
	Year          string `json:"year"`
}

type ManajemenShift struct {
	IDManajemenShift
	IDTipePegawai string `gorm:"column:id_tipe_pegawai" json:"id_tipe_pegawai"` //
	Unor          string `gorm:"column:unor" json:"unor"`                       //
	Nip           string `gorm:"column:nip" json:"nip"`                         //
	Tanggal       string `gorm:"column:tanggal" json:"tanggal"`                 //
	AbsenMasuk    string `gorm:"column:absen_masuk" json:"absen_masuk"`         //
	AbsenPulang   string `gorm:"column:absen_pulang" json:"absen_pulang"`       //
	CreatedAt     string `gorm:"column:created_at" json:"created_at"`           //
}

// TableName sets the insert table name for this struct type
func (m *ManajemenShift) TableName() string {
	return "manajemen_shift"
}
