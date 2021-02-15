##

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

***Note:*** For API request, send following header in each of the subsequent requests:
```json
Accept: application/json
Authorization: {{token}}
```
---

###3. Create Book
***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  /api/books

    + Body
        ```json
        {
          "title": "Book title",
          "isbn": "0005534186",
          "published_at": "2010-10-22",
          "status": "AVAILABLE"
        }
        ```
***Response:***
+ Response 201 (application/json)

    + Body
        ```json
        {
            "message": "Book Created Successfully.",
            "data": {
                "id": 32,
                "title": "Book title",
                "isbn": "0005534186",
                "published_at": "2010-10-22",
                "status": "AVAILABLE"
            }
        }
        ```

---

###4. List all books
Request: [GET]
+ Request (application/json)
    + Headers

            Location:  /api/books
    
+ Response 200 (application/json)
    + Body
      ```json
      {
        "data": [
            {
              "id": 1,
              "title": "Tenetur maxime laudantium dolores perferendis reiciendis asperiores sunt. Nihil neque deleniti blanditiis facilis sunt voluptatem. Perspiciatis unde beatae eligendi commodi vel rerum perferendis. Dolorum delectus iste quisquam dolorem.",
              "isbn": "0441013597",
              "published_at": "1983-05-17",
              "status": "AVAILABLE"
          },
          {
              "id": 2,
              "title": "Et temporibus sint quia. Voluptatum esse id saepe at asperiores. Aut et sequi nesciunt dolor. Quaerat expedita temporibus deserunt ad dignissimos et.",
              "isbn": "0978194527",
              "published_at": "1978-10-06",
              "status": "CHECKED_OUT"
          },
         ...
              {
              "id": 15,
              "title": "Quo sequi quo ullam ut non. Sit ut vero ea provident. Enim non aspernatur tenetur laborum sit enim. Ea delectus delectus at doloremque assumenda deleniti nobis. Explicabo voluptates delectus vitae.",
              "isbn": "0978194004",
              "published_at": "1995-12-05",
              "status": "AVAILABLE"
              }
          ],
          "links": {
              "first": "http://localhost/api/books?page=1",
              "last": "http://localhost/api/books?page=3",
              "prev": null,
              "next": "http://localhost/api/books?page=2"
          },
          "meta": {
              "current_page": 1,
              "from": 1,
              "last_page": 3,
              "links": [
              {
                  "url": null,
                  "label": "&laquo; Previous",
                  "active": false
              },
              {
                  "url": "http://localhost/api/books?page=1",
                  "label": 1,
                  "active": true
              },
              {
                  "url": "http://localhost/api/books?page=2",
                  "label": 2,
                  "active": false
              },
              {
                  "url": "http://localhost/api/books?page=3",
                  "label": 3,
                  "active": false
              },
              {
                  "url": "http://localhost/api/books?page=2",
                  "label": "Next &raquo;",
                  "active": false
              }
              ],
              "path": "http://localhost/api/books",
              "per_page": 15,
              "to": 15,
              "total": 33
              }
        }
        ```

***Note:***

If you want list of available books send GET request to ```/api/books?status=AVAILABLE```


---

###3. Create Book
***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  /api/books

    + Body
        ```json
        {
          "title": "Book title",
          "isbn": "0005534186",
          "published_at": "2010-10-22",
          "status": "AVAILABLE"
        }
        ```
***Response:***
+ Response 201 (application/json)

    + Body
        ```json
        {
            "message": "Book Created Successfully.",
            "data": {
                "id": 1,
                "title": "Book title",
                "isbn": "0005534186",
                "published_at": "2010-10-22",
                "status": "AVAILABLE"
            }
        }
        ```

---
