# Weather API Documentation

## Base URL

```
http://127.0.0.1:8000/api
```

---

# Current Weather

GET /weather?city=Lahore

Example:

```
GET /api/weather?city=Lahore
```

Response:

```json
{
  "success": true,
  "city": "Lahore",
  "country": "PK",
  "temperature": 39.9
}
```

---

# Forecast

GET /forecast?city=Lahore

Example:

```
GET /api/forecast?city=Lahore
```

Response:

```json
{
  "success": true,
  "forecast": [
    {
      "date": "2026-07-15",
      "temperature": 41.7
    }
  ]
}
```

---

# Air Quality

GET /air-quality?city=Lahore

Example:

```
GET /api/air-quality?city=Lahore
```

Response:

```json
{
  "success": true,
  "aqi": {
    "index": 2,
    "status": "Fair"
  }
}
```

## Status Codes

| Code | Meaning |
|------|---------|
|200|Success|
|404|City not found|
|422|Validation error|