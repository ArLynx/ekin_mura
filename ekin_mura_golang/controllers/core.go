package controllers

import (
	"log"
	"reflect"
	"strconv"

	"gorm.io/gorm"
)

type DB struct {
	db *gorm.DB
}

func SetDB(db *gorm.DB) *DB {
	return &DB{db: db}
}

func checkErr(err error) {
	if err != nil {
		log.Fatal(err)
	}
}

func StringConv(str interface{}, to string) (strConv interface{}) {
	if str == nil {
		return 0.0
	}

	paramType := reflect.TypeOf(str).Kind()

	if paramType != reflect.String {
		return str
	}

	switch to {
	case "int":
		strConv, err := strconv.Atoi(str.(string))
		checkErr(err)
		return strConv
	case "int64":
		strConv, err := strconv.ParseInt(str.(string), 10, 64)
		checkErr(err)
		return strConv
	case "float64":
		strConv, err := strconv.ParseFloat(str.(string), 64)
		checkErr(err)
		return strConv
	}
	return
}

func ArraySearch(needle interface{}, hystack interface{}) (index int) {
	index = -1

	switch reflect.TypeOf(hystack).Kind() {
	case reflect.Slice:
		s := reflect.ValueOf(hystack)

		for i := 0; i < s.Len(); i++ {
			if reflect.DeepEqual(needle, s.Index(i).Interface()) == true {
				index = i
				return
			}
		}
	}
	return
}
