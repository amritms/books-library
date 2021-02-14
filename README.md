## Requests

___
###1. Register User

***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  /api/register

    + Body
        ```json
        {
            "name": "Amrit Man Shrestha",
            "email": "amritms@gmail.com",
            "password": "Pass1234",
            "date_of_birth": "1999-03-11"
        }
        ```
***Response:***
+ Response 201 (application/json)

    + Body
        ```json
        {
            "message": "Registered Successfully.",
            "data": {
                "token": "4|3omrw2mNwFnQ3FtsHVCshfHRlE1ql1lvmufHuTop"
            }
        }
        ```
      
###2. Login
***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  /api/login

    + Body
        ```json
        {
            "email": "amritms@gmail.com",
            "password": "Pass1234"
        }
        ```
***Response:***
+ Response 201 (application/json)

    + Body
        ```json
        {
            "message": "Successfully Logged in.",
            "data": {
                "token": "8|gltjuNNqyz4BL3Su88ifWNgyBwmOKVvdwirg2wWr"
            }
        }
        ```

