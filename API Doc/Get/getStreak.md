# Get All Activities
## Get /v1/all/
Headers
| Parameter       | value         |
| -------------   |:-------------:| 
| Application Type| JSON          |
| Token           | berea token   |

### Response examples:
```
{
[
{
"Name": "Health Diet", 
"Schedule": "weekly",
"Count": "10", 
"NextActivity":"10-12-2025"
},
{
"Name": "Morning Run",
"Schedule": "weekly", can be weekly, Monthly
"Count": "0",
"NextActivity":"28-01-2026"
},
{
"Name": "Health Diet", 
"Schedule": "Monthly", 
"Count": "30",
"NextActivity":"30-11-2025"
},
]
}
```

### On Success
status: 200

AC
When there are no steaks, return an empty list
