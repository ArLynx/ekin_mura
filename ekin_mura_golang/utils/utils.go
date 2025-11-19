package utils

import (
	"net/http"
	"reflect"

	"github.com/gin-gonic/gin"
)

type Result struct {
	Status  bool        `json:"status"`
	Message string      `json:"message"`
	Data    interface{} `json:"data"`
}

type EndResult struct {
	Result
	Path interface{} `json:"path"`
}

func (result EndResult) Response(c *gin.Context) {

	if result.Data == nil {
		result.Data = make([]string, 0)
	}

	if reflect.ValueOf(result.Data).Kind() == reflect.Slice {
		if reflect.ValueOf(result.Data).IsNil() {
			result.Data = make([]string, 0)
		}
	}

	var response interface{}

	if result.Path == nil {
		var r Result
		r.Status = result.Status
		r.Message = result.Message
		r.Data = result.Data

		response = r
	} else {
		response = result
	}

	if result.Status == true {
		c.JSON(http.StatusOK, response)
	} else {
		c.JSON(http.StatusBadRequest, response)
	}
}
