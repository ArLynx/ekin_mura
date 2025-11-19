package models

import "time"

type TPPGabungan struct {
	No                             int       `json:"no"`
	CODE_GOL                       int       `json:"CODE_GOL"`
	NM_GOL                         string    `json:"NM_GOL"`
	PNS_NAMA                       string    `json:"PNS_NAMA"`
	PNS_NO_REK                     string    `json:"PNS_NO_REK"`
	PNS_PNSNIP                     string    `json:"PNS_PNSNIP"`
	PNS_UNOR                       string    `json:"PNS_UNOR"`
	Bank                           string    `json:"bank"`
	Besaran_hukuman_tks            float64   `json:"besaran_hukuman_tks"`
	Capaian_kerja                  float64   `json:"capaian_kerja"`
	Cost_bpjs                      float64   `json:"cost_bpjs"`
	Eselon_jabatan_pns             string    `json:"eselon_jabatan_pns"`
	Faktor_penyeimbang             float64   `json:"faktor_penyeimbang"`
	Gaji_kotor                     float64   `json:"gaji_kotor"`
	Id_master_jabatan_pns          int       `json:"id_master_jabatan_pns"`
	Id_master_kelangkaan_profesi   int       `json:"id_master_kelangkaan_profesi"`
	Id_master_kelas_jabatan        int       `json:"id_master_kelas_jabatan"`
	Ikfd                           float64   `json:"ikfd"`
	Ikk                            float64   `json:"ikk"`
	Ippd                           float64   `json:"ippd"`
	Iw_sudah_bayar                 float64   `json:"iw_sudah_bayar"`
	Kelas_jabatan                  int       `json:"kelas_jabatan"`
	Ket_pns                        string    `json:"ket_pns"`
	Keterangan_rapel               string    `json:"keterangan_rapel"`
	Limit_aktivitas_kerja          float64   `json:"limit_aktivitas_kerja"`
	Mulai_sanksi                   time.Time `json:"mulai_sanksi"`
	Nama_jabatan                   string    `json:"nama_jabatan"`
	Nama_jabatan_plt               string    `json:"nama_jabatan_plt"`
	Tunjangan_plt                  float64   `json:"tunjangan_plt"`
	Nominal_kondisi_kerja          float64   `json:"nominal_kondisi_kerja"`
	Nominal_rapel                  float64   `json:"nominal_rapel"`
	Nominal_sanksi                 float64   `json:"nominal_sanksi"`
	Pangkat                        string    `json:"pangkat"`
	Pengurangan                    float64   `json:"pengurangan"`
	Pengurangan_cpns               float64   `json:"pengurangan_cpns"`
	Persen_pajak                   float64   `json:"persen_pajak"`
	Persentase_indikator_kehadiran float64   `json:"persentase_indikator_kehadiran"`
	Pph                            float64   `json:"pph"`
	Sampai_sanksi                  time.Time `json:"sampai_sanksi"`
	Sopd_plt                       string    `json:"sopd_plt"`
	Total_capaian_waktu_kerja      float64   `json:"total_capaian_waktu_kerja"`
	Total_norma_waktu              float64   `json:"total_norma_waktu"`
	Tpp_basic                      float64   `json:"tpp_basic"`
	Tpp_basic_plt                  float64   `json:"tpp_basic_plt"`
	Tpp_beban_kerja                float64   `json:"tpp_beban_kerja"`
	Tpp_prestasi_kerja             float64   `json:"tpp_prestasi_kerja"`
	Tpp_gabungan                   float64   `json:"tpp_gabungan"`
	Tpp_gabungan_setelah_pph       float64   `json:"tpp_gabungan_setelah_pph"`
	Tpp_kelangkaan_profesi         float64   `json:"tpp_kelangkaan_profesi"`
	Tpp_kondisi_kerja              float64   `json:"tpp_kondisi_kerja"`
	Tpp_tempat_bertugas            float64   `json:"tpp_tempat_bertugas"`
	Unit_organisasi                string    `json:"unit_organisasi"`
	ID_TIPE_PEGAWAI                int       `json:"id_tipe_pegawai"`
}

type MasterPenguranganTPP struct {
	ID          int
	Pengurangan float64
	Tahun       int
}
