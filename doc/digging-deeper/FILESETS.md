# Filesets


### Purpose
Bibles serve as a parent wrapper for all [files](FILES.md) and plain text resources. 

### Structure
The combination of fileset **id**,**asset_id**, and **set_type_code** is unique.

```json
{
  "abbr": "CRNWBT",
  "name": "The Bible in El Nayar",
  "vname": "Ɨ nyuucari tɨ jajcua, tɨ ajta cɨme'en raxa aɨjna ɨ Tavastara'a",
  "language": "Cora, El Nayar",
  "autonym": "El Nayar",
  "language_id": 6414,
  "iso": "eng",
  "date": "1611",
  "filesets": {
    "dbs-web": [
      {
        "id": "CRNWBT",
        "type": "text_plain",
        "size": "C"
      },
      {
        "id": "CRNWBT",
        "type": "text_format",
        "size": "C"
      }
    ],
    "dbp-prod": [
      {
        "id": "CRNWYC1611",
        "type": "text_plain",
        "size": "C"
      },
      {
        "id": "CRNWBT",
        "type": "text_format",
        "size": "C"
      }
    ]
  }
}
```