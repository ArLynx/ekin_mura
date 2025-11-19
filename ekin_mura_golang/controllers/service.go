package controllers

import (
	"net/http"
	"strconv"

	"github.com/gin-gonic/gin"
)

func (r *DB) GetData(c *gin.Context) {
	params := c.Params
	query := c.Request.URL.Query()

	// db, _ := config.Connect()

	// Check if the table exists
	if !r.db.Migrator().HasTable(params.ByName("model")) {
		c.JSON(http.StatusNotFound, gin.H{"error": "Table not found: " + params.ByName("model")})
		return
	}

	// Convert the limit parameter to an integer
	limit, err := strconv.Atoi(query.Get("limit"))
	if err != nil {
		// Handle the error, for example, set a default limit
		limit = 10
	}

	// Fetch data from the specified table with the specified limit
	var data []map[string]interface{}
	result := r.db.Table(params.ByName("model")).Limit(limit).Find(&data)

	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	c.JSON(http.StatusOK, data)
}

func (r *DB) PostData(c *gin.Context) {
	params := c.Params
	// query := c.Request.URL.Query()
	// Create a map to hold the request data
	var data map[string]interface{}

	// Bind the JSON or form data from the request body to the map
	if err := c.ShouldBindJSON(&data); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid input data"})
		return
	}

	// Validate the input data if necessary

	// Get the database instance
	// db := config.Database()

	// Insert the data into the specified table
	result := r.db.Table(params.ByName("model")).Create(&data)

	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	c.JSON(http.StatusOK, data)
}

func (r *DB) PutData(c *gin.Context) {
	params := c.Params
	// Create a map to hold the request data
	var data map[string]interface{}

	// Bind the JSON or form data from the request body to the map
	if err := c.ShouldBindJSON(&data); err != nil {
		c.JSON(http.StatusBadRequest, gin.H{"error": "Invalid input data"})
		return
	}

	// Validate the input data if necessary

	// Get the database instance
	// db := config.Database()

	// Check if the table exists
	if !r.db.Migrator().HasTable(params.ByName("model")) {
		c.JSON(http.StatusNotFound, gin.H{"error": "Table not found: " + params.ByName("model")})
		return
	}

	// Update the data in the specified table
	result := r.db.Table(params.ByName("model")).Where("id = ?", params.ByName("id")).Updates(&data)

	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	c.JSON(http.StatusOK, data)
}

func (r *DB) DeleteData(c *gin.Context) {
	params := c.Params

	// Get the database instance
	// db := config.Database()

	var data map[string]interface{}
	// Check if the table exists
	if !r.db.Migrator().HasTable(params.ByName("model")) {
		c.JSON(http.StatusNotFound, gin.H{"error": "Table not found: " + params.ByName("model")})
		return
	}

	selectResult := r.db.Table(params.ByName("model")).Where("id = ?", params.ByName("id")).Find(&data)
	if selectResult.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": selectResult.Error.Error()})
		return
	}

	// Delete the data from the specified table based on the "id" parameter
	result := r.db.Table(params.ByName("model")).Where("id = ?", params.ByName("id")).Delete(&data)

	if result.Error != nil {
		c.JSON(http.StatusInternalServerError, gin.H{"error": result.Error.Error()})
		return
	}

	// Check if any rows were affected
	if result.RowsAffected == 0 {
		c.JSON(http.StatusNotFound, gin.H{"error": "Record not found"})
		return
	}

	c.JSON(http.StatusOK, data)
}
