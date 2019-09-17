# EzyAgric Backend

[![CircleCI](https://circleci.com/gh/Akorion/ezyagric-backend-2019/tree/dev.svg?style=svg)](https://circleci.com/gh/Akorion/ezyagric-backend-2019/tree/dev) <a href="https://codeclimate.com/repos/5ce2b0943e13e4019f00cbd5/test_coverage"><img src="https://api.codeclimate.com/v1/badges/cfa77d4d54b520a72130/test_coverage" /></a>

### User Login

#### Request

`POST /api/v1/auth/login`

#### Request Body

```
{
    "email": "valid email",
    "password": "valid password"
}
```

#### Response

Admin

```
{
    "success": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJBQkFIQUpPSDc4ODAwNzY0NUFETUlOIiwiaWF0IjoxNTYwNTExMjY5LCJleHAiOjE1NjA1MTQ4Njl9.jqNBT9TTG18iP9V4SbMBQOBi2b6K9ejTt87nNaCRFQs",
    "user": {
        "admin_address": "Rukungiri",
        "manager_phonenumber": "789394948",
        "admin_name": "Kubiri Youth Agents For Development",
        "_id": "ABAHAJOH788007645ADMIN",
        "status": "Open",
        "admin_id": "AK/MA/0421",
        "manager_name": "Nyesiga Benadeth",
        "time": "2018-07-05T20:12:21:662094",
        "type": "admin",
        "admin_email": "kubiri@akorion.com",
        "admin_value_chain": "crop",
        "manager_location": "Rukungiri",
        "manager_email": "nbenadeth@gmail.com",
        "email": "nbenadeth@gmail.com",
        "eloquent_type": "admin"
    }
}
```

Master Agent

```
{
    "success": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJBQkFIQUpPSDc4ODAwNzY0NUFETUlOIiwiaWF0IjoxNTYwNTExMjY5LCJleHAiOjE1NjA1MTQ4Njl9.jqNBT9TTG18iP9V4SbMBQOBi2b6K9ejTt87nNaCRFQs",
    "user": {
        "ma_address": "Rukungiri",
        "manager_phonenumber": "789394948",
        "ma_name": "Kubiri Youth Agents For Development",
        "_id": "AK/MA/0421",
        "status": "Open",
        "ma_id": "AK/MA/0421",
        "manager_name": "Nyesiga Benadeth",
        "time": "2018-07-05T20:12:21:662094",
        "type": "ma",
        "ma_email": "kubiri@akorion.com",
        "ma_value_chain": "crop",
        "manager_location": "Rukungiri",
        "manager_email": "nbenadeth@gmail.com",
         "email": "nbenadeth@gmail.com",
        "eloquent_type": "admin"
    }
}
```

offtaker

```
{
    "success": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJsdW1lbi1qd3QiLCJzdWIiOiJBQkFIQUpPSDc4ODAwNzY0NUFETUlOIiwiaWF0IjoxNTYwNTExMjY5LCJleHAiOjE1NjA1MTQ4Njl9.jqNBT9TTG18iP9V4SbMBQOBi2b6K9ejTt87nNaCRFQs",
    "user": {
        "partner_id": "AK/OT/0001",
        "ot_phonenumber": "256788345623",
        "type": "offtaker",
        "status": "active",
        "time": "2019-03-19 12:31:13.114047",
        "ot_name": "Luparelia",
        "_id": "AK/OT/0001",
        "ot_email": "ray@gmail.com",
        "ot_district": "Masaka",
        "ot_address": "lums",
         "email": "masteragent1234@gmail.com",
        "eloquent_type": "admin"
    }
}
```

### Get all village agents/offtakers/input suppliers

#### Request

`GET /api/v1/users/{user}`
Param `{user}` = `offtakers`, `village-agents`,`input-suppliers`, `government` or `farmers`

#### Response

Village agents

```
{
    "success": true,
    "count": 1,
    "villageAgents": [
        {
            "_id": "248833b0262f4ddeaa8d69105677f886",
            "agriculture_experience_in_years": "NA",
            "assets_held": "NA",
            "certification_doc_url": "NA",
            "education_doc_url": "NA",
            "education_level": "NA",
            "eloquent_type": "va",
            "farmers_enterprises": "NA",
            "ma_id": "00276417-eadf-371c-a6b1-2aee76f3b5b0",
            "other_occupation": "NA",
            "partner_id": "NA",
            "position held_in_community": "NA",
            "service_provision_experience_in_years": "NA",
            "services_va_provides": "NA",
            "status": "active",
            "time": "2018-07-05T21:48:13:141586",
            "total_farmers_acreage": "NA",
            "total_number_of_farmers": "NA",
            "type": "va",
            "vaId": "AK/MA/0421/0001",
            "va_country": "Uganda",
            "va_district": "rukungiri",
            "va_dob": "NA",
            "va_gender": "female",
            "va_home_gps_Accuracy": "NA",
            "va_home_gps_Altitude": "NA",
            "va_home_gps_Latitude": "NA",
            "va_home_gps_Longitude": "NA",
            "va_id_number": "NA",
            "va_id_type": "NA",
            "va_name": "Nyesiga Benadeth",
            "va_parish": "Nyakariro",
            "va_phonenumber": 789394948,
            "va_photo": "https =>//drive.google.com/open?id=1MwZuPcWTOcJYa6536Buk9FEc5i7HrZ3U",
            "va_region": "Western",
            "va_subcounty": "Bwambara",
            "va_village": "Kashayo",
            "master_agent": {
            "_id": "00276417-eadf-371c-a6b1-2aee76f3b5b0",
            "account_name": "Senger-Stark",
            "address": "36977 Trisha Locks\nOtistown, FL 08845-9403",
            "contact_person": "Miss Rosalia Gleason DDS",
            "created_at": "2019-09-02 21:14:33",
            "district": "Bushenyi",
            "eloquent_type": "ma",
            "email": "nannie23@mitchell.info",
            "phone_number": "1-864-665-0420 x8935",
            "status": "demo",
            "type": "ma",
            "updated_at": "2019-09-02 21:14:33",
            "username": "stephen81",
            "value_chain": "Crop"
        },
            "devt_partner": "N/A"
        }
    ]
}
```

Offtakers

```
{
  "success": true,
  "count": 136,
  "result": [
    {
      "_id": "-7mb_am",
      "contact_person": "N\/A",
      "created_at": "2019-07-18 21:06:21",
      "district": "N\/A",
      "eloquent_type": "offtaker",
      "email": "Bl3xJRj@gmail.com",
      "account_name": "fghjklkjhgf",
      "username": "fgyuhijokjhgf",
      "organization": "somewhere",
      "phone_number": "32489765478",
      "status": "demo",
      "type": "offtaker",
      "updated_at": "2019-07-18 21:06:21",
      "value_chain": "N\/A"
    }
  ]
}
```

Input suppliers

```

  "success": true,
  "count": 1,
  "result": [
    {
      "DateAdded": "2018-08-31",
      "DateUpdated": "11\/9\/2018",
      "_id": "648ac260-48ec-315f-a8e8-241bd94c5cab",
      "category": "Herbicides",
      "created_at": "2019-07-18 18:04:02",
      "crops": [
        "beans",
        "soya"
      ],
      "description": "Selective weed killer for beans and soya",
      "eloquent_type": "input",
      "name": "Beans Clean",
      "photo_url": "\/images\/7e185f0a-cfc5-45a3-bb4d-6ef6535a5042.png",
      "price": [
        38100,
        21000
      ],
      "quantity": 9992,
      "supplier": "Hangzhou Agrochemicals (U) Ltd",
      "type": "input",
      "unit": [
        "1Litre",
        "500ml"
      ],
      "updated_at": "2019-07-18 18:04:02"
    }
  ],
  "percentage": 100
}
```

Government

```
{
  "success": true,
  "count": 9,
  "result": [
    {
             "_id": "CuhD8lb",
            "account_type": "Custom",
            "address": "Molestiae nostrud la",
            "contact_person": "Distinctio",
            "created_at": "2019-07-25 13:23:08",
            "district": "Kyenjojo",
            "eloquent_type": "government",
            "email": "government24@gmail.com",
            "account_name": "Veda htu",
            "username": "Nunez",
            "phone_number": "+1 (669) 138-6075",
            "status": "demo",
            "type": "government",
            "updated_at": "2019-07-25 13:23:08",
            "value_chain": "N/A"
    }
  ]
}
```

### Create an Admin account

#### Request

`POST /api/v1/admin`

#### Request Body

```
{
         "email": "admin2222@gmail.com",
        "password": "admin2222",
        "confirmPassword": "admin2222",
        "adminRole": "Super Admin",
        "firstname": "John",
        "lastname":"Doe"
}
```

#### Response

```
{
    "success": true,
    "admin": {
        "type": "admin",
        "email": "admin2222@gmail.com",
        "adminRole": "Super Admin",
        "firstname": "John",
        "lastname": "Doe",
        "_id": "n9XoPny",
        "updated_at": "2019-07-01 17:21:15",
        "created_at": "2019-07-01 17:21:15"
    }
}
```

### Create an Offtaker account

#### Request

`POST /api/v1/users/offtaker`

#### Request Body

```
{
  "account_type": "Custom",
  "address": "Molestiae nostrud la",
  "contact_person": "Distinctio",
  "district": "Kyenjojo",
  "email": "test@gmail.com",
  "account_name": "Veda htu",
  "username": "Nunez",
  "password": "123123",
  "phone_number": "+1 (669) 138-6075",
  "value_chain": "N/A"
}
```

#### Response

```

  "message": "Please check your mail for your login password",
  "success": true,
  "offtaker": {
    "type": "offtaker",
    "status": "demo",
    "account_type": "Custom",
    "contact_person": "Distinctio",
    "district": "Kyenjojo",
    "email": "test@gmail.com",
    "account_name": "Veda htu",
    "username": "Nunez",
    "phone_number": "+1 (669) 138-6075",
    "value_chain": "N\/A",
    "_id": "ss0mLB-",
    "updated_at": "2019-07-22 14:38:05",
    "created_at": "2019-07-22 14:38:05"
  }
}
```

==========================================================

### Create a MasterAgent account

#### Request

`POST /api/v1/users/masteragent`

#### Request Body

```
{
  "account_type": "Custom",
  "address": "Molestiae nostrud la",
  "contact_person": "Distinctio",
  "district": "Kyenjojo",
  "email": "teste@gmail.com",
  "account_name": "Veda htu",
  "username": "Nunez",
  "password": "123123",
  "phone_number": "+1 (669) 138-6075",
  "value_chain": "N/A"
}
```

#### Response

```
{
  "message": "Please check your mail for your login password",
  "success": true,
  "offtaker": {
    "type": "ma",
    "status": "demo",
    "account_type": "Custom",
    "address": "Molestiae nostrud la",
    "contact_person": "Distinctio",
    "district": "Kyenjojo",
    "email": "teste@gmail.com",
    "account_name": "Veda htu",
    "username": "Nunez",
    "phone_number": "+1 (669) 138-6075",
    "value_chain": "N\/A",
    "_id": "JVdnEBB",
    "updated_at": "2019-07-22 14:40:20",
    "created_at": "2019-07-22 14:40:20"
  }
}
```

==========================================================

### Create a Government account

#### Request

`POST /api/v1/users/government`

#### Request Body

```
{
  "account_type": "Custom",
  "address": "Molestiae nostrud la",
  "contact_person": "Distinctio",
  "district": "Kyenjojo",
  "email": "government29@gmail.com",
  "account_name": "Christiana",
  "username": "Nunez",
  "password": "123123",
  "phone_number": "+1 (669) 138-6075",
  "value_chain": "N/A"
}
```

#### Response

```
{
  "message": "Please check your mail for your login password",
  "success": true,
    "government": {
        "type": "government",
        "status": "demo",
        "account_type": "Custom",
        "address": "Molestiae nostrud la",
        "contact_person": "Distinctio",
        "district": "Kyenjojo",
        "email": "government29@gmail.com",
        "account_name": "Christiana",
        "username": "Nunez",
        "phone_number": "+1 (669) 138-6075",
        "value_chain": "N/A",
        "_id": "ozhdbTE",
        "updated_at": "2019-07-29 13:39:10",
        "created_at": "2019-07-29 13:39:10"
    }
}
```

### Get all development partners

#### Request

`GET /api/v1/devt-partners`

#### Response

```
{
    "success": true,
    "count": 1,
    "devtPartners": [
        {
            "_id": "AK/DP/0001",
            "account_type": "generic",
            "category": "development-partner",
            "dp_address": "NA",
            "dp_email": "devtest1@akorion.com",
            "dp_location": "NA",
            "dp_name": "Akorion Dev",
            "dp_password": "o2A8oo40^L",
            "dp_phonenumber": "256703276434",
            "eloquent_type": "partner",
            "partner_id": "AK/DP/0001",
            "status": "Open",
            "time": "2019-04-23 20:05:56.502024",
            "type": "partner",
            "value_chain": "crop"
        }
    ]
}
```

### Create a Development Partner account

#### Request

`POST /api/v1/dev-partner`

#### Request Body

```
{
  "account_type": "Custom",
  "address": "Molestiae nostrud la",
  "contact_person": "Distinctio",
  "district": "Kyenjojo",
  "email": "nf@gmail.com",
  "account_name": "Veda htu",
  "username": "Nunez",
  "password": "123123",
  "phone_number": "+1 (669) 138-6075",
  "value_chain": "N/A"
}
```

#### Response

```
{
    "success": true,
    "devPartner": {
        "type": "partner",
        "category": "development-partner",
        "status": "closed",
        "email": "devpartner12345@gmail.com",
        "dp_username": "devpartner",
        "dp_name": "devpartner",
        "account_type": "Custom",
        "dp_phonenumber": "34567898765",
        "dp_district": "somewheere",
        "dp_address": "somewhere",
        "value_chain": "Crop",
        "_id": "7Svw6w0",
        "updated_at": "2019-06-18 15:53:04",
        "created_at": "2019-06-18 15:53:04"
    }
  "success": true,
  "topDistricts": {
    "Lagos": 2,
    "Rukungiri": 1
  }
}
```

=======

### Get top districts

#### Request

`GET /api/v1/top-districts`

#### Response

```
{
  "success": true,
  "districtCount": 5,
  "farmerCount": 7,
  "topDistricts": {
    "Lagos": 2,
    "Borno": 2,
    "Rukungiri": 1,
    "Ogun": 1
  },
  "allDistricts": {
    "Lagos": 2,
    "Borno": 2,
    "Rukungiri": 1,
    "Ogun": 1,
    "Kwara": 1
  }
}
```

### Get total acreage for farmers

#### Request

`GET /api/v1/total-acreage`

#### Response

```
{
  "success": true,
  "totalAcreage": 6
}
```

### Get total payment by farmers

#### Request

`GET /api/v1/total-payment`

#### Response

```
{
  "success": true,
  "totalPayment": 2345.21
}
```

### Get activity summary

#### Request

`GET /api/v1/activity-summary`

#### Response

```
{
  "success": true,
  "inputOrders": 3340000,
  "acresPlanted": 12,
  "soilTestAcreage": 25,
  "gardenMapped": 81.684316
}
```

### Get all master agents

#### Request

`GET /api/v1/masteragent`

#### Response

```
{
    "success": true,
    "count": 1,
       "masteragent": [
        {
            "_id": "-8ly79g",
            "created_at": "2019-06-19 15:07:45",
            "eloquent_type": "ma",
            "email": "w7y7gJQ@gmail.com",
            "ma_address": "somewhere",
            "ma_district": "somewhere",
            "ma_manager_name": "Samuel",
            "ma_name": "masteragent",
            "ma_phonenumber": "234567897654",
            "password": "$2y$10$r1C/vtQTlfYKNw/NBWSwZ.dgj2XhZBPn1eRvVeps5cnB9qxLFKM7W",
            "status": "inactive",
            "type": "ma",
            "updated_at": "2019-06-19 15:07:45"
        },
        {
            "_id": "-ASHP3E",
            "account_type": "something",
            "category": "development-partner",
            "created_at": "2019-06-16 00:56:19",
            "dp_address": "lums",
            "dp_district": "Masaka",
            "dp_manager_name": "AK/OT/0001",
            "dp_name": "Luparelia",
            "dp_phonenumber": "256788345623",
            "dp_username": "somename",
            "eloquent_type": "ma",
            "email": "masteragent12346@gmail.com",
            "password": "$2y$10$Urdjjk2.wyZcrasrSq3JW.EUnr1uTnDBvO.psB4GlQva.2yhngQ7u",
            "status": "closed",
            "type": "partner",
            "updated_at": "2019-06-16 00:56:19",
            "value_chain": "something"
        },
        {
            "_id": "-rtfnqS",
            "created_at": "2019-06-18 09:30:10",
            "eloquent_type": "ma",
            "email": "x1T9YHR@gmail.com",
            "ma_account_type": "Custom",
            "ma_address": "somewhere",
            "ma_district": "somewhere",
            "ma_manager_name": "Samuel",
            "ma_name": "masteragent",
            "ma_phonenumber": "234567897654",
            "password": "$2y$10$6Ra2KEHGxKn5qlKYKZvOou8UvGnDFW806QZVSlPObMFZisGE6Qf4a",
            "status": "inactive",
            "type": "ma",
            "updated_at": "2019-06-18 09:30:10"
        },
        {
            "_id": "0Gow7lq",
            "created_at": "2019-06-20 15:15:41",
            "eloquent_type": "ma",
            "email": "e2tBULN@gmail.com",
            "ma_address": "somewhere",
            "ma_district": "somewhere",
            "ma_manager_name": "Samuel",
            "ma_name": "masteragent",
            "ma_phonenumber": "234567897654",
            "password": "$2y$10$hQD9a8Edbpd7LfHBbEBLx.LJK7qsJ4Gz/TMMKUu75kPGHcm2xh0t2",
            "status": "inactive",
            "type": "ma",
            "updated_at": "2019-06-20 15:15:41"
        },
        {
            "_id": "0ncgUX0",
            "created_at": "2019-06-20 15:40:16",
            "eloquent_type": "ma",
            "email": "bIq9PbJ@gmail.com",
            "ma_address": "somewhere",
            "ma_district": "somewhere",
            "ma_manager_name": "Samuel",
            "ma_name": "masteragent",
            "ma_phonenumber": "234567897654",
            "password": "$2y$10$W4NOeruc/6a.HHXuuwIOhu9LzzWnm.KTcpF9Sr/FmaP.ofYwwlt06",
            "status": "inactive",
            "type": "ma",
            "updated_at": "2019-06-20 15:40:16"
        },
        {
            "_id": "0smLDKf",
            "created_at": "2019-06-21 02:28:35",
            "eloquent_type": "ma",
            "email": "dF_lEf5@gmail.com",
            "ma_address": "somewhere",
            "ma_district": "somewhere",
            "ma_manager_name": "Samuel",
            "ma_name": "masteragent",
            "password": "$2y$10$yyCEMr/i9NIY3xYwcvOrDOSoW6I1vswisJjZQ33NyECip57v/jMLK",
            "phonenumber": "234567897654",
            "status": "inactive",
            "type": "ma",
            "updated_at": "2019-06-21 02:28:35"
        },
            ]
}
```

### Activate or Suspend Account

#### Request

`PATCH /api/v1/{action}/{id}`
Param `{action}` = `activate` or `suspend`
Param `{id}` = Account id

#### Response

```
{
    "success": true,
    "message": "Account update successful.",
    "user": {
        "_id": "2DUALsI",
        "contact_person": "N/A",
        "created_at": "2019-06-25 09:15:08",
        "district": "N/A",
        "eloquent_type": "ma",
        "email": "zqdc-lR@gmail.com",
        "firstname": "hjkl;",
        "lastname": "fgyuhijokjhgf",
        "organization": "somewhere",
        "phonenumber": "324897654e78",
        "status": "active",
        "type": "ma",
        "updated_at": "2019-06-28 08:36:25",
        "value_chain": "N/A"
    }
}

```

### Send Message Through the Contact Form

`POST /api/v1/contact`

#### Request

```
{
    "name": "Daniel Emmanuel",
    "email": "ecomje@gmail.com",
    "message": "Lorem ipsum dolor sit amet, affert accumsan conceptam per ne, ius in rebum cetero. In pri ridens percipit, illum malis persequeris pro in. Sint deterruisset pri ad, quem intellegebat ut sea. Ea vis latine nominati, eum libris tibique cu, mea everti omnium ea. Meliore erroribus assueverit in est. Cum amet ponderum ut."
}
```

#### Response Body

```
{
    "message": "thank you for reaching out to us. we would get back to you shortly",
    "success": "true",
}
```

===================

### Get Twitter report

#### Request

`GET /api/v1/twitter-report`

#### Response

```
{
  "success": true,
  'data': {
    "followers_count": 106,
    "statuses_count": 118
  }
}
```

=======================================

### Get Youtube Channel report

#### Request

`GET /api/v1/youtube-report`

#### Response

```
{
  "success": true,
  "data": {
      "viewCount": "0",
      "commentCount": "0",
      "subscriberCount": "1",
      "hiddenSubscriberCount": false,
      "videoCount": "0"
  }
}
```

======================================

### Get Facebook report

#### Request

`GET /api/v1/facebook-report`

#### Response

```
{
  "success": true,
  "data": {
      "fanCount": 2,
      "shares": 1
   }
}
```

========================================

### Get all admins

#### Request

`GET /api/v1/admins`

#### Response

```
{
    "success": true,
    "count": 237,
    "result": [

        {
            "_id": "-H9PagX",
            "adminRole": "Analyst",
            "created_at": "2019-07-04 08:38:50",
            "eloquent_type": "admin",
            "email": "UxczuHI@gmail.com",
            "type": "admin",
            "updated_at": "2019-07-04 08:38:50"
        },
        {
            "_id": "-aL1a-e",
            "adminRole": "Analyst",
            "created_at": "2019-07-03 07:08:45",
            "eloquent_type": "admin",
            "email": "tekgxpw@gmail.com",
            "type": "admin",
            "updated_at": "2019-07-03 07:08:45"
        }
    ]
}
```

========================================

### Get top farm produce

#### Request

`GET /api/v1/top-produce`

#### Response

```
{
    "success": true,
    "farmProduceCount": 20,
    "topFarmProduce": {
        "Popcorn": 6,
        "Tobbaco": 5,
        "Cassava": 3,
        "Beans": 2,
        "Rice": 2
    },
    "allFarmProduce": {
        "Popcorn": 6,
        "Tobbaco": 5,
        "Cassava": 3,
        "Beans": 2,
        "Rice": 2,
        "Coffee": 2
    }
}
```

========================================

### Get top performing master or viilage agents

#### Request

`GET /api/v1/top-performing/{agent}`
Param `{agent}` = `ma` or `va`

#### Response

```
{
    "success": true,
    "data": [
        {
            "farmerCount": 2,
            "orderCount": 2,
            "name": "Bruce Abernathy"
        },
        {
            "farmerCount": 2,
            "orderCount": 2,
            "name": "Llewellyn Wiza"
        },
        {
            "farmerCount": 2,
            "orderCount": 2,
            "name": "Gilbert Rohan"
        },
        {
            "farmerCount": 2,
            "orderCount": 2,
            "name": "Bryce Douglas"
        },
        {
            "farmerCount": 2,
            "orderCount": 2,
            "name": "Rowan Hackett"
        }
    ]
}
```

========================================

### Request for an offtaker or masteragent account

#### Request

`POST api/v1/request{user}`
Param `{user}` = `offtaker` or `masteragent`

##### Request body

```
{
    "email":"mikel@gmail.com",
    "firstname": "Leticia",
    "lastname": "Esiagu",
    "phonenumber": "07063512587",
    "address": "Andela"
}
```

#### Response body

```
{
  "success": true,
  "offtaker": {
    "type": "offtaker",
    "status": "demo",
    "email": "mikel@gmail.com",
    "firstname": "Leticia",
    "lastname": "Esiagu",
    "phonenumber": "07063512587",
    "_id": "oeTMKql",
    "district": "N\/A",
    "value_chain": "N\/A",
    "account_type": "Generic",
    "contact_person": "N\/A",
    "updated_at": "2019-07-22 14:48:27",
    "created_at": "2019-07-22 14:48:27"
  }
}
```

### Get all activity logs

#### Request

`GET /api/v1/activity-log/?limit=17&offset=5`

#### Request Body

#### Response

```
[
    {
        "email": "admin2020@gmail.com",
        "target_email": "johndoe@gmail.com",
        "target_account_name": "john",
        "target_lastname": "doe",
        "activity": "account deleted",
        "updated_at": "2019-07-22 14:48:27",
        "created_at": "2019-07-22 14:48:27"
    }
]
```

### Get top performing districts by app downloads and web users

#### Request

`POST api/v1/top-performing-district`

#### Response body

```
{
    "success": true,
    "data": [
        {
            "appDownloads": 12,
            "name": "Buhweju",
            "webUsers": 38,
            "appPurchases": 12
        },
        {
            "appDownloads": 6,
            "name": "Bushenyi",
            "webUsers": 40,
            "appPurchases": 23
        },
        {
            "appDownloads": 5,
            "name": "Rukungiri",
            "webUsers": 30,
            "appPurchases": 10
        },
        {
            "appDownloads": 4,
            "name": "Rubirizi",
            "webUsers": 44,
            "appPurchases": 18
        },
        {
            "appDownloads": 3,
            "name": "Ntoroko",
            "webUsers": 44,
            "appPurchases": 17
        }
    ]
}
```

### Get all districts

#### Request

`GET api/v1/districts`

#### Response body

```
{

    "success": true,
    "message": "Districts retrieved successfully",
    "data": [
        {
            "name": "Nakasongola"
        },
        {
            "name": "Bundibugyo"
        },
        {
            "name": "Kyankwanzi"
        },
        {
            "name": "Kole"
        },
        {
            "name": "Maracha"
        },
        {
            "name": "Kiboga"
        },
        {
            "name": "Kyenjojo"
        }
    ]
}
```

### Get all active users

#### Request

`GET /api/v1/active-users`

#### Request Body

#### Response

```
{
    "success": true,
    "allUsersCount": 98,
    "activeUsersCount": 3
}
```

========================================

### Get all mobile active users

#### Request

`GET /api/v1/active-mobile-users`

#### Response
```
{
    "success": true,
    "allMobileUsersCount": 40,
    "activeMobileUsersCount": 2
}
```

========================================

### Get all enterprise (crops)

#### Request

`GET api/v1/enterprises`

#### Response body

```
{

    "success": true,
    "message": "Enterprises retrieved successfully",
    "data": [
        {
            "name": "Rice"
        },
        {
            "name": "Beans"
        },
        {
            "name": "Popcorn"
        },
        {
            "name": "Coffee"
        },
        {
            "name": "Tobacco"
        },
        {
            "name": "Cassava"
        },
        {
            "name": "Maize"
        }
    ]
}
```

========================================

### Get most ordered products and services

#### Request

`GET api/v1/most-ordered?type={type}&filter={filter}`

Param `{type}` = `district` or `enterprise`

Param `{filter}` = `districtName` or `cropName`

#### Response body

```
{

    "success": true,
    "data": {
        "products": [
            [
                "Blended fertilizer",
                "Dudu Cypher"
            ],
            [
                "Weed master",
                "Dudu Cypher"
            ],
            [
                "Weed master",
                "Harvester"
            ],
            [
                "Korn Kali",
                "Dudu Kill"
            ],
            [
                "Maguguma",
                "Harvester"
            ],
            [
                "Blended fertilizer",
                "Dudu Kill"
            ],
            [
                "Weed master",
                "Metrazin"
            ],
            [
                "Korn Kali",
                "Metrazin"
            ],
            [
                "Korn Kali",
                "Dudu Cypher"
            ]
        ],
        "services": [
            {
                "garden_mapping": 9
            },
            {
                "soil_test": 6
            },
            {
                "planting": 6
            },
            {
                "spraying": 4
            },
            {
                "insurance": 8
            }
        ]
    }
}
```

### Get farmers and village agents order statistics

#### Request

`GET api/v1/farmer-agents-order-statistics?start_date={date}&end_date={date}`

Param `{date}` = `YYYY-MM-DD`

#### Response body

```
{
    "success": true,
    "data": [
        {
            "soil_test_agent": 19,
            "soil_test_farmer": 20
        },
        {
            "garden_mapping_agent": 30,
            "garden_mapping_farmer": 30
        },
        {
            "planting_agent": 20,
            "planting_farmer": 20
        },
        {
            "insurance_agent": 45,
            "insurance_farmer": 45
        },
        {
            "spraying_agent": 20,
            "spraying_farmer": 20
        }
    ]
}
```

### Add multiple village agents

#### Request

`POST api/v1/village-agents`

```
{
	"villageAgents": [
	{
	  	"agriculture_experience_in_years": "NA",
      "assets_held": "NA",
      "certification_doc_url": "NA",
      "created_at": "2019-08-29 00:02:08",
      "education_doc_url": "NA",
      "education_level": "NA",
      "eloquent_type": "va",
      "farmers_enterprises": "NA",
      "ma_id": "AK\/MA\/0421",
      "other_occupation": "NA",
      "partner_id": "NA",
      "password": "$2y$10$0hRHy0Ktg8QW3cAfDqgdvuP4YfwjYMBzunlY5LcrxrdsORahMAu7u",
      "position held_in_community": "NA",
      "service_provision_experience_in_years": "NA",
      "services_va_provides": "NA",
      "status": "active",
      "time": "2018-07-05T21:48:13:141586",
      "total_farmers_acreage": "NA",
      "total_number_of_farmers": "NA",
      "type": "va",
      "updated_at": "2019-08-29 00:02:08",
      "vaId": "AK\/MA\/0421\/0001",
      "va_country": "Uganda",
      "va_district": "Buhweju",
      "va_dob": "NA",
      "va_gender": "female",
      "va_home_gps_Accuracy": "NA",
      "va_home_gps_Altitude": "NA",
      "va_home_gps_Latitude": "NA",
      "va_home_gps_Longitude": "NA",
      "va_id_number": "NA",
      "va_id_type": "NA",
      "va_name": "Melyna Huels Jr.",
      "va_parish": "Nyakariro",
      "va_phonenumber": "6286-815-6339130690",
      "va_photo": "https =>\/\/drive.google.com\/open?id=1MwZuPcWTOcJYa6536Buk9FEc5i7HrZ3U",
      "va_region": "Western",
      "va_subcounty": "Bwambara",
      "va_village": "Kashayo"
	},
{
	  	"agriculture_experience_in_years": "NA",
      "assets_held": "NA",
      "certification_doc_url": "NA",
      "created_at": "2019-08-29 00:02:08",
      "education_doc_url": "NA",
      "education_level": "NA",
      "eloquent_type": "va",
      "farmers_enterprises": "NA",
      "ma_id": "AK\/MA\/0421",
      "other_occupation": "NA",
      "partner_id": "NA",
      "password": "$2y$10$0hRHy0Ktg8QW3cAfDqgdvuP4YfwjYMBzunlY5LcrxrdsORahMAu7u",
      "position held_in_community": "NA",
      "service_provision_experience_in_years": "NA",
      "services_va_provides": "NA",
      "status": "active",
      "time": "2018-07-05T21:48:13:141586",
      "total_farmers_acreage": "NA",
      "total_number_of_farmers": "NA",
      "type": "va",
      "updated_at": "2019-08-29 00:02:08",
      "vaId": "AK\/MA\/0421\/0001",
      "va_country": "Uganda",
      "va_district": "Buhweju",
      "va_dob": "NA",
      "va_gender": "female",
      "va_home_gps_Accuracy": "NA",
      "va_home_gps_Altitude": "NA",
      "va_home_gps_Latitude": "NA",
      "va_home_gps_Longitude": "NA",
      "va_id_number": "NA",
      "va_id_type": "NA",
      "va_name": "Melyna Huels Jr.",
      "va_parish": "Nyakariro",
      "va_phonenumber": "62386-815-633930690",
      "va_photo": "https =>\/\/drive.google.com\/open?id=1MwZuPcWTOcJYa6536Buk9FEc5i7HrZ3U",
      "va_region": "Western",
      "va_subcounty": "Bwambara",
      "va_village": "Kashayo"
	}
	]
}
```


### Get all Farmers

#### Request

`GET api/v1/farmers`

```
{
  "success": true,
  "count": 265,
  "result": [
    {
      "_id": "018e6b4b-acf5-3f19-8c2f-ce5ed05b1127",
      "agriculture_experience_in_years": 4,
      "assets_held": "NA",
      "created_at": "2019-08-21 07:47:45",
      "eloquent_type": "farmer",
      "farmer_district": "Rukungiri",
      "farmer_dob": "05\/05\/1979",
      "farmer_gender": "male",
      "farmer_id": "fd657380-0fb2-3a86-8171-62e80e9ce8ac",
      "farmer_location_farmer_home_gps_Accuracy": "NA",
      "farmer_location_farmer_home_gps_Altitude": "NA",
      "farmer_location_farmer_home_gps_Latitude": -0.5909426,
      "farmer_location_farmer_home_gps_Longitude": 29.7671408,
      "farmer_name": "Furman Kunde",
      "farmer_parish": "Kikarara",
      "farmer_phone_number": "+1.578.469.0184",
      "farmer_photo": "https:\/\/drive.google.com\/open?id=1tb0ovB3lzvsnhW6DgzTW3_ZnW8MaWW9b",
      "farmer_region": "Central",
      "farmer_subcounty": "Bwambara",
      "farmer_village": "Nyajatembe",
      "garden_acreage_mapped_gps": "NA",
      "garden_acreage_not_mapped_gps": "2",
      "garden_mapped": "NA",
      "land_gps_url": "NA",
      "ma_id": "137a93d1-4cac-335e-a37b-fc8728389710",
      "other_occupation": "NA",
      "partner_id": "NA",
      "position held_in_community": "Farmer",
      "public_id_number": "NA",
      "public_id_type": "NA",
      "state": "active",
      "status": "New",
      "time": "2018-07-05T23:02:22:278902",
      "type": "farmer",
      "updated_at": "2019-08-21 07:47:45",
      "vaId": "776d08d6-8a47-3124-9a84-a935a0cfa1f9",
      "value_chain": "Coffee"
    }
  ]
```
```
### Get completed orders

 #### Request
`GET api/v1/orders/completed`

 #### Response body

{
    "success": true,
    "completed_orders": [
        {
            "created_at": "2019-08-19 14:18:27",
            "district": "Buhweju",
            "name": "Mr. Lane Gulgowski",
            "orders": [
                {
                    "category": "Farming tools",
                    "price": 10000,
                    "product": "Korn Kali",
                    "qty": 165,
                    "src": "http://138.197.220.176:3000/assets/images/f9ab1da7-cd63-4e3d-8a96-b1c7dcc2cb42.png",
                    "stock": "",
                    "supplier": "World Food Program",
                    "unit": "50 Kgs"
                },
                {
                    "category": "Farming tools",
                    "price": 10000,
                    "product": "Dudu Cypher",
                    "qty": 2,
                    "src": "http://138.197.220.176:3000/assets/images/Jmugo75136.png",
                    "stock": "",
                    "supplier": "World Food Program",
                    "unit": "50 Kgs"
                }
            ],
            "payment": "mm",
            "phone": "1-487-610-6498 x36276",
            "time": "2018-10-20T20:22:51",
            "status": "Delivered",
            "total_cost": 1670000,
            "total_items": 2,
            "updated_at": "2019-08-19 14:18:27"
        },
        {
            "created_at": "2019-08-19 14:18:27",
            "district": "Rukungiri",
            "name": "Antonette Ruecker",
            "orders": [
                {
                    "category": "Farming tools",
                    "price": 10000,
                    "product": "Blended fertilizer",
                    "qty": 165,
                    "src": "http://138.197.220.176:3000/assets/images/f9ab1da7-cd63-4e3d-8a96-b1c7dcc2cb42.png",
                    "stock": "",
                    "supplier": "World Food Program",
                    "unit": "50 Kgs"
                },
                {
                    "category": "Farming tools",
                    "price": 10000,
                    "product": "Dudu Cypher",
                    "qty": 2,
                    "src": "http://138.197.220.176:3000/assets/images/Jmugo75136.png",
                    "stock": "",
                    "supplier": "World Food Program",
                    "unit": "50 Kgs"
                }
            ],
            "payment": "mm",
            "phone": "(880) 686-5228 x06574",
            "time": "2018-10-20T20:22:51",
            "status": "Delivered",
            "total_cost": 1670000,
            "total_items": 2,
            "updated_at": "2019-08-19 14:18:27"
        }
    ],
    "count": 2
}

```

### Get number of farmers who ordered inputs of different input categories

#### Request
`GET api/v1/farmers-orders`
```
{
    "success": true,
    "farmers_order": {
        "Pesticide": 12,
        "Seeds": 10,
        "Farming Tools": 15,
        "Fertilizer": 11,
        "Herbicide": 7 
    }
} 
```

### Get total orders

#### Request

`GET api/v1/orders?start_date={date}&end_date={date}`

Param `{date}` = `YYYY-MM-DD`

#### Response body

```
{
    "success": true,
    "totalOrders": 135
}
```

======================================================

### Create Diagnonis information

#### Request

`POST api/v1/diagnosis/{category}`

Param `{category}` = `pest` / `disease`

#### Request Body (form-data)

```
name:Kolakolosis
control:1. Okay control it well↵2. Go away
explanation:This is a test explanation
crop:Maize
cause:Virus
photo: (jpeg|png|jpg file)
```

#### Response

```
{
    "success": true,
    "message": "Diagnosis added successfully",
    "diagnosis": {
        "_id": "zmabBgk",
        "category": "disease",
        "cause": "lorem",
        "control": "lorem ipsum",
        "created_at": "2019-08-28 18:24:25",
        "crop": "Nguba",
        "eloquent_type": "diagnosis",
        "explanation": "lorem",
        "name": "Ankoro",
        "photo_url": "https://storage.googleapis.com/ezyagric_dev_image_bucket/diagnosis/logo_1567016662.png",
        "type": "diagnosis",
        "updated_at": "2019-08-28 18:24:25"
    }
}
```

======================================================

### Get all diagnosis

#### Request
`GET api/v1/diagnosis/{category}`

#### Response

Param `{category}` = `pest` / `disease`

```
{
    "success": true,
    "data": [
        {
            "_id": "0215956d-81ab-379c-b79e-e869b41e78dd",
            "category": "Disease",
            "cause": "Virus",
            "control": "1. Okay control it well\n2. Go away",
            "created_at": "2019-08-24 19:11:07",
            "crop": "Maize",
            "eloquent_type": "diagnosis",
            "explanation": "This is a test explanation",
            "name": "edited name",
            "photo_url": "https://storage.googleapis.com/ezyagric_dev_image_bucket/diagnosis/crop_pest.jpg",
            "type": "diagnosis",
            "updated_at": "2019-08-26 08:46:10"
        },
        {
            "_id": "0318f66d-694b-3b18-a2d1-06ed63c2fd10",
            "category": "Disease",
            "cause": "Virus",
            "control": "1. Okay control it well\n2. Go away",
            "created_at": "2019-08-24 19:11:07",
            "crop": "Maize",
            "eloquent_type": "diagnosis",
            "explanation": "This is a test explanation",
            "name": "edited name",
            "photo_url": "https://storage.googleapis.com/ezyagric_dev_image_bucket/diagnosis/crop_pest.jpg",
            "type": "diagnosis",
            "updated_at": "2019-08-26 08:46:10"
        },
        {
            "_id": "0470b0c3-2de2-37d4-9fbc-4bd613bcf49f",
            "category": "Disease",
            "cause": "Virus",
            "control": "1. Okay control it well\n2. Go away",
            "created_at": "2019-08-24 19:13:18",
            "crop": "Maize",
            "eloquent_type": "diagnosis",
            "explanation": "This is a test explanation",
            "name": "edited name",
            "photo_url": "https://storage.googleapis.com/ezyagric_dev_image_bucket/diagnosis/crop_pest.jpg",
            "updated_at": "2019-08-26 08:46:10"
        }
    ]
}
```

### Get single diagnosis

#### Request
`GET api/v1/diagnosis/{category}/{id}`

Param `{category}` = `pest` / `disease`

#### Response
```
{
    "success": true,
    "data": [
        {
            "_id": "0fd437f4-565d-3151-bf0a-ea6863deb506",
            "category": "Disease",
            "cause": "Virus",
            "control": "1. Use certified and disease-free seeds. 2. Control attacks of aphids and remove infected plants from the field.",
            "created_at": "2019-08-26 08:46:08",
            "crop": "Beans",
            "eloquent_type": "diagnosis",
            "explanation": [
                "Folding and twisting of leaves with a light and dark green patches",
                "The dark green patches are always near the veins. ",
                "Affected plants produce smaller, curved pods that appear slippery"
            ],
            "name": "edited name",
            "photo_url": "/images/uAge0125440.png",
            "updated_at": "2019-08-26 08:46:10"
        }
    ]
}
```
### Delete diagnosis

#### Request
`DELETE api/v1/diagnosis/{id}`

#### Response

```
{
    "success": true,
    "data": null
}
```
### Edit diagnosis information

#### Request
`POST api/v1/diagnosis/{category}/{id}`

Param `{category}` = `pest` / `disease`

#### Request Body (form-data)

```
name:Kolakolosis
control:1. Okay control it well↵2. Go away
explanation:This is a test explanation
crop:Maize
cause:Virus
photo: (jpeg|png|jpg file)
```



#### Response
```
{
    "success": true,
    "data": [
        {
            "_id": "0fd437f4-565d-3151-bf0a-ea6863deb506",
            "category": "Disease",
            "cause": "Virus",
            "control": "1. Use certified and disease-free seeds. 2. Control attacks of aphids and remove infected plants from the field.",
            "created_at": "2019-08-26 08:46:08",
            "crop": "Beans",
            "eloquent_type": "diagnosis",
            "explanation": [
                "Folding and twisting of leaves with a light and dark green patches",
                "The dark green patches are always near the veins. ",
                "Affected plants produce smaller, curved pods that appear slippery"
            ],
            "name": "edited name",
            "photo_url": "/images/uAge0125440.png",
            "updated_at": "2019-08-26 08:46:10"
        }
    ]
}

```
### Get received orders

 #### Request
`GET api/v1/orders/received`

 #### Response body

{
    "success": true,
    "received_orders": [
        {
            "created_at": "2019-08-19 14:18:27",
            "district": "Buhweju",
            "name": "Mr. Lane Gulgowski",
            "orders": [
                {
                    "category": "Farming tools",
                    "price": 10000,
                    "product": "Korn Kali",
                    "qty": 165,
                    "src": "http://138.197.220.176:3000/assets/images/f9ab1da7-cd63-4e3d-8a96-b1c7dcc2cb42.png",
                    "stock": "",
                    "supplier": "World Food Program",
                    "unit": "50 Kgs"
                },
                {
                    "category": "Farming tools",
                    "price": 10000,
                    "product": "Dudu Cypher",
                    "qty": 2,
                    "src": "http://138.197.220.176:3000/assets/images/Jmugo75136.png",
                    "stock": "",
                    "supplier": "World Food Program",
                    "unit": "50 Kgs"
                }
            ],
            "payment": "mm",
            "phone": "1-487-610-6498 x36276",
            "time": "2018-10-20T20:22:51",
            "status": "Intransit",
            "total_cost": 1670000,
            "total_items": 2,
            "updated_at": "2019-08-19 14:18:27"
        },
        {
            "created_at": "2019-08-19 14:18:27",
            "district": "Rukungiri",
            "name": "Antonette Ruecker",
            "orders": [
                {
                    "category": "Farming tools",
                    "price": 10000,
                    "product": "Blended fertilizer",
                    "qty": 165,
                    "src": "http://138.197.220.176:3000/assets/images/f9ab1da7-cd63-4e3d-8a96-b1c7dcc2cb42.png",
                    "stock": "",
                    "supplier": "World Food Program",
                    "unit": "50 Kgs"
                },
                {
                    "category": "Farming tools",
                    "price": 10000,
                    "product": "Dudu Cypher",
                    "qty": 2,
                    "src": "http://138.197.220.176:3000/assets/images/Jmugo75136.png",
                    "stock": "",
                    "supplier": "World Food Program",
                    "unit": "50 Kgs"
                }
            ],
            "payment": "mm",
            "phone": "(880) 686-5228 x06574",
            "time": "2018-10-20T20:22:51",
            "status": "Intransit",
            "total_cost": 1670000,
            "total_items": 2,
            "updated_at": "2019-08-19 14:18:27"
        }
    ],
    "count": 2
}
```

```
### Delete order of particular ID

#### Request
`DELETE api/v1/orders/{orderId}`

 #### Response body
{
    "success": true,
    "message": "Order deleted successfully",
    "data": null
}
```

```
### Add crop 

#### Request

`POST /api/v1/crops`

#### Request Body (form-data)

```
crop:Carrot
photo: (jpeg|png|jpg file)
```

#### Response

```
{
    "success": true,
    "message": "Crop added successfully",
    "Crop": {
        "_id": "3hZF62Q",
        "created_at": "2019-08-26 06:01:06",
        "crop": "Carrot",
        "eloquent_type": "our_crops",
        "photo_url": "http://storage.googleapis.com/ezyagric.com/Cotton2.jpg",
        "type": "our_crops",
        "updated_at": "2019-08-26 06:01:06"
    }
}
```
======================================================

#### Request

`GET /api/v1/crops/{id}`

#### Response

```
{
    "success": true,
    "data": [
        {
            "_id": "1rSFJvo",
            "created_at": "2019-08-26 06:19:00",
            "crop": "Carrot",
            "eloquent_type": "our_crops",
            "photo_url": "https://www.google.com/file/images/ae086c56-12cd-43cd-8f97-0b0e1267ab6c.png",
            "type": "our_crops",
            "updated_at": "2019-08-26 06:19:00"
        }
    ]
}
```
======================================================

#### Request

`GET /api/v1/crops`

#### Response

```
{
    "success": true,
    "data": [
        {
            "_id": "1rSFJvo",
            "created_at": "2019-08-26 06:19:00",
            "crop": "Carrot",
            "eloquent_type": "our_crops",
            "photo_url": "https://www.google.com/file/images/ae086c56-12cd-43cd-8f97-0b0e1267ab6c.png",
            "type": "our_crops",
            "updated_at": "2019-08-26 06:19:00"
        }
        {
            "_id": "xu9RGL7",
            "created_at": "2019-08-26 07:00:39",
            "crop": "Carrot",
            "eloquent_type": "our_crops",
            "photo_url": "https://www.google.com/file/images/ae086c56-12cd-43cd-8f97-0b0e1267ab6c.png",
            "type": "our_crops",
            "updated_at": "2019-08-26 07:00:39"
        }
    ]
}
```
### Edit Crop information

#### Request
`POST api/v1/crops/{id}`

#### Request Body (form-data)

```
crop:Maize
photo: (jpeg|png|jpg file)
```

#### Response
```
{
    "success": true,
    "data": {
        "_id": "0e28q83",
        "created_at": "2019-09-03 08:26:22",
        "crop": "Beans",
        "eloquent_type": "our_crops",
        "photo_url": "https://storage.googleapis.com/ezyagric_dev_image_bucket/crops/Screenshot 2019-07-15 at 19_1567530720.png",
        "type": "our_crops",
        "updated_at": "2019-09-03 17:12:03"
    }
}

```

### Delete Crop

#### Request
`DELETE api/v1/crops/{id}`

#### Response

```
{
    "success": true,
    "data": null
}
```
=======
### Get input stock

#### Request

`GET /api/v1/inputs`


#### Response
```
{
    "success": true,
      "available_stock": {
        "Seeds": 2000,
        "Herbicides": 9992
    },
    "total": 11992
}

`GET /api/v1/inputs`


#### Response
```
{
    "success": true,
      "available_stock": {
        "Seeds": 2000,
        "Herbicides": 9992
    },
    "total": 11992
}

```

==========================================================

### Create an Input

#### Request

`POST /api/v1/inputs`

#### Request Body (form-data)

```
name:npkkk
crops[]: ["maize","beans"]
category:Pesticides
description:An Input of quality
photo: (jpeg|png|jpg file)
price: [2000,4000]
unit: ["Kg","Ton"]
supplier: east cooperative
quantity: 29

```

#### Response

```
{
    "success": true,
    "input": {
        "type": "input",
        "name": "npkkk",
        "category": "Pesticides",
        "supplier": "east cooperative",
        "crops": [
            "maize",
            "beans"
        ],
        "description": "An Input of quality",
        "photo_url": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSDZLI0n9jEtSC-Lt8hU56X2VMXCI_sdbVF43oHZblfU7dlijak",
        "price": [
            2000,
            4000
        ],
        "unit": [
            "Kg",
            "Ton"
        ],
        "quantity": 20,
        "_id": "K28eYHn",
        "updated_at": "2019-08-23 12:36:07",
        "created_at": "2019-08-23 12:36:07",
        "id": 0
    }
}
```

==========================================================

### Get list of Inputs

#### Request

`GET /api/v1/inputs/list`

#### Response

```
{
    "success": true,
    "count": 1,
    "result": [
        {
            "DateAdded": "2018-08-31",
            "DateUpdated": "11/9/2018",
            "_id": "166cbb09-379f-3175-9910-60ada0ae0f14",
            "category": "Herbicides",
            "created_at": "2019-08-21 14:35:16",
            "crops": [
                "beans",
                "soya"
            ],
            "description": "Selective weed killer for beans and soya",
            "eloquent_type": "input",
            "name": "Beans Clean",
            "photo_url": "/images/7e185f0a-cfc5-45a3-bb4d-6ef6535a5042.png",
            "price": [
                38100,
                21000
            ],
            "quantity": 9992,
            "supplier": "Hangzhou Agrochemicals (U) Ltd",
            "type": "input",
            "unit": [
                "1Litre",
                "500ml"
            ],
            "updated_at": "2019-08-21 14:35:16"
        }
    ]
}
```


#### Agronomical Info

#### Request Body (form-data)
`POST /api/v1/agronomical-info/{id}`
{
    purpose: Land Preparationsss,
    description: Identify places with fertile soils, deep soil support proper root development.
    title: Site selections
    photo: (jpeg|png|jpg file)
}

#### Response
```
{
    "success": true,
    "message": "update successful",
    "data": {
        "_id": "5e199e37-0865-3cc4-bf57-a7f207a99f04",
        "admin": {
            "_id": "b34287ed-9044-3609-ba1a-b825ef3aa3c5",
            "adminRole": "Super Admin",
            "created_at": "2019-09-07 14:22:49",
            "eloquent_type": "admin",
            "email": "admin2020@gmail.com",
            "firstname": "Johnson",
            "lastname": "Smith",
            "type": "admin",
            "updated_at": "2019-09-10 06:47:21"
        },
        "auth": "b34287ed-9044-3609-ba1a-b825ef3aa3c5",
        "created_at": "2019-09-07 14:23:17",
        "crop": "Beans",
        "description": "for nothing",
        "eloquent": "cropinf",
        "eloquent_type": "cropinf",
        "photo_url": "https://storage.googleapis.com/ezyagric_dev_image_bucket/crop_info/Screen Shot 2019-05-27 at 20_1568271864.png",
        "purpose": "Land Preparation",
        "title": "cool",
        "type": "cropinf",
        "updated_at": "2019-09-12 07:04:29"
    }
}
```

`POST /api/v1/agronomical-info/{id}`

#### Response
```
{
    "success": true,
    "message": "Gronomical info successfully deleted"
}
```

### Mark an order as cleared

#### Request

`PATCH /orders/{id}/new`

#### Response
```
{
    "success": true,
    "message": "Successfully marked order as cleared."
}
```


==========================================================

### Get Order Distribution

#### Request

`GET /api/v1/order-distribution/{category}`

#### Response
Param `{category}` = `Farming Tools` 

{
    "success": true,
    "orderDistribution": {
        "Jan": 0,
        "Feb": 0,
        "Mar": 0,
        "Apr": 0,
        "May": 0,
        "Jun": 0,
        "Jul": 0,
        "Aug": 0,
        "Sep": 13,
        "Oct": 0,
        "Nov": 0,
        "Dec": 0
    },
    "category":"Farming Tools"
}
```