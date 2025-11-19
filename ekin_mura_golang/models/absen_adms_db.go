package models

type AbsenADMSDBParams struct {
	ID    int    `json:"id"`
	Month string `json:"month"`
	Year  string `json:"year"`
}

type ChangeStatusAbsenADMSDB struct {
	Pin     string   `gorm:"column:pin" json:"pin"`
	Tanggal []string `gorm:"column:tanggal" json:"tanggal"`
}

type DataTarikAbsen struct {
	DeviceIP   string `json:"device_ip"`
	InOutMode  string `json:"inoutmode"`
	PIN        string `json:"pin"`
	ScanDate   string `json:"scan_date"`
	SN         string `json:"sn"`
	VerifyMode string `json:"verifymode"`
}

type TarikAbsen struct {
	Data []DataTarikAbsen `json:"data"`
}

type AbsenADMSDB struct {
	ID       int    `gorm:"column:id;primary_key" json:"id"`   //
	DeviceIP string `gorm:"column:device_ip" json:"device_ip"` //
	SN       string `gorm:"column:sn" json:"sn"`               //
	PIN      string `gorm:"column:pin" json:"pin"`             //
	ScanDate string `gorm:"column:scan_date" json:"scan_date"` //
	Tanggal  string `gorm:"column:tanggal" json:"tanggal"`     //
	Waktu    string `gorm:"column:waktu" json:"waktu"`         //
	Status   int    `gorm:"column:status" json:"status"`       //
}

// TableName sets the insert table name for this struct type
func (a *AbsenADMSDB) TableName() string {
	return "absen_adms_db"
}
