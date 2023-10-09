# Schedule service

 Service is generating annuity schedule for given loan parameters. 
 Has no practical value and aim to explore possibility to use Laravel and Eloquent framework for DDD.


### Technology Stack
* PHP 8.2 + Laravel Framework 10.26.2 + Nginx
* Docker
* MySQL 8.0

### How to run
```bash
git clone https://github.com/shuraknows/schedule-srv.git
cd schedule-srv
make install
```

### Ports
By default service is available on port 8089.
DB is available on port 3336 **user:** _phper_ **password:** _secret_

### Generate schedule
```bash
curl --location 'http://localhost:8089/api/schedule' \
--header 'Content-Type: application/json' \
--data '{
    "amount": 1000000,
    "term": 12,
    "interestRate": 400,
    "euriborRate": 394
}'

#{
#    "id":"bea1d081-f8e9-499f-8ade-961705953bd0",
#    "amount":1000000,
#    "term":12,
#    "interestRate":400,
#    "segments":[
#      {"sequenceNumber":1,"remainingAmount":1000000,"principalPayment":81817,"interestPayment":3333,"euriborPayment":3283,"totalPayment":88433,"euriborRate":394},
#      {"sequenceNumber":2,"remainingAmount":918183,"principalPayment":82089,"interestPayment":3061,"euriborPayment":3015,"totalPayment":88165,"euriborRate":394},
#      {"sequenceNumber":3,"remainingAmount":836094,"principalPayment":82363,"interestPayment":2787,"euriborPayment":2745,"totalPayment":87895,"euriborRate":394}
#      ...
#    ]
#}
```

### Change euribor rate
```bash
curl --location --request PUT 'http://localhost:8089/api/schedule/bea1d081-f8e9-499f-8ade-961705953bd0/euribor-rate' \
--header 'Content-Type: application/json' \
--data '{
    "segment": 6,
    "euriborRate": 410
}'

#
#{
#    "id": "bea1d081-f8e9-499f-8ade-961705953bd0",
#    "amount": 1000000,
#    "term": 12,
#    "interestRate": 400,
#    "segments": [
#        {"sequenceNumber": 1, "remainingAmount": 1000000, "principalPayment": 81817, "interestPayment": 3333, "euriborPayment": 3283, "totalPayment": 88433, "euriborRate": 394},
#        {"sequenceNumber": 2, "remainingAmount": 918183, "principalPayment": 82089, "interestPayment": 3061, "euriborPayment": 3015, "totalPayment": 88165, "euriborRate": 394},
#        ...
#    ]
#}
```

### Run feature tests
```bash
make test
```
