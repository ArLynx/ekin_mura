package utils

import (
	"strconv"
)

const LayoutDateTime = "2006-01-02 15:04:05"
const LayoutDate = "2006-01-02"
const LayoutTime = "15:04:05"

func StrToInt(val string) int {
	i, _ := strconv.Atoi(val)
	return i
}

func IntToStr(val int) string {
	i := strconv.Itoa(val)
	return i
}
