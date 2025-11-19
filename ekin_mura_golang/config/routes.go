package config

import (
	"ekinerja/controllers"
	"ekinerja/middleware"

	"github.com/gin-contrib/cors"
	"github.com/gin-gonic/gin"
)

var Router *gin.Engine

// func Cors() gin.HandlerFunc {
// 	return func(c *gin.Context) {
// 		c.Writer.Header().Add("Access-Control-Allow-Origin", "*")
// 		c.Next()
// 	}
// }

func InitRoutes() {
	// gin.SetMode(gin.ReleaseMode)
	gin.SetMode(gin.DebugMode)

	Router = gin.New()

	call := controllers.SetDB(DB)

	// Router.Use(Cors())
	Router.Use(cors.Default())

	groupToken := Router.Group("api")
	group := Router.Group("api")

	groupToken.Use(middleware.CheckToken())

	// User Routes
	groupToken.GET("/user/kinerja", func(c *gin.Context) {
		call.GetUserKinerja(c)
	})
	groupToken.GET("/user/presensi", func(c *gin.Context) {
		call.GetUserPresensi(c)
	})
	groupToken.POST("/login", func(c *gin.Context) {
		call.Login(c)
	})

	// Setting Routes
	groupToken.GET("/setting", func(c *gin.Context) {
		call.GetSetting(c)
	})
	groupToken.PUT("/setting", func(c *gin.Context) {
		call.UpdateSetting(c)
	})

	// Information Routes
	groupToken.GET("/information", func(c *gin.Context) {
		call.GetInformation(c)
	})
	groupToken.PUT("/information", func(c *gin.Context) {
		call.UpdateInformation(c)
	})

	// Pegawai
	groupToken.GET("/get_pegawai_tpp", func(c *gin.Context) {
		call.GetPegawaiTPP(c)
	})
	groupToken.GET("/get_pegawai_non_tpp", func(c *gin.Context) {
		call.GetPegawaiNonTPP(c)
	})
	groupToken.GET("/get_gaji_pegawai/:unor", func(c *gin.Context) {
		call.GetGajiPegawai(c)
	})
	groupToken.GET("/get_detail_pns", func(c *gin.Context) {
		call.GetDetailPNS(c)
	})

	// Absen
	group.GET("/get_absen_pegawai", func(c *gin.Context) {
		call.GetAbsenPegawai(c)
	})
	group.GET("/get_indikator_kehadiran", func(c *gin.Context) {
		call.GetIndikatorKehadiran(c)
	})

	// Tunjangan
	groupToken.GET("/get_tpp_gabungan", func(c *gin.Context) {
		call.TppGabungan(c)
	})

	group.GET("/test", func(c *gin.Context) {
		call.Test(c)
	})

	groupToken.GET("/get_sopd_detail", func(c *gin.Context) {
		call.GetDetailSOPD(c)
	})
	groupToken.PUT("/update_sopd_detail", func(c *gin.Context) {
		call.UpdateDetailSOPD(c)
	})

	// Manajemen Shift
	groupToken.GET("/get_manajemen_shift", func(c *gin.Context) {
		call.GetManagementShift(c)
	})
	groupToken.POST("/save_manajemen_shift", func(c *gin.Context) {
		call.SaveManagementShift(c)
	})
	groupToken.DELETE("/delete_manajemen_shift", func(c *gin.Context) {
		call.DeleteManagementShift(c)
	})

	// No Token Needed Routes
	group.POST("/generate_token", func(c *gin.Context) {
		controllers.GenerateToken(c)
	})

	group.GET("/get_tipe_pegawai", func(c *gin.Context) {
		call.GetTipePegawai(c)
	})

	group.GET("/get_sopd", func(c *gin.Context) {
		call.GetAllSOPD(c)
	})

	group.GET("/get_pegawai_exchange", func(c *gin.Context) {
		call.GetPegawaiExchange(c)
	})

	group.POST("/create_pegawai_exchange", func(c *gin.Context) {
		call.CreatePegawaiExchange(c)
	})

	group.PUT("/update_pegawai_exchange/:nip", func(c *gin.Context) {
		call.UpdatePegawaiExchange(c)
	})

	group.GET("/:model", func(c *gin.Context) {
		call.GetData(c)
	})

	group.POST("/:model", func(c *gin.Context) {
		call.PostData(c)
	})

	group.PUT("/:model/:id", func(c *gin.Context) {
		call.PutData(c)
	})

	group.DELETE("/:model/:id", func(c *gin.Context) {
		call.DeleteData(c)
	})

	// group.GET("/read_adms_db", func(c *gin.Context) {
	// 	call.ReadADMSDB(c)
	// })
	// group.GET("/process_pending_adms_db", func(c *gin.Context) {
	// 	call.PrepareProcessADMSDB(c)
	// })

}
