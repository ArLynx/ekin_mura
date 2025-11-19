package models

type Information struct {
	Title string `gorm:"column:title" json:"title"`
	Content string `gorm:"column:content" json:"content"`
	CreatedAt   string `gorm:"column:created_at" json:"created_at"`
	UpdatedAt   string `gorm:"column:updated_at" json:"updated_at"`
}