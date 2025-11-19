package cron

import (
	"ekinerja/controllers"
	"fmt"
	"os"
	"sync"

	"github.com/robfig/cron/v3"
	"gorm.io/gorm"
)

func InitCron(db *gorm.DB) {
	wg := &sync.WaitGroup{}
	wg.Add(1)

	cron := cron.New()

	cron.AddFunc(os.Getenv("TIME_CRON_ADMS_DB"), func() {
		fmt.Println("Cron Running..")
		call := controllers.SetDB(db)
		call.ReadADMSDB()
		fmt.Println("Cron End..")
	})

	cron.Start()

	wg.Wait()
}
