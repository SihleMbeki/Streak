# Login
## POST /v1/authentication
Headers
| Parameter       | value         |
| -------------   |:-------------:| 
| Application Type| JSON          |


### Payload examples:
```
{
"Email": "test@test.com", 
"Password": "test12345",
}
```
### Response examples:
```
{
"Token": "adfsdlkfjswrwesdfsdfsdfdh", 
}
```

### On Success
status: 200

AC
400 - Failed to verify password
400-1 Email required
400-2 Password required
401 - Account does not exists
402 - Invalid email address
