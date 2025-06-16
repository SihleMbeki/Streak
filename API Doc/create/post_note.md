# POST NOTE
## POST /v1/create
Headers
| Parameter       | value         |
| -------------   |:-------------:| 
| Application Type| JSON          |
| Token           | berea token   |

### Payload examples:
```
{
"streak": "Vegetable meal",
"Endate": "10-12-2026", must be a Future date
"Schedule": "Weekly", can be weekly or Monthly
}
```

### On Success
status: 200

### AC
- 401 invalid token
- 400-01 Past Date
- 400-02 Invalid Date
- 400-02 streak already exists
- 422-01 User selected monthly while remaining days between end Date and current day are in the current Month
- 422-02 User selected weekly while remaining days between end Date and current day is less than 7 Days
- 422-03 Invalid schedule
