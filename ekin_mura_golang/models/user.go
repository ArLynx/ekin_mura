package models

type User struct {
	ID                    int    `gorm:"column:id;primary_key" json:"id"`
	IPAddress             []byte `gorm:"column:ip_address" json:"ip_address"`
	Username              string `gorm:"column:username" json:"username"`
	Password              string `gorm:"column:password" json:"password"`
	Salt                  string `gorm:"column:salt" json:"salt"`
	Email                 string `gorm:"column:email" json:"email"`
	ActivationCode        string `gorm:"column:activation_code" json:"activation_code"`
	ForgottenPasswordCode string `gorm:"column:forgotten_password_code" json:"forgotten_password_code"`
	ForgottenPasswordTime int    `gorm:"column:forgotten_password_time" json:"forgotten_password_time"`
	RememberCode          string `gorm:"column:remember_code" json:"remember_code"`
	CreatedOn             int    `gorm:"column:created_on" json:"created_on"`
	LastLogin             int    `gorm:"column:last_login" json:"last_login"`
	Active                int    `gorm:"column:active" json:"active"`
	FirstName             string `gorm:"column:first_name" json:"first_name"`
	LastName              string `gorm:"column:last_name" json:"last_name"`
	Company               string `gorm:"column:company" json:"company"`
	Phone                 string `gorm:"column:phone" json:"phone"`
	Nip                   string `gorm:"column:nip" json:"nip"`
	Unit                  string `gorm:"column:unit" json:"unit"`
	Photo                 string `gorm:"column:photo" json:"photo"`
	GroupId               string `gorm:"column:group_id" json:"group_id"`
	Unor                  string `gorm:"column:unor" json:"unor"`
}