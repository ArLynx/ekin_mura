package controllers

import (
	"net/http"
	"os"

	"github.com/dgrijalva/jwt-go"
	"github.com/gin-gonic/gin"
)

type GenerateTokenParams struct {
	Username string `json:"username"`
	Password string `json:"password"`
}

func GenerateToken(c *gin.Context) {
	mySigningKey := []byte(os.Getenv("JWT_SECRET"))

	var generateTokenParams GenerateTokenParams

	errBindJson := c.BindJSON(&generateTokenParams)

	if errBindJson == nil {
		if generateTokenParams.Username == os.Getenv("USER_CREDENTIAL") && generateTokenParams.Password == os.Getenv("PASS_CREDENTIAL") {
			// Create the Claims
			claims := &jwt.StandardClaims{
				Issuer: "Awan Tengah Studio",
			}

			token := jwt.NewWithClaims(jwt.SigningMethodHS256, claims)
			ss, err := token.SignedString(mySigningKey)
			if err == nil {
				c.JSON(http.StatusOK, gin.H{
					"status":  "success",
					"message": "Generate token successfully",
					"data": gin.H{
						"token": ss,
					},
				})
			} else {
				c.JSON(http.StatusBadRequest, gin.H{
					"status":  "failed",
					"message": err,
					"data":    make([]string, 0),
				})
			}
		} else {
			c.JSON(http.StatusBadRequest, gin.H{
				"status":  "failed",
				"message": "You don't have permission to access",
				"data":    make([]string, 0),
			})
		}
	} else {
		c.JSON(http.StatusBadRequest, gin.H{
			"status":  "failed",
			"message": "Body must sent on json",
			"data":    make([]string, 0),
		})
	}

}
