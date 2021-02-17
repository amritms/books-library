# API Documentation
API_URL: http://99.79.122.227

## Response Codes ##

| Http status code| User message | 
|---|---|
| OK (200) | The action is completed. | 
| CREATED (201) | Resource created. | 
| RESET CONTENT (205) | User should be redirected to login page. | 
| UNAUTHORIZED (401) | Guest trying to access the resource. | 
| Not Found (404) | The specified resource is not found. | 
| Unprocessable Entity (422) | Validation Error. | 
| Internal Server Error (500) | There is some problem with server. |

## 1. Register User

***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  {{API_URL}}/api/register

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
      

## 2. Login
***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  {{API_URL}}/api/login

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
Authorization: Bearer {{token}}
```


## 3. Logout
***Request:***
[POST]
+ Request (application/json)
    + Headers
    ```
   Location:  {{API_URL}}/api/logout
    ```
***Response:***
+ Response 201 (application/json)

    + Body
        ```json
        {
            "message": "Successfully Logged out."
         }
        ```


## 4. Create a Book
***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  {{API_URL}}/api/books

    + Body
        ```json
        {
          "title": "Book title",
          "isbn": "0005534186",
          "published_at": "2010-10-22"
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
    

## 5. List all books
Request: [GET]
+ Request (application/json)
    + Headers

            Location:  {{API_URL}}/api/books

+ Response 200 (application/json)
    + Body
      ```json
      {
        "data": [
            {
              "id": 1,
              "title": "Perspiciatis expedita rerum et est deleniti ipsam et. Qui accusamus provident quidem a. Ut debitis asperiores perspiciatis perferendis odit fugiat repellendus. Sit aut incidunt aut voluptatem incidunt quia.",
              "isbn": "0978195248",
              "published_at": "2006-09-22",
              "status": "AVAILABLE"
          },
          {
              "id": 2,
              "title": "Non mollitia reiciendis laborum. Blanditiis impedit esse eos et. Saepe voluptatem et aut. Maiores nesciunt expedita ipsum repellat dolore. Sit excepturi odio eos et. Vitae eum illo temporibus ex ut qui aut aperiam. Ullam ut in vel repellendus eaque.",
              "isbn": "0978110196",
              "published_at": "1998-05-02",
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
              "total": 20
              }
        }
        ```

***Note:***

If you want list of available books send GET request to ```{{API_URL}}/api/books?status=AVAILABLE```


## 6. Book checkout
***Note:*** You can only checkout Available books. Get list of available books from ```/api/books?status=AVAILABLE```

***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  {{API_URL}}/api/checkout

    + Body
        ```json
        {
          "book_ids": [1,2,3]
        }
        ```
***Response:***
+ Response 201 (application/json)

    + Body
        ```json
        {
          "message": "Book(s) Checked out Successfully.",
          "data": [
              {
                  "id": 1,
                  "title": "Perspiciatis expedita rerum et est deleniti ipsam et. Qui accusamus provident quidem a. Ut debitis asperiores perspiciatis perferendis odit fugiat repellendus. Sit aut incidunt aut voluptatem incidunt quia.",
                  "isbn": "0978195248",
                  "published_at": "2006-09-22",
                  "status": "CHECKED_OUT"
              },
              {
                  "id": 2,
                  "title": "Non mollitia reiciendis laborum. Blanditiis impedit esse eos et. Saepe voluptatem et aut. Maiores nesciunt expedita ipsum repellat dolore. Sit excepturi odio eos et. Vitae eum illo temporibus ex ut qui aut aperiam. Ullam ut in vel repellendus eaque.",
                  "isbn": "0978110196",
                  "published_at": "1998-05-02",
                  "status": "CHECKED_OUT"
              },
              {
                  "id": 3,
                  "title": "Vero et est labore dolore. Aperiam sit error aliquid. Ea minus maxime delectus voluptatem excepturi numquam illo qui.",
                  "isbn": "0441013597",
                  "published_at": "1999-10-16",
                  "status": "CHECKED_OUT"
              }
          ]
        }
        ```

## 7. Book checkin
***Note:*** You can only checkin checked out books. Get list of available books from ```/api/books?status=CHECKED_OUT```

***Request:***
[POST]
+ Request (application/json)
    + Headers

            Location:  {{API_URL}}/api/checkin

    + Body
        ```json
        {
          "book_ids": [1,2]
        }
        ```
***Response:***
+ Response 201 (application/json)

    + Body
        ```json
        {
            "message": "Book(s) Checked in Successfully.",
            "data": [
                {
                  "id": 1,
                  "title": "Perspiciatis expedita rerum et est deleniti ipsam et. Qui accusamus provident quidem a. Ut debitis asperiores perspiciatis perferendis odit fugiat repellendus. Sit aut incidunt aut voluptatem incidunt quia.",
                  "isbn": "0978195248",
                  "published_at": "2006-09-22",
                  "status": "AVAILABLE"
              },
              {
                  "id": 2,
                  "title": "Non mollitia reiciendis laborum. Blanditiis impedit esse eos et. Saepe voluptatem et aut. Maiores nesciunt expedita ipsum repellat dolore. Sit excepturi odio eos et. Vitae eum illo temporibus ex ut qui aut aperiam. Ullam ut in vel repellendus eaque.",
                  "isbn": "0978110196",
                  "published_at": "1998-05-02",
                  "status": "AVAILABLE"
              }
            ]
        }
        ```
---

[Go back to Readme](../README.md)

