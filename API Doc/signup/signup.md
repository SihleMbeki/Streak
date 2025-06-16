# Signup
## Post /v1/signup/account/create
Headers
| Parameter       | value         |
| -------------   |:-------------:| 
| Application Type| JSON          |

### Payload examples:
```
{
"Name": "Jalisha", 
"Surname": "Dambisa,
"Email": "testaccount@test.com", 
"Password":"******"
}
```

### On Success
status: 201

AC
400-21-1 Account already exists
