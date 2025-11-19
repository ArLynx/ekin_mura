package main

import (
	"ekinerja/config"
	"ekinerja/cron"
	"os"

	"github.com/joho/godotenv"
)

func main() {
	err := godotenv.Load()
	if err != nil {
		panic("Error loading .env file")
	}

	db, err := config.Connect()
	if err != nil {
		panic(err)
	}

	config.SetDatabase(db)
	config.InitRoutes()

	go cron.InitCron(db)

	config.Router.Run(":" + os.Getenv("GO_PORT"))
}
