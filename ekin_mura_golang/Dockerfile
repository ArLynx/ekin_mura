FROM golang:1.19.4-alpine
WORKDIR /app
COPY . .
RUN go build -o ekin .
EXPOSE 3000
CMD [ "./ekin" ]
