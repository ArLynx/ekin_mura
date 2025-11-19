FROM golang:1.19.4-alpine
RUN rm /etc/sysctl.d/00-alpine.conf || true
WORKDIR /app
COPY . .
RUN go build -o ekin .
EXPOSE 3000
CMD [ "./ekin" ]
